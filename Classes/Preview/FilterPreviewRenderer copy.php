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
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;

class FilterPreviewRenderer extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(
            'EXT:pagelist/Resources/Private/Templates/Backend/FilterPreview.html',
        );

        $record = $item->getRecord();
        $view->assign('filteritem', $record);

        // Initialize an array to store pagelist records
        $pagelistRecords = [];

        $CType = $record['CType'];
        $targets = $record['tx_pagelist_filtertarget'];

        $selectedCategories = $record['selected_categories'];
        $authorUids = $record['tx_pagelist_authors'];


        // Get selected targets
        if ($targets) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
            $query = $queryBuilder
                ->select('*')
                ->from('tt_content')
                ->where(
                    $queryBuilder->expr()->in('uid', $targets)
                );
            $queryResult = $query->executeQuery();
            $targets = $queryResult->fetchAllAssociative();
            $view->assign('targets', $targets);
        }

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

        $out = $view->render();
        return $this->linkEditContent($out, $record);
    }
}
