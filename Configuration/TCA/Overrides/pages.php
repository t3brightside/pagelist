<?php
  defined('TYPO3_MODE') || die('Access denied.');
  $tempColumns = array(
    'tx_pagelist_images' => array(
  		'exclude' => 1,
  		'label' => 'Images',
  		'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
  			'tx_pagelist_images',
  			array(
  				'appearance' => array(
            'headerThumbnail' => array(
            	'width' => '45',
  						'height' => '30',
            ),
            'createNewRelationLinkTitle' => 'LLL:EXT:your_extension/Resources/Private/Language/locallang_db.xlf:tx_yourextension_db_table.add-images'
        	),
  				'foreign_types' => array(
  	        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => array(
  	          'showitem' => '
                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                --palette--;;filePalette
              '
  	        ),
          ),
  			),
  			'jpg,jpeg,png,gif,tiff,bmp'
  		)
  	),
  );

  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns, 1);
  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--palette--;Pagelist;pagelistimages,',
    '',
    'after:media'
  );

  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages_language_overlay', $tempColumns, 1);
  \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
  	'pages_language_overlay',
    '--palette--;Pagelist;pagelistimages,',
    '',
    'after:media'
  );

  $GLOBALS['TCA']['pages']['palettes']['pagelistimages']['showitem'] = '
    tx_pagelist_images,
  ';
