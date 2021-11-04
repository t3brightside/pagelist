<?php
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('@import "EXT:pagelist/Configuration/PageTS/setup.typoscript"');

	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
	$iconRegistry->registerIcon(
		'mimetypes-x-content-pagelist',
		\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
		['source' => 'EXT:pagelist/Resources/Public/Images/Icons/mimetypes-x-content-pagelist.svg']
	);

	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['pagelist'] = \Brightside\Pagelist\Hooks\PageLayoutView\PagelistContentElementPreviewRenderer::class;

	if (TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('personnel')){
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants('
			pagelist.personnelIsLoaded = 1
		');
	} else {
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants('
			pagelist.personnelIsLoaded = 0
		');
	}

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	    'Pagelist',
	    'Pagelist',
	    [
	        \Brightside\Pagelist\DataProcessing\PagelistDatabaseQueryProcessor::class => 'pagelist',
					'Pagelist' => 'pagelist',
	    ],
			[

			],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
	);
