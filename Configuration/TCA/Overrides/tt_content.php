<?php
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use Brightside\Pagelist\Preview\PagelistPreviewRenderer;

defined('TYPO3') || die('Access denied.');

// Content type icons

$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['pagelist_sub'] = 'mimetypes-x-content-pagelist';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['pagelist_selected'] = 'mimetypes-x-content-pagelist';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['pagelist_articles_sub'] = 'mimetypes-x-content-pagelist';
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['pagelist_events_sub'] = 'mimetypes-x-content-pagelist';

// Get extension configuration
$extensionConfiguration = GeneralUtility::makeInstance(
    ExtensionConfiguration::class
);
$extensionConfiguration = $extensionConfiguration->get('pagelist');

// Content element type dropdown
ExtensionManagementUtility::addTcaSelectItem(
    "tt_content",
    "CType",
    [
        'label' => 'Pagelist: subpages',
        'value' => 'pagelist_sub',
        'icon' => 'mimetypes-x-content-pagelist',
        'group' => 'default',
        'description' => 'Shows subpages list of selected pages.',
    ],
    'textmedia',
    'after'
);

ExtensionManagementUtility::addTcaSelectItem(
    "tt_content",
    "CType",
    [
        'label' => 'Pagelist: selected pages',
        'value' => 'pagelist_selected',
        'icon' => 'mimetypes-x-content-pagelist',
        'group' => 'default',
        'description' => 'Shows list of selected pages.',
    ],
    'pagelist_sub',
    'after'
);

ExtensionManagementUtility::addTcaSelectItem(
    "tt_content",
    "CType",
    [
        'label' => 'Pagelist: articles of subpages',
        'value' => 'pagelist_articles_sub',
        'icon' => 'mimetypes-x-content-pagelist',
        'group' => 'default',
        'description' => 'Show news article subpages from selected pages.',
    ],
    'pagelist_sub',
    'after'
);

ExtensionManagementUtility::addTcaSelectItem(
    "tt_content",
    "CType",
    [
        'label' => 'Pagelist: events of subpages',
        'value' => 'pagelist_events_sub',
        'icon' => 'mimetypes-x-content-pagelist',
        'group' => 'default',
        'description' => 'Shows event subpages of selected pages.',
    ],
    'pagelist_sub',
    'after'
);

