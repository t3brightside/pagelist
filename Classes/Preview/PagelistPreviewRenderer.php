<?php

declare(strict_types=1);

namespace Brightside\Pagelist\Preview;

use TYPO3\CMS\Backend\Preview\PreviewRendererInterface;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Collection\LazyRecordCollection;
use TYPO3\CMS\Core\Domain\RecordInterface; 
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility; // Import for BackendUtility::getPagesTSconfig

class PagelistPreviewRenderer extends StandardContentPreviewRenderer implements PreviewRendererInterface
{
    private const TT_CONTENT_TABLE = 'tt_content'; // Added constant for TCA checks

    /**
     * Safely retrieves a field value from the record, supporting both array (v12) 
     * and RecordInterface (v14+) access.
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

    /**
     * Checks if a field is available for editing based on permissions AND TSconfig overrides (User/Page).
     */
    private function isFieldAvailableForRecord(array|RecordInterface $record, string $fieldName): bool
    {
        // 1. Check if the field exists in tt_content columns array (TCA structure check)
        if (!isset($GLOBALS['TCA'][self::TT_CONTENT_TABLE]['columns'][$fieldName])) {
            return false;
        }
        
        // 2. Permission Check (non_exclude_fields)
        if (!isset($GLOBALS['BE_USER']) || !$GLOBALS['BE_USER']->check('non_exclude_fields', self::TT_CONTENT_TABLE . ':' . $fieldName)) {
            return false;
        }

        // --- 3. TSconfig Removal/Disabled Check (User + Page) ---
        
        $pid = $this->getRecordValueSafely($record, 'pid', 0);
        $CType = $this->getRecordValueSafely($record, 'CType', '');

        // Fetch both configurations (using documented API)
        $userTsConfig = $GLOBALS['BE_USER']->getTSConfig();
        $pageTsConfig = $pid > 0 ? BackendUtility::getPagesTSconfig((int)$pid) : [];
        
        // Define all places where the field config might live:
        $tsConfigPaths = [
            // [Table/Field config array, CType specific config array]
            [
                $userTsConfig['TCEFORM.'][self::TT_CONTENT_TABLE . '.'][$fieldName . '.'] ?? [],
                $userTsConfig['TCEFORM.'][self::TT_CONTENT_TABLE . '.'][$fieldName . '.']['types.'][$CType . '.'] ?? [],
            ],
            [
                $pageTsConfig['TCEFORM.'][self::TT_CONTENT_TABLE . '.'][$fieldName . '.'] ?? [],
                $pageTsConfig['TCEFORM.'][self::TT_CONTENT_TABLE . '.'][$fieldName . '.']['types.'][$CType . '.'] ?? [],
            ],
        ];

        foreach ($tsConfigPaths as [$globalConfig, $typeConfig]) {
            // Check 3a: Global Field Rule (TCEFORM.tt_content.field.disabled = 1)
            $isDisabledGlobal = (bool)($globalConfig['disabled'] ?? false);
            if ($isDisabledGlobal) {
                return false;
            }

            // Check 3b: Conditional Type Rule (TCEFORM.tt_content.field.types.CType.disabled = 1)
            $isDisabledType = (bool)($typeConfig['disabled'] ?? false);
            if ($isDisabledType) {
                return false;
            }

            // Check 3c: Explicit Removal (TCEFORM.field.removeItems = fieldname)
            $removeItems = $globalConfig['removeItems'] ?? '';
            if (GeneralUtility::inList($removeItems, $fieldName)) {
                return false;
            }
        }
        
        // If all checks pass, the field is available for viewing/editing.
        return true;
    }

    // ------------------------------
    // Preview Rendering Methods
    // ------------------------------
    
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $record = $item->getRecord();
        
        // Use the new helper function consistently across all fields
        $getValue = fn(string $field) => $this->getRecordValueSafely($record, $field, '');
        // Helper function to check field availability
        $isFieldAvailable = fn(string $field) => $this->isFieldAvailableForRecord($record, $field);

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
        
        // --- HTML Output Generation ---

        $output = '<div class="element-preview-content">';
        
        // Helper function to wrap the entire line's content in the link
        $wrapLineInLink = function (string $content) use ($item): string {
            // Note: $item->getRecord() is passed here, which is either an array (v12) or object (v14)
            return $this->linkEditContent($content, $item->getRecord());
        };
        
