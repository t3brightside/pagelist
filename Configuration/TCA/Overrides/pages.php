<?php
  defined('TYPO3_MODE') || die('Access denied.');
  call_user_func(
    function () {
      $pagelistConiguration = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pagelist'];
      if (!is_array($pagelistConiguration)) {
        $pagelistConiguration = unserialize($pagelistConiguration);
      }

      $pagelistArticle = 136;
      $pagelistEvent = 137;
      $pagelistProduct = 138;

      $tempColumns = array(
        'tx_pagelist_images' => array(
          'exclude' => 1,
          'label' => 'File:',
          'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            'tx_pagelist_images',
            array(
              'appearance' => array(
                'headerThumbnail' => array(
                  'width' => '45',
                  'height' => '30',
                ),
                'createNewRelationLinkTitle' => 'Create new relation'
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
        'tx_pagelist_eventlocation' => [
            'exclude' => 1,
            'label' => 'Location text:',
            'config' => [
                'type' => 'input',
                'size' => '200',
                'eval' => 'text',
            ]
        ],
        'tx_pagelist_eventlocationlink' => [
            'exclude' => 1,
            'label' => 'Location link:',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ]
        ],
        'tx_pagelist_datetime' => [
            'exclude' => 1,
            'label' => 'Date & time:',
            'config' => [
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'datetime,int',
                'checkbox' => '0',
            ]
        ],
        'tx_pagelist_eventstart' => [
            'exclude' => 1,
            'label' => 'Event start:',
            'config' => [
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'datetime,int',
                'checkbox' => '0',
            ]
        ],
        'tx_pagelist_eventfinish' => [
            'exclude' => 1,
            'label' => 'Event finish:',
            'config' => [
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'datetime,int',
                'checkbox' => '0',
            ]
        ],
      );
      if ($pagelistConiguration['pagelistEnableProducts']) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
          'pages',
          'doktype',
          [
            'Product',
            $pagelistProduct,
            'apps-pagetree-product'
          ],
          '1',
          'after'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
          'pages_language_overlay',
          'doktype',
          [
            'Product',
            $pagelistProduct,
            'apps-pagetree-product'
          ],
          '1',
          'after'
        );
      }
      if ($pagelistConiguration['pagelistEnableEvents']) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
          'pages',
          'doktype',
          [
            'Event',
            $pagelistEvent,
            'apps-pagetree-event'
          ],
          '1',
          'after'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
          'pages_language_overlay',
          'doktype',
          [
            'Event',
            $pagelistEvent,
            'apps-pagetree-event'
          ],
          '1',
          'after'
        );
      }
      if ($pagelistConiguration['pagelistEnableArticles']) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
          'pages',
          'doktype',
          [
            'Article',
            $pagelistArticle,
            'apps-pagetree-article'
          ],
          '1',
          'after'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
          'pages_language_overlay',
          'doktype',
          [
            'Article',
            $pagelistArticle,
            'apps-pagetree-article'
          ],
          '1',
          'after'
        );
      }

      \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
        $GLOBALS['TCA']['pages'],
        [
          'ctrl' => [
            'typeicon_classes' => [
              $pagelistArticle => 'apps-pagetree-article',
              $pagelistEvent => 'apps-pagetree-event',
              $pagelistProduct => 'apps-pagetree-product',
            ],
          ],
        ]
      );


      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns, 1);

// Add to all page types
      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '--palette--;Page list image;pagelistimages',
        '1',
        'before:media'
      );

// Define Article page type
      $GLOBALS['TCA']['pages']['types'][$pagelistArticle] = array(
        'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;standard,
                    --palette--;Article;pagelistarticlegeneral,
                    --palette--;LLL:EXT:realurl/Resources/Private/Language/locallang_db.xlf:pages.palette_title;tx_realurl,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
                    --palette--;;metatags,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.appearance,
                    --palette--;;layout,
                    --palette--;;replace,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.behaviour,
                    --palette--;;links,
                    --palette--;;caching,
                    --palette--;;miscellaneous,
                    --palette--;;module,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.resources,
                    --palette--;;media,
                    --palette--;;config,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
                    --palette--;;visibility,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            '
    	);
      $GLOBALS['TCA']['pages']['palettes']['pagelistarticlegeneral']['showitem'] = '
        tx_pagelist_datetime,lastUpdated,
        --linebreak--,title,
        --linebreak--,subtitle,
        --linebreak--,abstract,
        --linebreak--,tx_pagelist_images,
        --linebreak--,author,
      ';
// Define Event page type
      $GLOBALS['TCA']['pages']['types'][$pagelistEvent] = array(
        'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;standard,
                    --palette--;Event;pagelisteventgeneral,
                    --palette--;LLL:EXT:realurl/Resources/Private/Language/locallang_db.xlf:pages.palette_title;tx_realurl,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
                    --palette--;;metatags,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.appearance,
                    --palette--;;layout,
                    --palette--;;replace,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.behaviour,
                    --palette--;;links,
                    --palette--;;caching,
                    --palette--;;miscellaneous,
                    --palette--;;module,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.resources,
                    --palette--;;media,
                    --palette--;;config,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
                    --palette--;;visibility,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            '
    	);
      $GLOBALS['TCA']['pages']['palettes']['pagelisteventgeneral']['showitem'] = '
        tx_pagelist_eventstart,tx_pagelist_eventfinish,tx_pagelist_datetime,lastUpdated,
        --linebreak--,title,
        --linebreak--,tx_pagelist_eventlocation,
        --linebreak--,tx_pagelist_eventlocationlink,
        --linebreak--,abstract,
        --linebreak--,tx_pagelist_images,
        --linebreak--,author,
      ';

      $GLOBALS['TCA']['pages']['palettes']['pagelistimages']['showitem'] = '
        tx_pagelist_images,
      ';



      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages_language_overlay', $tempColumns, 1);
      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
      	'pages_language_overlay',
        '--palette--;Pagelist;pagelistimages,',
        '',
        'after:media'
      );
      $GLOBALS['TCA']['pages_language_overlay']['palettes']['pagelistimages']['showitem'] = '
        tx_pagelist_images,
      ';
    }
  );
