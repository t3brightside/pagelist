<?php
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Information\Typo3Version;

defined('TYPO3') || die('Access denied.');

(function () {
    $typo3Version = GeneralUtility::makeInstance(Typo3Version::class);
    if ($typo3Version->getMajorVersion() < 13) {
        ExtensionManagementUtility::addUserTSConfig(
            '@import "EXT:pagelist/Configuration/user.tsconfig"',
        );
    }
})();