        if ($isFieldAvailable('pages') && $pageTitles) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Pages:</strong><span>' . implode(', ', $pageTitles) . '</span>';
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        if ($isFieldAvailable('selected_categories') && $categories) {
            $categoryTitles = implode(', ', array_column($categories, 'title'));
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Filter by category (ANY):</strong>' . $categoryTitles;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        if ($isFieldAvailable($authorFieldName) && $authors) {
            $authorNames = implode(', ', array_column($authors, 'firstname', 'lastname'));
            // Replicates Fluid logic: <f:if condition="{catTitles} && {authors}">AND </f:if>
            $prefix = !empty($categories) ? 'AND ' : '';
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">' . $prefix . 'Filter by person (ANY):</strong>' . $authorNames;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }
                
        if($isFieldAvailable('tx_pagelist_recursive') && $tx_pagelist_recursive) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Recursive level:</strong>' . $tx_pagelist_recursive;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        if($isFieldAvailable('tx_pagelist_orderby') && $tx_pagelist_orderby) {
            $orderByLabel = $this->getOrderByLabel($tx_pagelist_orderby);
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Order by:</strong>' . $orderByLabel;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }
        
        if($isFieldAvailable('tx_pagelist_startfrom') && $tx_pagelist_startfrom) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Start from page:</strong>' . $tx_pagelist_startfrom;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        if($isFieldAvailable('tx_pagelist_limit') && $tx_pagelist_limit) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Limit to pages:</strong>' . $tx_pagelist_limit;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }

        if($isFieldAvailable('tx_pagelist_template') && $tx_pagelist_template) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Template:</strong>' . $tx_pagelist_template;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }
        
        if($isFieldAvailable('tx_pagelist_disableimages')) {
            if($tx_pagelist_disableimages) {
                $lineContent = '<strong style="display: inline-block; min-width: 200px;">Images:</strong>disabled';
                $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
            } else { 
                $lineContent = '<strong style="display: inline-block; min-width: 200px;">Images:</strong>enabled';
                $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
                
                if ($isFieldAvailable('tx_pagelist_cropratio') && $tx_pagelist_cropratio) {
                    $line2Content = '<strong style="display: inline-block; min-width: 200px;">Images crop:</strong>' . $tx_pagelist_cropratio;
                    $output .= '<div>' . $wrapLineInLink($line2Content) . '</div>';
                }

            }
        }
        
        if($isFieldAvailable('tx_pagelist_disableabstract')) {
            if($tx_pagelist_disableabstract) {
                $lineContent = '<strong style="display: inline-block; min-width: 200px;">Abstract:</strong>disabled';
                $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
            } else { 
                $lineContent = '<strong style="display: inline-block; min-width: 200px;">Abstract:</strong>enabled';
                $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
            }
        }
        
        if($isFieldAvailable('tx_pagelist_titlewrap') && $tx_pagelist_titlewrap) {
            $lineContent = '<strong style="display: inline-block; min-width: 200px;">Title wrap:</strong>' . $tx_pagelist_titlewrap;
            $output .= '<div>' . $wrapLineInLink($lineContent) . '</div>';
        }
        
        // Pagination block
        $isPaginationConfigAvailable = $isFieldAvailable('tx_paginatedprocessors_paginationenabled');

        if($tx_paginatedprocessors_paginationenabled && $isPaginationConfigAvailable) {
            // Build the content of the whole pagination summary line
            $paginationContent = '<br /><strong>Pagination:</strong> active';

            if($isFieldAvailable('tx_paginatedprocessors_itemsperpage') && $tx_paginatedprocessors_itemsperpage) {
                $paginationContent .= ' •&nbsp;items per page: ' . $tx_paginatedprocessors_itemsperpage;
            }

            if($isFieldAvailable('tx_paginatedprocessors_pagelinksshown') && $tx_paginatedprocessors_pagelinksshown) {
                $paginationContent .= ' •&nbsp;page links shown: ' . $tx_paginatedprocessors_pagelinksshown;
            }
            
            if($isFieldAvailable('tx_paginatedprocessors_anchorid') && $tx_paginatedprocessors_anchorid > '0') { 
                $paginationContent .= ' •&nbsp;focus on page change: ' . $tx_paginatedprocessors_anchorid;
            } else {
                if($isFieldAvailable('tx_paginatedprocessors_anchor') && $tx_paginatedprocessors_anchor) {
                    $paginationContent .= ' •&nbsp;focus self on page change';
                }
            }

            if($isFieldAvailable('tx_paginatedprocessors_urlsegment') && $tx_paginatedprocessors_urlsegment) {
                $paginationContent .= ' •&nbsp;anchor: ' . $tx_paginatedprocessors_urlsegment;
            }
            
            $output .= '<div>' . $wrapLineInLink($paginationContent) . '</div>';
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