$tempColumns = array(
    'tx_pagelist_recursive' => [
        'exclude' => 1,
        'label'   => 'Recursive Level',
        'config'  => [
            'type'     => 'select',
            'renderType' => 'selectSingle',
            'default' => '0',
            'items' => [
                ['0', '0'],
                ['1', '1'],
                ['2', '2'],
                ['3', '3'],
                ['4', '4'],
                ['5', '5'],
                ['6', '6'],
                ['7', '7'],
                ['8', '8'],
                ['9', '9'],
                ['All sub levels', '999'],
            ],
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ],
    ],
    'tx_pagelist_template' => [
        'exclude' => 1,
        'label'   => 'Layout',
        'config'  => [
            'type'     => 'select',
            'renderType' => 'selectSingle',
            'default' => 0,
            'items'    => array(), /* items set in page TsConfig */
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ],
    ],
    'tx_pagelist_orderby' => [
        'exclude' => 1,
        'label'   => 'Order by',
        'config'  => [
            'type'     => 'select',
            'renderType' => 'selectSingle',
            'default' => 'pages.sorting',
            'items' => [
                ['Page tree', 'pages.sorting'],
                ['Date (now → past)', 'tx_pagelist_datetime DESC'],
                ['Date (past → now)', 'tx_pagelist_datetime ASC'],
                ['Last updated (now → past)', 'lastUpdated DESC'],
                ['Last updated (past → now)', 'lastUpdated ASC'],
                ['Event start (now → future)', 'tx_pagelist_eventstart ASC'],
                ['Event start (future → now)', 'tx_pagelist_eventstart DESC'],
                ['Page title (a → z)', 'title ASC'],
                ['Page title (z → a)', 'title DESC'],
            ],
            'default' => 'pages.sorting',
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ],
    ],
    'tx_pagelist_startfrom' => [
        'exclude' => 1,
        'label' => 'Start from page',
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
        'label' => 'Limit showing to',
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
    'tx_pagelist_cropratio' => [
        'exclude' => 1,
        'label'   => 'Image Crop',
        'config'  => [
            'type'     => 'select',
            'renderType' => 'selectSingle',
            'default' => '0',
            'items'    => array(), /* items set in page TsConfig */
        ],
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
    'tx_pagelist_titlewrap' => [
        'exclude' => 1,
        'label'   => 'Title wrap',
        'config'  => [
            'type'     => 'select',
            'renderType' => 'selectSingle',
            'default' => 0,
            'items'    => array(), /* items set in page TsConfig */
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ],
    ],
);


ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);

// Author field if Personell is installed
if(ExtensionManagementUtility::isLoaded('personnel')){
    $tempColumnsAuthors = array(
        'tx_pagelist_authors' => [
            'exclude' => 1,
            'label' => 'Author',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_personnel_domain_model_person',
                'foreign_table_where' => 'AND tx_personnel_domain_model_person.sys_language_uid IN (-1,0)',
            //    'maxitems' => '1',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
    );
    ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumnsAuthors);
}

// Define content type for Pagelist: subpages
$GLOBALS['TCA']['tt_content']['types']['pagelist_sub']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        --palette--;;headers,
        --palette--;Pages;pagelist_sub_data,
        --palette--;Layout;pagelist_layout,
        --palette--;Filter;pagelist_filtering,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;;frames,
        --palette--;;appearanceLinks,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;;access,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
        categories,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        rowDescription,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
';
if ($extensionConfiguration['pagelistEnablePagination']) {
    $GLOBALS['TCA']['tt_content']['types']['pagelist_sub']['showitem'] = str_replace(
        ';pagelist_filtering,',
        ';pagelist_filtering,
        --palette--;Pagination;paginatedprocessors,',
        $GLOBALS['TCA']['tt_content']['types']['pagelist_sub']['showitem']
    );
}

// Define content type for Pagelist: selected
$GLOBALS['TCA']['tt_content']['types']['pagelist_selected']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        --palette--;;headers,
        pages;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:pages.ALT.menu_formlabel,
        --palette--;Layout;pagelist_selected_layout,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;;frames,
        --palette--;;appearanceLinks,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;;access,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
        categories,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        rowDescription,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
';
if ($extensionConfiguration['pagelistEnablePagination']) {
    $GLOBALS['TCA']['tt_content']['types']['pagelist_selected']['showitem'] = str_replace(
        ';pagelist_selected_layout,',
        ';pagelist_selected_layout,
        --palette--;Pagination;paginatedprocessors,',
        $GLOBALS['TCA']['tt_content']['types']['pagelist_selected']['showitem']
    );
}

// Define content type for Pagelist: subpages
$GLOBALS['TCA']['tt_content']['types']['pagelist_articles_sub'] = [
    'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
            --palette--;Pages;pagelist_sub_data,
            --palette--;Layout;pagelist_layout,
            --palette--;Filter;pagelist_filtering,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    ',
    'columnsOverrides' => [
        'tx_pagelist_orderby' => [
            'config'  => [
                'default' => 'tx_pagelist_datetime DESC',
            ],
        ],
    ],
];

if ($extensionConfiguration['pagelistEnablePagination']) {
    $GLOBALS['TCA']['tt_content']['types']['pagelist_articles_sub']['showitem'] = str_replace(
        ';pagelist_filtering,',
        ';pagelist_filtering,
        --palette--;Pagination;paginatedprocessors,',
        $GLOBALS['TCA']['tt_content']['types']['pagelist_articles_sub']['showitem']
    );
}

$GLOBALS['TCA']['tt_content']['types']['pagelist_events_sub'] = [
    'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
            --palette--;Pages;pagelist_sub_data,
            --palette--;Layout;pagelist_layout,
            --palette--;Filter;pagelist_filtering,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    ',
    'columnsOverrides' => [
        'tx_pagelist_orderby' => [
            'config'  => [
                'default' => 'tx_pagelist_eventstart ASC',
            ],
        ],
    ],
];

if ($extensionConfiguration['pagelistEnablePagination']) {
    $GLOBALS['TCA']['tt_content']['types']['pagelist_events_sub']['showitem'] = str_replace(
        ';pagelist_filtering,',
        ';pagelist_filtering,
        --palette--;Pagination;paginatedprocessors,',
        $GLOBALS['TCA']['tt_content']['types']['pagelist_events_sub']['showitem']
    );
}

// Define palettes for content types
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
    tx_pagelist_titlewrap,
    tx_pagelist_disableimages,
    tx_pagelist_cropratio,--linebreak--,
    tx_imagelazyload,
    tx_pagelist_disableabstract,
';

$GLOBALS['TCA']['tt_content']['palettes']['pagelist_filtering']['showitem'] = '
    selected_categories;by Category (ANY),
    tx_pagelist_authors;AND by Author (ANY),
';

$GLOBALS['TCA']['tt_content']['palettes']['pagelist_selected_layout']['showitem'] = '
    tx_pagelist_template,
    tx_pagelist_titlewrap,
    tx_pagelist_cropratio,
    tx_pagelist_disableimages,
    tx_imagelazyload,
    tx_pagelist_disableabstract,
';

// Set custom BE preview renderer
$GLOBALS['TCA']['tt_content']['types']['pagelist_sub']['previewRenderer'] = PagelistPreviewRenderer::class;
$GLOBALS['TCA']['tt_content']['types']['pagelist_articles_sub']['previewRenderer'] = PagelistPreviewRenderer::class;
$GLOBALS['TCA']['tt_content']['types']['pagelist_events_sub']['previewRenderer'] = PagelistPreviewRenderer::class;
$GLOBALS['TCA']['tt_content']['types']['pagelist_selected']['previewRenderer'] = PagelistPreviewRenderer::class;
