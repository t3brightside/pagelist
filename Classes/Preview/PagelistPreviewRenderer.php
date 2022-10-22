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
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Backend\Preview\PreviewRendererInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
/**
 * Contains a preview rendering for the page module of CType="textmedia"
 * @internal this is a concrete TYPO3 hook implementation and solely used for EXT:frontend and not part of TYPO3's Core API.
 */
class PagelistPreviewRenderer extends StandardContentPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $content = '';
        $row = $item->getRecord();
        if ($row['CType'] === 'pagelist_selected' || $row['CType'] === 'pagelist_sub' || $row['CType'] === 'pagelist_category') {
            if ($row['CType'] === 'pagelist_selected') {
                $content = $this->linkEditContent('<span style="display: block; margin-top: 0.3em;">Pagelist: selected '. $row['pages'] .'</span>', $row);
            }
            if ($row['CType'] === 'pagelist_sub') {
                $content = $this->linkEditContent('<span style="display: block; margin-top: 0.3em;">Pagelist: subpages from '. $row['pages'] .'</span>', $row);
            }
            if ($row['CType'] === 'pagelist_category') {
                $content = $this->linkEditContent('<span style="display: block; margin-top: 0.3em;">Pagelist: from category '. $row['selected_categories'] .'</span>', $row);
            }
            $content .= '<ul style="margin: 0; padding: 0.2em 1.4em;">';
            if ($row['tx_pagelist_template']) {
                $content .= '<li>' . $this->linkEditContent($this->renderText('Template: ' . $row['tx_pagelist_template']), $row) . '</li>';
            }
            if ($row['tx_pagelist_titlewrap']) {
                $content .= '<li>' . $this->linkEditContent($this->renderText('Title tag: ' . $row['tx_pagelist_titlewrap']), $row) . '</li>';
            }
            if ($row['CType'] === 'pagelist_sub') {
                if ($row['tx_pagelist_orderby']) {
                    $content .= '<li>' . $this->linkEditContent($this->renderText('Order by: ' . $row['tx_pagelist_orderby']), $row) . '</li>';
                }
            }
            if ($row['CType'] === 'pagelist_sub' || $row['CType'] === 'pagelist_category') {
                if ($row['tx_pagelist_startfrom']) {
                    $content .= '<li>' . $this->linkEditContent($this->renderText('Start from: ' . $row['tx_pagelist_startfrom']), $row) . '</li>';
                }
            }
            if ($row['CType'] === 'pagelist_sub' || $row['CType'] === 'pagelist_category') {
                if ($row['tx_pagelist_limit']) {
                    $content .= '<li>' . $this->linkEditContent($this->renderText('Items shown: ' . $row['tx_pagelist_limit']), $row) . '</li>';
                }
            }
            if ($row['tx_pagelist_disableimages'] == 1) {
                $content .= '<li>' . $this->linkEditContent('Images: disabled', $row) . '</li>';
            }
            if ($row['tx_pagelist_disableabstract'] == 1) {
                $content .= '<li>' . $this->linkEditContent('Introduction: disabled', $row) . '</li>';
            }
            if ($row['tx_pagelist_authors'] > 0) {
                $content .= '<li>' . $this->linkEditContent('Author filter: active', $row) . '</li>';
            }
            if ($row['tx_paginatedprocessors_paginationenabled'] == 1) {
                $content .= '<li>' . $this->linkEditContent('Pagination: enabled', $row) . '</li>';
            }
            if ($row['tx_paginatedprocessors_itemsperpage'] && $row['tx_paginatedprocessors_paginationenabled'] == 1) {
                $content .= '<li>' . $this->linkEditContent($this->renderText('Items per page: ' . $row['tx_paginatedprocessors_itemsperpage']), $row) . '</li>';
            }
            if ($row['tx_paginatedprocessors_pagelinksshown'] && $row['tx_paginatedprocessors_paginationenabled'] == 1) {
                $content .= '<li>' . $this->linkEditContent($this->renderText('Links shown: ' . $row['tx_paginatedprocessors_pagelinksshown']), $row) . '</li>';
            }
            if ($row['tx_paginatedprocessors_urlsegment'] && $row['tx_paginatedprocessors_paginationenabled'] == 1) {
                $content .= '<li>' . $this->linkEditContent($this->renderText('URL segment: ' . $row['tx_paginatedprocessors_urlsegment']), $row) . '</li>';
            }
            $content .= '</ul>';
        }
        return $content;

    }
}
