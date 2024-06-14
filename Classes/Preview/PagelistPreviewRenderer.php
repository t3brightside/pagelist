<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Brightside\Pagelist\Preview;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\MathUtility;

class PagelistPreviewRenderer extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(
            'EXT:pagelist/Resources/Private/Templates/Backend/Preview.html',
        );

        $record = $item->getRecord();
        $view->assign('pagelistitem', $record);

        // Initialize an array to store pagelist records
        $pagelistRecords = [];

        $CType = $record['CType'];
        $pids = $record['pages'];

        $selectedCategories = $record['selected_categories'];
        $authorUids = $record['tx_pagelist_authors'];

        // Get page titles
        if ($pids) {
            $pageIds = explode(',', $pids);
            $pageIds = array_map('intval', $pageIds);
            $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
            $pageTitles = [];
            foreach ($pageIds as $pageUid) {
                $pageData = $pageRepository->getPage($pageUid);
                if ($pageData && isset($pageData['title'])) {
                    $pageTitles[] = $pageData['title'];
                }
            }
            $view->assign('pageTitles', $pageTitles);
        }

        // Show filtering selections
        if ($CType == 'pagelist_sub') {

            // Get selected catefories
            if ($selectedCategories) {
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
                $query = $queryBuilder
                    ->select('title')
                    ->from('sys_category')
                    ->where(
                        $queryBuilder->expr()->in('uid', $selectedCategories)
                    );
                $queryResult = $query->executeQuery();
                $cetegoryTitles = $queryResult->fetchAllAssociative();
                $view->assign('catTitles', $cetegoryTitles);
            }

            // Get selected authors
            if ($authorUids) {
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_personnel_domain_model_person');
                $query = $queryBuilder
                    ->select('*')
                    ->from('tx_personnel_domain_model_person')
                    ->where(
                        $queryBuilder->expr()->in('uid', $authorUids)
                    );
                $queryResult = $query->executeQuery();
                $authors = $queryResult->fetchAllAssociative();
                $view->assign('authors', $authors);
            }
        }

        $out = $view->render();
        return $this->linkEditContent($out, $record);
    }
}
