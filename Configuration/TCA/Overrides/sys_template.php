<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

ExtensionManagementUtility::addStaticFile(
	'pagelist',
	'Configuration/TypoScript/',
	'Pagelist'
);

ExtensionManagementUtility::addStaticFile(
	'pagelist',
	'Configuration/TypoScript/EventVcal/',
	'Pagelist - Events vCal'
);
