<?php
defined('TYPO3_MODE') || defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	'pagelist',
	'Configuration/TypoScript/',
	'Pagelist'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	'pagelist',
	'Configuration/TypoScript/EventVcal/',
	'Pagelist - Events vCal'
);
