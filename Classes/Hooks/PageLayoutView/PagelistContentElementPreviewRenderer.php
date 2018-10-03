<?php
namespace Brightside\Pagelist\Hooks\PageLayoutView;

use \TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use \TYPO3\CMS\Backend\View\PageLayoutView;

class PagelistContentElementPreviewRenderer implements PageLayoutViewDrawItemHookInterface {
	 /**
     * Preprocesses the preview rendering of a content element of type "textmedia"
     *
     * @param \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent object
     * @param bool $drawItem Whether to draw the item using the default functionality
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     *
     * @return void
     */

	 public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row) {
		if ($row['CType'] === 'pagelist_selected' || $row['CType'] === 'pagelist_sub' || $row['CType'] === 'pagelist_category') {
			if ($row['CType'] === 'pagelist_selected') {
				$itemContent = $parentObject->linkEditContent('<span style="display: block; margin-top: 0.3em;">Pagelist: selected '. $row['pages'] .'</span>', $row);
			}
			if ($row['CType'] === 'pagelist_sub') {
        $itemContent = $parentObject->linkEditContent('<span style="display: block; margin-top: 0.3em;">Pagelist: subpages '. $row['pages'] .'</span>', $row);
			}
			if ($row['CType'] === 'pagelist_category') {
				$itemContent = $parentObject->linkEditContent('<span style="display: block; margin-top: 0.3em;">Pagelist: category '. $row['selected_categories'] .'</span>', $row);
			}
			$itemContent .= '<ul style="margin: 0; padding: 0.2em 1.4em;">';
			if ($row['CType'] === 'pagelist_sub') {
				if ($row['tx_pagelist_orderby']) {
	        $itemContent .= '<li>' . $parentObject->linkEditContent($parentObject->renderText('Order by: ' . $row['tx_pagelist_orderby']), $row) . '</li>';
				}
			}
			if ($row['CType'] === 'pagelist_sub' || $row['CType'] === 'pagelist_category') {
				if ($row['tx_pagelist_startfrom']) {
	        $itemContent .= '<li>' . $parentObject->linkEditContent($parentObject->renderText('Start from: ' . $row['tx_pagelist_startfrom']), $row) . '</li>';
				}
			}
			if ($row['CType'] === 'pagelist_sub' || $row['CType'] === 'pagelist_category') {
				if ($row['tx_pagelist_limit']) {
	        $itemContent .= '<li>' . $parentObject->linkEditContent($parentObject->renderText('Items shown: ' . $row['tx_pagelist_limit']), $row) . '</li>';
				}
			}
			if ($row['tx_pagelist_disableimages'] == 1) {
        $itemContent .= '<li>' . $parentObject->linkEditContent('Images: disabled', $row) . '</li>';
			}
			if ($row['tx_pagelist_disableabstract'] == 1) {
        $itemContent .= '<li>' . $parentObject->linkEditContent('Introduction: disabled', $row) . '</li>';
			}
			if ($row['tx_pagelist_paginate'] == 1) {
        $itemContent .= '<li>' . $parentObject->linkEditContent('Pagination: enabled', $row) . '</li>';
			}
			if ($row['tx_pagelist_paginateitems'] > 1 && $row['tx_pagelist_paginate'] == 1) {
				$itemContent .= '<li>' . $parentObject->linkEditContent($parentObject->renderText('Items per page: ' . $row['tx_pagelist_paginateitems']), $row) . '</li>';
			}
			if ($row['tx_pagelist_paginateitems'] == 0 && $row['tx_pagelist_paginate'] == 1) {
				$itemContent .= '<li>' . $parentObject->linkEditContent('Items per page: TypoScript', $row) . '</li>';
			}
			$itemContent .= '</ul>';
			$drawItem = FALSE;
		}
	}
}
