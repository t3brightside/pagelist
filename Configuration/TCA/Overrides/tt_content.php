<?php
  defined('TYPO3_MODE') || die('Access denied.');

  call_user_func(function () {
/* Add type icons */
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['pagelist_sub'] =  'mimetypes-x-content-pagelist';
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['pagelist_selected'] =  'mimetypes-x-content-pagelist';

/* Define for content element type dropdown */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
      "tt_content",
      "CType",
      [
        "Pagelist: subpages",
        "pagelist_sub",
        "mimetypes-x-content-pagelist"
      ],
      'textmedia',
      'after'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
      "tt_content",
      "CType",
      [
        "Pagelist: selected",
        "pagelist_selected",
        "mimetypes-x-content-pagelist"
      ],
      'pagelist_sub',
      'after'
    );

/* Define columns added to tt_content */
    $tempColumns = array(
      'tx_pagelist_recursive' => array(
        'exclude' => 1,
        'label'   => 'Recursive level',
        'config'  => array(
          'type'     => 'select',
          'renderType' => 'selectSingle',
          'default' => '0',
          'items' => array(
            array('1', '0'),
            array('2', '1'),
            array('3', '2'),
            array('4', '3'),
            array('5', '4'),
            array('6', '5'),
            array('7', '6'),
            array('8', '7'),
            array('9', '8'),
            array('10', '9'),
            array('All sub levels', '999'),
          ),
          'behaviour' => [
            'allowLanguageSynchronization' => true,
          ],
        ),
      ),
      'tx_pagelist_template' => array(
        'exclude' => 1,
        'label'   => 'Template',
        'config'  => array(
          'type'     => 'select',
          'renderType' => 'selectSingle',
          'default' => 0,
          'items'    => array(), /* items set in page TsConfig */
          'behaviour' => [
            'allowLanguageSynchronization' => true,
          ],
        ),
      ),
      'tx_pagelist_orderby' => array(
        'exclude' => 1,
        'label'   => 'Sort by',
        'config'  => array(
          'type'     => 'select',
          'renderType' => 'selectSingle',
          'default' => 'pages.sorting',
          'items' => array(
            array('Page tree (default)', 'pages.sorting'),
            array('Date (now → past)', 'tx_pagelist_datetime DESC'),
            array('Date (past → now)', 'tx_pagelist_datetime ASC'),
            array('Last updated (now → past)', 'lastUpdated DESC'),
            array('Last updated (past → now)', 'lastUpdated ASC'),
            array('Page title (a → z)', 'title ASC'),
            array('Page title (z → a)', 'title DESC'),
          ),
          'behaviour' => [
            'allowLanguageSynchronization' => true,
          ],
        ),
      ),
      'tx_pagelist_startfrom' => [
        'exclude' => 1,
        'label' => 'Start from item',
        'config' => [
          'type' => 'input',
          'eval' => 'num',
          'size' => '1',
          'behaviour' => [
            'allowLanguageSynchronization' => true,
          ],
        ],
      ],
      'tx_pagelist_limit' => [
        'exclude' => 1,
        'label' => 'Items shown',
        'config' => [
          'type' => 'input',
          'eval' => 'num',
          'size' => '1',
          'behaviour' => [
            'allowLanguageSynchronization' => true,
          ],
        ],
      ],
      'tx_pagelist_paginateitems' => [
        'exclude' => 1,
        'label' => 'Pagination items per page',
        'config' => [
          'type' => 'input',
          'eval' => 'num',
          'size' => '1',
          'behaviour' => [
            'allowLanguageSynchronization' => true,
          ],
        ],
      ],
    );

    $tempColumnsChecks = array(
      'tx_pagelist_disableimages' => [
        'exclude' => 1,
        'label' => 'Images',
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
            'behaviour' => [
              'allowLanguageSynchronization' => true,
            ],
        ]
      ],
      'tx_pagelist_disableabstract' => [
        'exclude' => 1,
        'label' => 'Introduction',
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
            'behaviour' => [
              'allowLanguageSynchronization' => true,
            ],
        ]
      ],
      'tx_pagelist_paginate' => [
        'exclude' => 1,
        'label' => 'Pagination',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'items' => [
                [
                    0 => '',
                    1 => '',
                ]
            ],
            'behaviour' => [
              'allowLanguageSynchronization' => true,
            ],
        ]
      ],
    );


    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumnsChecks);

    if(TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('personnel')){
      $tempColumnsAuthors = array(
        'tx_pagelist_authors' => [
            'exclude' => 1,
            'label' => 'Author',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_personnel_domain_model_person',
                'foreign_table_where' => 'AND tx_personnel_domain_model_person.sys_language_uid IN (-1,0)',
                'maxitems' => '1',
                'behaviour' => [
                  'allowLanguageSynchronization' => true,
                ],
            ]
        ],
      );
      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumnsAuthors);
    }

/* Define back end forms for content types */
    $GLOBALS['TCA']['tt_content']['types']['pagelist_sub'] = array(
      'showitem' => '
	      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
          pages;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:pages.ALT.menu_formlabel,
          tx_pagelist_recursive,
          --palette--;;pagelistSettingsSub,
          selected_categories;Category Filter,
          tx_pagelist_authors;Author Filter,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.accessibility,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.menu_accessibility;menu_accessibility,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
          --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
          --palette--;;hidden,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
          categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
          rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        --div--;LLL:EXT:gridelements/Resources/Private/Language/locallang_db.xlf:gridElements, tx_gridelements_container, tx_gridelements_columns
      '
    );
    $GLOBALS['TCA']['tt_content']['palettes']['pagelistSettingsSub']['showitem'] = '
        tx_pagelist_template,
        tx_pagelist_orderby,
        tx_pagelist_startfrom,
        tx_pagelist_limit,
        --linebreak--,
        tx_pagelist_disableimages,
        tx_pagelist_disableabstract,
        tx_pagelist_paginate,
        tx_pagelist_paginateitems,
  	';

    $GLOBALS['TCA']['tt_content']['types']['pagelist_selected'] = array(
      'showitem' => '
	      --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
          pages;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:pages.ALT.menu_formlabel,
          --palette--;;pagelistSettingsSelected,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.accessibility,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.menu_accessibility;menu_accessibility,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
          --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
          --palette--;;hidden,
          --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
          categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
          rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
        --div--;LLL:EXT:gridelements/Resources/Private/Language/locallang_db.xlf:gridElements, tx_gridelements_container, tx_gridelements_columns
      '
    );
    $GLOBALS['TCA']['tt_content']['palettes']['pagelistSettingsSelected']['showitem'] = '
  		tx_pagelist_template,
    //  tx_pagelist_orderby,
  	//  tx_pagelist_startfrom,
  	//	tx_pagelist_limit,
      tx_pagelist_disableimages,
      tx_pagelist_disableabstract,
      tx_pagelist_paginate,
      tx_pagelist_paginateitems,
  	';
});
