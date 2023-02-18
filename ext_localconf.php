<?php
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

(function () {
    $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
    // Only include page.tsconfig if TYPO3 version is below 12 so that it is not imported twice.
    if ($versionInformation->getMajorVersion() < 12) {
    ExtensionManagementUtility::addPageTSConfig('
        @import "EXT:pagelist/Configuration/page.tsconfig"
    ');
    }

    $iconRegistry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'mimetypes-x-content-pagelist',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:pagelist/Resources/Public/Icons/mimetypes-x-content-pagelist.svg']
    );

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
})();
