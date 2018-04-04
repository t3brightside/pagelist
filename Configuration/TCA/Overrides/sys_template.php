<?php
defined('TYPO3_MODE') || die('Access denied.');

// Add an entry in the static template list found in sys_templates for static TS

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
	'pagelist',
	'Configuration/TypoScript/',
	'Page List'
);
