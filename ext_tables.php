<?php
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

defined('TYPO3') || die('Access denied.');

(function () {
    $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
    $pagelistConiguration = $extensionConfiguration->get('pagelist');

    $pagelistArticle = 136;
    $pagelistEvent = 137;
    $pagelistProduct = 138;
    $pagelistVacancy = 139;

    if ($pagelistConiguration['pagelistEnableArticles']) {
        $GLOBALS['PAGES_TYPES'][$pagelistArticle] = [
            'type' => 'web',
            'allowedTables' => '*',
        ];
        GeneralUtility::makeInstance(IconRegistry::class)->registerIcon(
            'apps-pagetree-article',
            SvgIconProvider::class,
            [
                'source' => 'EXT:pagelist/Resources/Public/Icons/ico_article.svg',
            ]
        );
        ExtensionManagementUtility::addUserTSConfig(
            'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . $pagelistArticle . ')'
        );
    }
    if ($pagelistConiguration['pagelistEnableEvents']) {
        $GLOBALS['PAGES_TYPES'][$pagelistEvent] = [
            'type' => 'web',
            'allowedTables' => '*',
        ];
        GeneralUtility::makeInstance(IconRegistry::class)->registerIcon(
            'apps-pagetree-event',
            SvgIconProvider::class,
            [
                'source' => 'EXT:pagelist/Resources/Public/Icons/ico_event.svg',
            ]
        );
        ExtensionManagementUtility::addUserTSConfig(
            'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . $pagelistEvent . ')'
        );
    }
    if ($pagelistConiguration['pagelistEnableProducts']) {
        $GLOBALS['PAGES_TYPES'][$pagelistProduct] = [
            'type' => 'web',
            'allowedTables' => '*',
        ];
        GeneralUtility::makeInstance(IconRegistry::class)->registerIcon(
            'apps-pagetree-product',
            SvgIconProvider::class,
            [
                'source' => 'EXT:pagelist/Resources/Public/Icons/ico_product.svg',
            ]
        );
        ExtensionManagementUtility::addUserTSConfig(
            'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . $pagelistProduct . ')'
        );
    }
    if ($pagelistConiguration['pagelistEnableVacancies']) {
        $GLOBALS['PAGES_TYPES'][$pagelistVacancy] = [
            'type' => 'web',
            'allowedTables' => '*',
        ];
        GeneralUtility::makeInstance(IconRegistry::class)->registerIcon(
            'apps-pagetree-vacancy',
            SvgIconProvider::class,
            [
                'source' => 'EXT:pagelist/Resources/Public/Icons/ico_vacancy.svg',
            ]
        );
        ExtensionManagementUtility::addUserTSConfig(
            'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . $pagelistVacancy . ')'
        );
    }
})();
