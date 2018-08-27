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
              'behaviour' => [
                'allowLanguageSynchronization' => true,
              ],
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
            'jpg,jpeg,png,gif,tiff,bmp,svg'
          )
        ),
        'tx_pagelist_eventlocation' => [
            'exclude' => 1,
            'label' => 'Location Text:',
            'config' => [
                'type' => 'input',
                'size' => '200',
                'eval' => 'text,trim',
                'behaviour' => [
                  'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'tx_pagelist_eventlocationlink' => [
            'exclude' => 1,
            'label' => 'Location Link:',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'renderType' => 'inputLink',
                'behaviour' => [
                  'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'tx_pagelist_datetime' => [
            'exclude' => 1,
            'label' => 'Date & Time:',
            'config' => [
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'datetime,int',
                'checkbox' => '0',
                'behaviour' => [
                  'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'tx_pagelist_eventfinish' => [
            'exclude' => 1,
            'label' => 'Event End:',
            'config' => [
                'type' => 'input',
                'size' => '12',
                'max' => '20',
                'eval' => 'datetime,int',
                'checkbox' => '0',
                'behaviour' => [
                  'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'tx_pagelist_productprice' => [
            'exclude' => 1,
            'label' => 'Price:',
            'config' => [
                'type' => 'input',
                'size' => '10',
                'eval' => 'trim',
                'behaviour' => [
                  'allowLanguageSynchronization' => true,
                ],
            ]
        ],
        'tx_pagelist_notinlist' => [
           'exclude' => 1,
           'label' => 'In Page Lists',
           'config' => [
              'type' => 'check',
              'renderType' => 'check',
              'items' => [
                ['Hide', '1'],
              ],
           ]
        ],

      );
      if(TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('personnel')){
        $tempColumnsAuthors = array(
          'tx_pagelist_authors' => [
              'exclude' => 1,
              'label' => 'Author(s)',
              'config' => [
                  'type' => 'select',
                  'renderType' => 'selectMultipleSideBySide',
                  'enableMultiSelectFilterTextfield' => true,
                  'foreign_table' => 'tx_personnel_domain_model_person',
                  'foreign_table_where' => 'AND tx_personnel_domain_model_person.sys_language_uid IN (-1,0)',
                  'size' => '3',
                  'behaviour' => [
                    'allowLanguageSynchronization' => true,
                  ],
              ]
          ],
        );
      }

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
      if(TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('personnel')){
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumnsAuthors, 1);
      }
// Add to all page types
      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '--palette--;Page list image;pagelistimages',
        '1',
        'before:media'
      );
      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        'tx_pagelist_notinlist',
        '1',
        'after:nav_hide'
      );

// Define Article page type
      $GLOBALS['TCA']['pages']['types'][$pagelistArticle] = array(
        'showitem' => '
          --div--;Article,
            --palette--;Page;standard,
            --palette--;Article;pagelistarticlegeneral,
            --palette--;LLL:EXT:realurl/Resources/Private/Language/locallang_db.xlf:pages.palette_title;tx_realurl,
          --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
            --palette--;Meta Tags;metatags,
            --palette--;;pagelistauthor,
            --palette--;Meta Plus;metaplus,
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
            --palette--;;visibility,tx_pagelist_notinlist,
            --palette--;;access,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '
    	);
      if ($pagelistConiguration['pagelistEnableArticlePersonnel']) {
        $GLOBALS['TCA']['pages']['palettes']['pagelistarticlegeneral']['showitem'] = '
          tx_pagelist_datetime,lastUpdated,
          --linebreak--,title,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_authors,
          --linebreak--,tx_pagelist_images,
        ';
      } else {
        $GLOBALS['TCA']['pages']['palettes']['pagelistarticlegeneral']['showitem'] = '
          tx_pagelist_datetime,lastUpdated,
          --linebreak--,title,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_images,
        ';
      }

      $GLOBALS['TCA']['pages']['types'][$pagelistProduct] = array(
        'showitem' => '
          --div--;Article,
            --palette--;Page;standard,
            --palette--;Product;pagelistproductgeneral,
            --palette--;LLL:EXT:realurl/Resources/Private/Language/locallang_db.xlf:pages.palette_title;tx_realurl,
          --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
            --palette--;Meta Tags;metatags,
            --palette--;;pagelistauthor,
            --palette--;Meta Plus;metaplus,
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
            --palette--;;visibility,tx_pagelist_notinlist,
            --palette--;;access,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '
    	);
      if ($pagelistConiguration['pagelistEnableProductPersonnel']) {
        $GLOBALS['TCA']['pages']['palettes']['pagelistproductgeneral']['showitem'] = '
          title,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_productprice,
          --linebreak--,tx_pagelist_authors,
          --linebreak--,tx_pagelist_images,
          --linebreak--,tx_pagelist_datetime,lastUpdated,
        ';
      } else {
        $GLOBALS['TCA']['pages']['palettes']['pagelistproductgeneral']['showitem'] = '
          title,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_productprice,
          --linebreak--,tx_pagelist_images,
          --linebreak,tx_pagelist_datetime,lastUpdated,
        ';
      }

// Define Event page type
      $GLOBALS['TCA']['pages']['types'][$pagelistEvent] = array(
        'showitem' => '
          --div--;Event,
            --palette--;Page;standard,
            --palette--;Event;pagelisteventgeneral,
            --palette--;LLL:EXT:realurl/Resources/Private/Language/locallang_db.xlf:pages.palette_title;tx_realurl,
          --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
            --palette--;Meta Tags;metatags,
            --palette--;;pagelistauthor,
            --palette--;Meta Plus;metaplus,
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
            --palette--;;visibility,tx_pagelist_notinlist,
            --palette--;;access,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '
    	);
      if ($pagelistConiguration['pagelistEnableEventPersonnel']) {
        $GLOBALS['TCA']['pages']['palettes']['pagelisteventgeneral']['showitem'] = '
          tx_pagelist_datetime,tx_pagelist_eventfinish,lastUpdated,
          --linebreak--,title,
          --linebreak--,tx_pagelist_eventlocation,
          --linebreak--,tx_pagelist_eventlocationlink,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_authors,
          --linebreak--,tx_pagelist_images,
        ';
      } else {
        $GLOBALS['TCA']['pages']['palettes']['pagelisteventgeneral']['showitem'] = '
          tx_pagelist_datetime,tx_pagelist_eventfinish,lastUpdated,
          --linebreak--,title,
          --linebreak--,tx_pagelist_eventlocation,
          --linebreak--,tx_pagelist_eventlocationlink,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_images,
        ';
      }
      $GLOBALS['TCA']['pages']['palettes']['pagelistauthor']['showitem'] = '
        author,
        author_email,
      ';

      $GLOBALS['TCA']['pages']['palettes']['pagelistimages']['showitem'] = '
        tx_pagelist_images,
      ';

      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages_language_overlay', $tempColumns, 1);
      if(TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('personnel')){
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages_language_overlay', $tempColumnsAuthors, 1);
      }
      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
      	'pages_language_overlay',
        '--palette--;Pagelist;pagelistimages,',
        '',
        'after:media'
      );
      $GLOBALS['TCA']['pages_language_overlay']['palettes']['pagelistimages']['showitem'] = '
        tx_pagelist_images,
      ';

// Define Article page type
      $GLOBALS['TCA']['pages_language_overlay']['types'][$pagelistArticle] = array(
        'showitem' => '
          --div--;Article,
            --palette--;Page;standard,
            --palette--;Article;pagelistarticlegeneral,
            --palette--;LLL:EXT:realurl/Resources/Private/Language/locallang_db.xlf:pages.palette_title;tx_realurl,
          --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
            --palette--;Meta Tags;metatags,
            --palette--;;pagelistauthor,
            --palette--;Meta Plus;metaplus,
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
            --palette--;;visibility,hidden,
            --palette--;;access,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '
    	);
      if ($pagelistConiguration['pagelistEnableArticlePersonnel']) {
        $GLOBALS['TCA']['pages_language_overlay']['palettes']['pagelistarticlegeneral']['showitem'] = '
          tx_pagelist_datetime,lastUpdated,
          --linebreak--,title,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_authors,
          --linebreak--,tx_pagelist_images,
        ';
      } else {
        $GLOBALS['TCA']['pages_language_overlay']['palettes']['pagelistarticlegeneral']['showitem'] = '
          tx_pagelist_datetime,lastUpdated,
          --linebreak--,title,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_images,
        ';
      }

      $GLOBALS['TCA']['pages_language_overlay']['types'][$pagelistProduct] = array(
        'showitem' => '
          --div--;Article,
            --palette--;Page;standard,
            --palette--;Product;pagelistproductgeneral,
            --palette--;LLL:EXT:realurl/Resources/Private/Language/locallang_db.xlf:pages.palette_title;tx_realurl,
          --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
            --palette--;Meta Tags;metatags,
            --palette--;;pagelistauthor,
            --palette--;Meta Plus;metaplus,
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
            --palette--;;visibility,hidden,
            --palette--;;access,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '
    	);
      if ($pagelistConiguration['pagelistEnableProductPersonnel']) {
        $GLOBALS['TCA']['pages_language_overlay']['palettes']['pagelistproductgeneral']['showitem'] = '
          title,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_productprice,
          --linebreak--,tx_pagelist_authors,
          --linebreak--,tx_pagelist_images,
          --linebreak--,tx_pagelist_datetime,lastUpdated,
        ';
      } else {
        $GLOBALS['TCA']['pages_language_overlay']['palettes']['pagelistproductgeneral']['showitem'] = '
          title,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_productprice,
          --linebreak--,tx_pagelist_images,
          --linebreak--,tx_pagelist_datetime,lastUpdated,
        ';
      }

// Define Event page type
      $GLOBALS['TCA']['pages_language_overlay']['types'][$pagelistEvent] = array(
        'showitem' => '
          --div--;Event,
            --palette--;Page;standard,
            --palette--;Event;pagelisteventgeneral,
            --palette--;LLL:EXT:realurl/Resources/Private/Language/locallang_db.xlf:pages.palette_title;tx_realurl,
          --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.metadata,
            --palette--;Meta Tags;metatags,
            --palette--;;pagelistauthor,
            --palette--;Meta Plus;metaplus,
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
            --palette--;;visibility,hidden,
            --palette--;;access,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
          --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        '
    	);
      if ($pagelistConiguration['pagelistEnableEventPersonnel']) {
        $GLOBALS['TCA']['pages_language_overlay']['palettes']['pagelisteventgeneral']['showitem'] = '
          tx_pagelist_datetime,tx_pagelist_eventfinish,lastUpdated,
          --linebreak--,title,
          --linebreak--,tx_pagelist_eventlocation,
          --linebreak--,tx_pagelist_eventlocationlink,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_authors,
          --linebreak--,tx_pagelist_images,
        ';
      } else {
        $GLOBALS['TCA']['pages_language_overlay']['palettes']['pagelisteventgeneral']['showitem'] = '
          tx_pagelist_datetime,tx_pagelist_eventfinish,lastUpdated,
          --linebreak--,title,
          --linebreak--,tx_pagelist_eventlocation,
          --linebreak--,tx_pagelist_eventlocationlink,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_images,
        ';
      }
      $GLOBALS['TCA']['pages_language_overlay']['palettes']['pagelistauthor']['showitem'] = '
        author,
        author_email,
      ';

      $GLOBALS['TCA']['pages_language_overlay']['palettes']['pagelistimages']['showitem'] = '
        tx_pagelist_images,
      ';
    }
  );
