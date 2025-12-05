<?php

declare(strict_types=1);

namespace Brightside\Pagelist\Preview;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Collection\LazyRecordCollection;
// The RecordInterface is what v14 returns, useful for type hinting/checking
use TYPO3\CMS\Core\Domain\RecordInterface;
use TYPO3\CMS\Backend\Preview\PreviewRendererInterface; // Optional, but good practice if you implement it

class FilterPreviewRenderer extends StandardContentPreviewRenderer implements PreviewRendererInterface // Added PreviewRendererInterface
{
    /**
     * Safely retrieves a field value from the record, supporting both array (v12)
     * and RecordInterface (v14+) access.
     *
     * @param array|RecordInterface $record The content record data
     * @param string $field The field name to retrieve
     * @param mixed $default The default value if the field is not present
     * @return mixed
     */
    private function getRecordValueSafely(array|RecordInterface $record, string $field, mixed $default = null): mixed
    {
        if (is_array($record)) {
            // TYPO3 v12 (Array access)
            return $record[$field] ?? $default;
        } elseif ($record instanceof RecordInterface) {
            // TYPO3 v14+ (RecordInterface object access)
            return $record->has($field) ? $record->get($field) : $default;
        }
        return $default;
    }

    // ------------------------------
    // Preview Rendering Method
    // ------------------------------

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $record = $item->getRecord();

        // Use the new helper function consistently across all fields
        $getValue = fn(string $field) => $this->getRecordValueSafely($record, $field, '');

        // Safely retrieve relation fields, defaulting to null/empty array if missing
        // NOTE: The original example only showed selected_categories and tx_pagelist_authors
        $targets = $this->getRecordValueSafely($record, 'tx_pagelist_filtertarget');
        $selectedCategories = $this->getRecordValueSafely($record, 'selected_categories');
        $authorUids = $this->getRecordValueSafely($record, 'tx_pagelist_authors', []);

        $targetRecords = $this->getTargetRecords($targets);
        $categories = $this->getCategories($selectedCategories);
        $authors = $this->getAuthors($authorUids);

        $output = '<div class="element-preview-content">';

        // Helper function to wrap the entire line's content in the link
        $wrapLineInLink = function (string $content) use ($record): string {
            // Note: $record is either an array (v12) or object (v14)
            return $this->linkEditContent($content, $record);
        };

        // --- FILTER CONDITIONS ---

        // 1. Filter Targets (Replicates the logic from your original query)
        if ($targetRecords) {
            $targetTitles = implode(', ', array_column($targetRecords, 'header'));
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Filter target:</strong><span>' . $targetTitles . '</span>';
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        // 2. Categories filter (ANY)
        if ($categories) {
            $categoryTitles = implode(', ', array_column($categories, 'title'));
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Listed categories:</strong>' . $categoryTitles;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        // 3. Authors filter (ANY)
        if ($authors) {
            $authorNames = implode(', ', array_column($authors, 'firstname', 'lastname'));
            // Replicates Fluid logic: <f:if condition="{categories} && {authors}">AND </f:if>
            // No prefix logic needed for this specific component unless requested, unlike the PageList.
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Authors filter (ANY):</strong>' . $authorNames;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        $output .= '</div>';

        return $output;
    }

    // ------------------------------
    // Helper methods (Copied from PagelistPreviewRenderer)
    // ------------------------------

    private function getTargetRecords(mixed $targets): array
    {
        // $targets is a string (v12) or LazyRecordCollection (v14)
        $targetIds = $this->extractUids($targets);
        if (empty($targetIds)) return [];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tt_content');

        return $queryBuilder
            ->select('uid', 'header')
            ->from('tt_content')
            ->where($queryBuilder->expr()->in('uid', $targetIds))
            ->executeQuery()
            ->fetchAllAssociative() ?: [];
    }

    private function getCategories(mixed $categories): array
    {
        $categoryIds = $this->extractUids($categories);
        if (empty($categoryIds)) return [];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');

        return $queryBuilder
            ->select('uid', 'title')
            ->from('sys_category')
            ->where($queryBuilder->expr()->in('uid', $categoryIds))
            ->executeQuery()
            ->fetchAllAssociative() ?: [];
    }

    private function getAuthors(mixed $authors): array
    {
        $authorIds = $this->extractUids($authors);
        if (empty($authorIds)) return [];

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_personnel_domain_model_person');

        return $queryBuilder
            ->select('uid', 'firstname', 'lastname')
            ->from('tx_personnel_domain_model_person')
            ->where($queryBuilder->expr()->in('uid', $authorIds))
            ->executeQuery()
            ->fetchAllAssociative() ?: [];
    }

    /**
     * Extract UIDs from a field that could be a string CSV (v12) or LazyRecordCollection (v14)
     */
    private function extractUids(mixed $field): array
    {
        if (!$field) return [];

        // CSV string (v12)
        if (is_string($field)) {
            return array_map('intval', array_filter(explode(',', $field)));
        }

        // LazyRecordCollection (v14)
        if ($field instanceof LazyRecordCollection) {
            $uids = [];
            foreach ($field as $record) {
                // Check for RecordInterface if needed, though $record->has('uid') is common
                if (is_object($record) && method_exists($record, 'has') && $record->has('uid')) {
                    $uids[] = (int)$record->get('uid');
                }
            }
            return $uids;
        }

        return [];
    }
}