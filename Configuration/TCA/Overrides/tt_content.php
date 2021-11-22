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
        'label'   => 'Recursive Level',
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
        'label' => 'Start from Page',
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
        'label' => 'Pages Shown',
        'config' => [
          'type' => 'input',
          'eval' => 'num',
          'size' => '1',
          'behaviour' => [
            'allowLanguageSynchronization' => true,
          ],
        ],
      ],
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
    );


    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);

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

/* Define content type for Pagelist: subpages */
    $GLOBALS['TCA']['tt_content']['types']['pagelist_sub']['showitem'] = $GLOBALS['TCA']['tt_content']['types']['header']['showitem'];
    $GLOBALS['TCA']['tt_content']['types']['pagelist_sub']['showitem'] = str_replace(
        ';headers,',
        ';headers,
        --palette--;Pages;pagelist_sub_data,
        --palette--;Layout;pagelist_layout,
        --palette--;Filter;pagelist_filtering,
        --palette--;Pagination;paginatedprocessors,',
        $GLOBALS['TCA']['tt_content']['types']['pagelist_sub']['showitem']
    );

/* Define content type for Pagelist: selected */
    $GLOBALS['TCA']['tt_content']['types']['pagelist_selected']['showitem'] = $GLOBALS['TCA']['tt_content']['types']['header']['showitem'];
    $GLOBALS['TCA']['tt_content']['types']['pagelist_selected']['showitem'] = str_replace(
        ';headers,',
        ';headers,
        pages;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:pages.ALT.menu_formlabel,
        --palette--;Layout;pagelist_selected_layout,
        --palette--;Pagination;paginatedprocessors,',
        $GLOBALS['TCA']['tt_content']['types']['pagelist_selected']['showitem']
    );

/* Define palettes for content types */
    $GLOBALS['TCA']['tt_content']['palettes']['pagelist_sub_data']['showitem'] = '
        pages;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:pages.ALT.menu_formlabel,
        --linebreak--,
        tx_pagelist_recursive,
        tx_pagelist_orderby,
        tx_pagelist_startfrom,
        tx_pagelist_limit,
  	';

    $GLOBALS['TCA']['tt_content']['palettes']['pagelist_layout']['showitem'] = '
        tx_pagelist_template,
        tx_pagelist_disableimages,
        tx_pagelist_disableabstract,
  	';

    $GLOBALS['TCA']['tt_content']['palettes']['pagelist_filtering']['showitem'] = '
      selected_categories;by Category,
      tx_pagelist_authors;by Author,
  	';

    $GLOBALS['TCA']['tt_content']['palettes']['pagelist_selected_layout']['showitem'] = '
  		tx_pagelist_template,
      tx_pagelist_disableimages,
      tx_pagelist_disableabstract,
  	';
});
