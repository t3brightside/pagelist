<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

(function () {    
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
        'pagelist',
        'Pagelist',
        [
            'Pagelist' => 'pagelist',
        ],
        [], 
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT 
    );
})();
