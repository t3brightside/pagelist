<?php
  defined('TYPO3_MODE') || die('Access denied.');

  call_user_func(
    function () {
      $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
      );
      $pagelistConiguration = $extensionConfiguration->get('pagelist');

      $pagelistArticle = 136;
      $pagelistEvent = 137;
      $pagelistProduct = 138;

      $tempColumns = array(
    /*    'slug' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug',
            'displayCond' => 'USER:' . \TYPO3\CMS\Core\Compatibility\PseudoSiteTcaDisplayCondition::class . '->isInPseudoSite:pages:false',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['title', 'tx_pagelist_datetime'],
                    'fieldSeparator' => '-',
                    'prefixParentPageSlug' => true
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
                'default' => ''
            ]
        ],
        */
        'tx_pagelist_images' => [
          'exclude' => 1,
          'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.images',
          'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
            'tx_pagelist_images',
            [
              'behaviour' => [
                'allowLanguageSynchronization' => true,
              ],
              // custom configuration for displaying fields in the overlay/reference table
              // to use the image overlay palette instead of the basic overlay palette
              'overrideChildTca' => [
                'types' => [
                  '0' => [
                    'showitem' => '
                      --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                      --palette--;;filePalette'
                  ],
                  \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                    'showitem' => '
                      --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                      --palette--;;filePalette'
                  ],
                ],
              ],
            ],
            $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
          ),
        ],
        'tx_pagelist_eventlocation' => [
          'exclude' => 1,
          'label' => 'Location',
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
            'label' => 'Location Link',
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
          'label' => 'Date & Time',
          'config' => [
            'type' => 'input',
            'renderType' => 'inputDateTime',
            'size' => '12',
            'eval' => 'datetime,int',
            'behaviour' => [
              'allowLanguageSynchronization' => true,
            ],
          ]
        ],
        'tx_pagelist_starttime' => [
          'exclude' => 1,
          'label' => 'Enable Start Time',
          'config' => [
              'type' => 'check',
              'renderType' => 'checkboxToggle',
              'items' => [
                  [
                      0 => '',
                      1 => '',
                      'behaviour' => [
                        'allowLanguageSynchronization' => true,
                      ],
                  ]
              ],
          ]
        ],
        'tx_pagelist_eventfinish' => [
          'exclude' => 1,
          'label' => 'Event End',
          'config' => [
            'type' => 'input',
            'renderType' => 'inputDateTime',
            'size' => '12',
            'eval' => 'datetime,int',
            'behaviour' => [
              'allowLanguageSynchronization' => true,
            ],
          ]
        ],
        'tx_pagelist_endtime' => [
          'exclude' => 1,
          'label' => 'Enable End Time',
          'config' => [
              'type' => 'check',
              'renderType' => 'checkboxToggle',
              'items' => [
                  [
                      0 => '',
                      1 => '',
                      'behaviour' => [
                        'allowLanguageSynchronization' => true,
                      ],
                  ]
              ],
          ]
        ],
        'tx_pagelist_productprice' => [
          'exclude' => 1,
          'label' => 'Price',
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
          'label' => 'Page enabled in lists',
          'config' => [
              'type' => 'check',
              'renderType' => 'checkboxToggle',
              'items' => [
                  [
                      0 => '',
                      1 => '',
                      'invertStateDisplay' => true
                  ]
              ],
          ]
        ],
      );

      if(TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('personnel')){
        $tempColumnsAuthors = array(
          'tx_pagelist_authors' => [
            'exclude' => 1,
            'label' => 'Authors',
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
      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempHidePageColumn, 1);

      if(TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('personnel')){
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tempColumnsAuthors, 1);
      }

// Add to all page types
      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '--palette--;Pagelist image;pagelistimages',
        '1',
        'before:media'
      );
      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        'tx_pagelist_notinlist',
        '1',
        'after:nav_hide'
      );

      $GLOBALS['TCA']['pages']['types'][$pagelistArticle]['showitem'] = $GLOBALS['TCA']['pages']['types'][1]['showitem'];
      $GLOBALS['TCA']['pages']['types'][$pagelistArticle]['showitem'] = str_replace(
        ';title,',
        ';,--palette--;Article;pagelistarticlegeneral,',
        $GLOBALS['TCA']['pages']['types'][$pagelistArticle]['showitem']
      );
      $GLOBALS['TCA']['pages']['types'][$pagelistArticle]['showitem'] = str_replace(
        ';abstract,',
        '--palette--;;,',
        $GLOBALS['TCA']['pages']['types'][$pagelistArticle]['showitem']
      );
      $GLOBALS['TCA']['pages']['types'][$pagelistArticle]['showitem'] = str_replace(
        ';editorial,',
        '--palette--;;,',
        $GLOBALS['TCA']['pages']['types'][$pagelistArticle]['showitem']
      );
      $GLOBALS['TCA']['pages']['types'][$pagelistArticle]['showitem'] = str_replace(
        'pagelistimages,',
        '',
        $GLOBALS['TCA']['pages']['types'][$pagelistArticle]['showitem']
      );

      if (TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('personnel') AND $pagelistConiguration['pagelistEnableArticlePersonnel']) {
        $GLOBALS['TCA']['pages']['palettes']['pagelistarticlegeneral']['showitem'] = '
          title,
          --linebreak--,slug,
          --linebreak--,tx_pagelist_datetime,lastUpdated,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_authors,
          --linebreak--,tx_pagelist_images,
        ';
      } else {
        $GLOBALS['TCA']['pages']['palettes']['pagelistarticlegeneral']['showitem'] = '
          title,
          --linebreak--,slug,
          --linebreak--,tx_pagelist_datetime,lastUpdated,
          --linebreak--,abstract,
          --linebreak--,author,author_email,
          --linebreak--,tx_pagelist_images,
        ';
      }
      $GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem'] = $GLOBALS['TCA']['pages']['types'][1]['showitem'];
      // Replace title area and add categories
      $GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem'] = str_replace(
        ';title,',
        ';,--palette--;Product;pagelistproductgeneral,',
        $GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem']
      );
      $GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem'] = str_replace(
        ';abstract,',
        '--palette--;;,',
        $GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem']
      );
      $GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem'] = str_replace(
        ';editorial,',
        '--palette--;;,',
        $GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem']
      );
      $GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem'] = str_replace(
        'pagelistimages,',
        '',
        $GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem']
      );

      if (TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('personnel') AND $pagelistConiguration['pagelistEnableProductPersonnel']) {
        $GLOBALS['TCA']['pages']['palettes']['pagelistproductgeneral']['showitem'] = '
          title,
          --linebreak--,slug,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_productprice,
          --linebreak--,tx_pagelist_authors,
          --linebreak--,tx_pagelist_images,
          --linebreak--,tx_pagelist_datetime,lastUpdated,
        ';
      } else {
        $GLOBALS['TCA']['pages']['palettes']['pagelistproductgeneral']['showitem'] = '
          title,
          --linebreak--,slug,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_productprice,
          --linebreak--,tx_pagelist_images,
          --linebreak--,tx_pagelist_datetime,lastUpdated,author,author_email,
        ';
      }

