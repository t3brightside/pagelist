<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addPageTSConfig('@import "EXT:pagelist/Configuration/PageTS/setup.typoscript"');

$iconRegistry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'mimetypes-x-content-pagelist',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:pagelist/Resources/Public/Icons/mimetypes-x-content-pagelist.svg']
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['pagelist'] = \Brightside\Pagelist\Hooks\PageLayoutView\PagelistContentElementPreviewRenderer::class;

if (ExtensionManagementUtility::isLoaded('personnel')) {
    ExtensionManagementUtility::addTypoScriptConstants('
		pagelist.personnelIsLoaded = 1
	');
} else {
    ExtensionManagementUtility::addTypoScriptConstants('
		pagelist.personnelIsLoaded = 0
	');
}

ExtensionUtility::configurePlugin(
    'Pagelist',
    'Pagelist',
    [
        'Pagelist' => 'pagelist',
    ]
);
