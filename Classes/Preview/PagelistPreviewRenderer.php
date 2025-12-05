<?php

declare(strict_types=1);

namespace Brightside\Pagelist\Preview;

use TYPO3\CMS\Backend\Preview\PreviewRendererInterface;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Collection\LazyRecordCollection;
// The RecordInterface is what v14 returns, useful for type hinting/checking
use TYPO3\CMS\Core\Domain\RecordInterface; 
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;

class PagelistPreviewRenderer extends StandardContentPreviewRenderer implements PreviewRendererInterface
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
    // Preview Rendering Methods
    // ------------------------------
    
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $record = $item->getRecord();
        
        // Use the new helper function consistently across all fields
        $getValue = fn(string $field) => $this->getRecordValueSafely($record, $field, '');

        // 📝 Safely retrieve all fields
        $tx_pagelist_recursive = $getValue('tx_pagelist_recursive');
        $tx_pagelist_orderby = $getValue('tx_pagelist_orderby');
        $tx_pagelist_startfrom = $getValue('tx_pagelist_startfrom');
        $tx_pagelist_limit = $getValue('tx_pagelist_limit');
        $tx_pagelist_template = $getValue('tx_pagelist_template');
        $tx_pagelist_titlewrap = $getValue('tx_pagelist_titlewrap');
        $tx_pagelist_disableimages = $getValue('tx_pagelist_disableimages');
        $tx_pagelist_cropratio = $getValue('tx_pagelist_cropratio');
        $tx_pagelist_disableabstract = $getValue('tx_pagelist_disableabstract');
        
        // Pagination
        $tx_paginatedprocessors_paginationenabled = $getValue('tx_paginatedprocessors_paginationenabled');
        $tx_paginatedprocessors_itemsperpage = $getValue('tx_paginatedprocessors_itemsperpage');
        $tx_paginatedprocessors_pagelinksshown = $getValue('tx_paginatedprocessors_pagelinksshown');
        $tx_paginatedprocessors_anchor = $getValue('tx_paginatedprocessors_anchor');
        $tx_paginatedprocessors_anchorid = $getValue('tx_paginatedprocessors_anchorid');
        $tx_paginatedprocessors_urlsegment = $getValue('tx_paginatedprocessors_urlsegment');

        // Safely retrieve relation fields, defaulting to null/empty array if missing
        $pages = $this->getRecordValueSafely($record, 'pages');
        $selectedCategories = $this->getRecordValueSafely($record, 'selected_categories');
        $authorFieldName = 'tx_pagelist_authors';
        $authorUids = $this->getRecordValueSafely($record, $authorFieldName, []);

        $pageTitles = $this->getPageTitles($pages);
        $categories = $this->getCategories($selectedCategories);
        $authors = $this->getAuthors($authorUids);
        
        $output = '<div class="element-preview-content">';
        
        // Helper function to wrap the entire line's content in the link
        $wrapLineInLink = function (string $content) use ($item): string {
            // Note: $item->getRecord() is passed here, which is either an array (v12) or object (v14)
            return $this->linkEditContent($content, $item->getRecord());
        };

        // --- FILTER CONDITIONS ---
        
        if ($pageTitles) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Pages:</strong><span>' . implode(', ', $pageTitles) . '</span>';
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        // 1. Categories filter (ANY)
        if ($categories) {
            $categoryTitles = implode(', ', array_column($categories, 'title'));
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Filter by category (ANY):</strong>' . $categoryTitles;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        // 2. Authors filter (ANY) - conditionally prepend "AND" if categories are also set
        if ($authors) {
            $authorNames = implode(', ', array_column($authors, 'firstname', 'lastname'));
            // Replicates Fluid logic: <f:if condition="{catTitles} && {authors}">AND </f:if>
            $prefix = !empty($categories) ? 'AND ' : '';
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">' . $prefix . 'Filter by person (ANY):</strong>' . $authorNames;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }
        
        // --- CORE FIELD CONDITIONS ---

        if($tx_pagelist_recursive) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Recursive level:</strong>' . $tx_pagelist_recursive;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        // 3. Order by: Use new helper for human-readable label
        if($tx_pagelist_orderby) {
            $orderByLabel = $this->getOrderByLabel($tx_pagelist_orderby);
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Order by:</strong>' . $orderByLabel;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        if($tx_pagelist_startfrom) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Start from page:</strong>' . $tx_pagelist_startfrom;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }
        if($tx_pagelist_limit) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Limit to pages:</strong>' . $tx_pagelist_limit;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }
        if($tx_pagelist_template) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Template:</strong>' . $tx_pagelist_template;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }
        if($tx_pagelist_disableimages) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Images:</strong>disabled';
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        } else if ($tx_pagelist_disableimages === '0' || $tx_pagelist_disableimages === 0) { // Check for explicit '0' or 0
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Images:</strong>enabled';
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
            $line2Content = '<strong style="display: inline-block; min-width: 200px;">Images crop:</strong>' . $tx_pagelist_cropratio;
            $output .= '<div>' . $wrapLineInLink($line2Content) . '</div>';

        }
        if($tx_pagelist_disableabstract) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Abstract:</strong>disabled';
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        } else if ($tx_pagelist_disableabstract === '0' || $tx_pagelist_disableabstract === 0) { // Check for explicit '0' or 0
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Abstract:</strong>enabled';
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }
        
        if($tx_pagelist_titlewrap) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Title wrap:</strong>' . $tx_pagelist_titlewrap;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }
        
        // Pagination block
        if($tx_paginatedprocessors_paginationenabled) {
            // Build the content of the whole pagination summary line
            $paginationContent = '<strong>Pagination:</strong> active';

            if($tx_paginatedprocessors_itemsperpage) {
                $paginationContent .= ' •&nbsp;items per page: ' . $tx_paginatedprocessors_itemsperpage;
            }
            if($tx_paginatedprocessors_pagelinksshown) {
                $paginationContent .= ' •&nbsp;page links shown: ' . $tx_paginatedprocessors_pagelinksshown;
            }
            
            // Check for the value of anchorid being set to something > 0
            if($tx_paginatedprocessors_anchorid > '0') { 
                $paginationContent .= ' •&nbsp;focus on page change: ' . $tx_paginatedprocessors_anchorid;
            } else {
                if($tx_paginatedprocessors_anchor) {
                    $paginationContent .= ' •&nbsp;focus self on page change';
                }
            }
            if($tx_paginatedprocessors_urlsegment) {
                $paginationContent .= ' •&nbsp;anchor: ' . $tx_paginatedprocessors_urlsegment;
            }
            
            // Wrap the whole line's content in the link
            $output .= '<br /><div>' . $wrapLineInLink($paginationContent) . '</div>';
        }
        
        $output .= '</div>';

        return $output;
    }
    
    // ------------------------------
    // Helper methods
    // ------------------------------

    /**
     * Maps the tx_pagelist_orderby value to a human-readable label,
     * replicating the logic from the Fluid template.
     */
    private function getOrderByLabel(string $orderByValue): string
    {
        return match ($orderByValue) {
            'pages.sorting' => 'page tree',
            'tx_pagelist_datetime DESC' => 'date (now → past)',
            'tx_pagelist_datetime ASC' => 'date (past → now)',
            'tx_pagelist_eventstart ASC' => 'event start (now → future)',
            'tx_pagelist_eventstart DESC' => 'event start (future → now)',
            'lastUpdated DESC' => 'last updated (now → past)',
            'lastUpdated ASC' => 'last updated (past → now)',
            'title ASC' => 'page title (a → z)',
            'title DESC' => 'page title (z → a)',
            default => $orderByValue, // Fallback to the raw value if not found
        };
    }
    
    private function getPageTitles(mixed $pages): array
    {
        // $pages is a string (v12) or LazyRecordCollection (v14)
        $pageIds = $this->extractUids($pages); 
        if (empty($pageIds)) return [];

        $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
        $titles = [];
        foreach ($pageIds as $uid) {
            $page = $pageRepository->getPage((int)$uid);
            if ($page && isset($page['title'])) {
                $titles[] = $page['title'];
            }
        }
        return $titles;
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