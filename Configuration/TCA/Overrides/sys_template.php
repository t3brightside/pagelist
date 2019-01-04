<?php
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	'pagelist',
	'Configuration/TypoScript/',
	'Pagelist'
);

if (TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('personnel')){
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants('
		pagelist.personnelIsLoaded = 1
	');
} else {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants('
		pagelist.personnelIsLoaded = 0
	');
}