// Event page type
      $GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem'] = $GLOBALS['TCA']['pages']['types'][1]['showitem'];
      // Replace title area and add categories
      $GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem'] = str_replace(
        ';title,',
        ';,--palette--;Event;pagelisteventgeneral,',
        $GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem']
      );
      $GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem'] = str_replace(
        ';abstract,',
        '--palette--;;,',
        $GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem']
      );
      $GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem'] = str_replace(
        ';editorial,',
        '--palette--;;,',
        $GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem']
      );
      $GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem'] = str_replace(
        'pagelistimages,',
        '',
        $GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem']
      );

      if (TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('personnel') AND $pagelistConiguration['pagelistEnableEventPersonnel']) {
        $GLOBALS['TCA']['pages']['palettes']['pagelisteventgeneral']['showitem'] = '
          title,
          --linebreak--,slug,
          --linebreak--,tx_pagelist_datetime,tx_pagelist_starttime,tx_pagelist_eventfinish,tx_pagelist_endtime,lastUpdated,
          --linebreak--,tx_pagelist_eventlocation,
          --linebreak--,tx_pagelist_eventlocationlink,
          --linebreak--,abstract,
          --linebreak--,tx_pagelist_authors,
          --linebreak--,tx_pagelist_images,
        ';
      } else {
        $GLOBALS['TCA']['pages']['palettes']['pagelisteventgeneral']['showitem'] = '
          title,
          --linebreak--,slug,
          --linebreak--,tx_pagelist_datetime,tx_pagelist_starttime,tx_pagelist_eventfinish,tx_pagelist_endtime,lastUpdated,
          --linebreak--,tx_pagelist_eventlocation,
          --linebreak--,tx_pagelist_eventlocationlink,
          --linebreak--,abstract,
          --linebreak--,author,author_email,
          --linebreak--,tx_pagelist_images,
        ';
      }

      $GLOBALS['TCA']['pages']['palettes']['pagelistimages']['showitem'] = '
        tx_pagelist_images,
      ';
    }
  );
