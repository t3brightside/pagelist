<?php
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

defined('TYPO3') || die('Access denied.');

$extensionConfiguration = GeneralUtility::makeInstance(
    ExtensionConfiguration::class
);

$pagelistConiguration = $extensionConfiguration->get('pagelist');
$pagelistArticle = 136;
$pagelistEvent = 137;
$pagelistProduct = 138;
$pagelistVacancy = 139;

$tempColumns = array(
    'tx_pagelist_images' => [
        'exclude' => 1,
        'label' => 'Image',
        'config' => [
            'type' => 'file',
            'maxitems' => 100,
            'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
            'appearance' => [
                'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
            ],
            'overrideChildTca' => [
                'columns' => [
                    'crop' => [
                        'config' => [
                            'cropVariants' => [
                                'default' => [
                                    'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.crop_variant.default',
                                    'allowedAspectRatios' => [

                                        'NaN' => [
                                            'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
                                            'value' => 0.0
                                        ],
                                    ],
                                    'selectedRatio' => 'NaN',
                                    'cropArea' => [
                                        'x' => 0.0,
                                        'y' => 0.0,
                                        'width' => 1.0,
                                        'height' => 1.0,
                                    ],
                                ],
                                'tv' => [
                                    'title' => 'TV (4:3)',
                                    'selectedRatio' => '4:3',
                                    'allowedAspectRatios' => [
                                        '4:3' => [
                                            'title' => 'TV',
                                            'value' => 4 / 3,
                                        ],
                                    ],
                                ],
                                'widescreen' => [
                                    'title' => 'Widescreen (16:9)',
                                    'selectedRatio' => '16:9',
                                    'allowedAspectRatios' => [
                                        '16:9' => [
                                            'title' => 'Widescreen',
                                            'value' => 16 / 9,
                                        ],
                                    ],
                                ],
                                'anamorphic' => [
                                    'title' => 'Anamorphic (2.39:1)',
                                    'selectedRatio' => '2.39:1',
                                    'allowedAspectRatios' => [
                                        '2.39:1' => [
                                            'title' => 'Anamorphic',
                                            'value' => 2.39 / 1,
                                        ],
                                    ],
                                ],
                                'square' => [
                                    'title' => 'Square (1:1)',
                                    'selectedRatio' => '1:1',
                                    'allowedAspectRatios' => [
                                        '1:1' => [
                                            'title' => 'Square',
                                            'value' => 1 / 1,
                                        ],
                                    ],
                                ],
                                'portrait' => [
                                    'title' => 'Portrait (3:4)',
                                    'selectedRatio' => '3:4',
                                    'allowedAspectRatios' => [
                                        '3:4' => [
                                            'title' => 'Portrait (three-four)',
                                            'value' => 3 / 4,
                                        ],
                                    ],
                                ],
                                'tower' => [
                                    'title' => 'Tower (9:16)',
                                    'selectedRatio' => '9:16',
                                    'allowedAspectRatios' => [
                                        '9:16' => [
                                            'title' => 'Tower',
                                            'value' => 9 / 16,
                                        ],
                                    ],
                                ],
                                'skyscraper' => [
                                    'title' => 'Skyscraper (1:2.39)',
                                    'selectedRatio' => '1:2.39',
                                    'allowedAspectRatios' => [
                                        '1:2.39' => [
                                            'title' => 'Skyscraper',
                                            'value' => 1 / 2.39,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
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
    ],
    'tx_pagelist_eventtickets' => [
        'exclude' => 1,
        'label' => 'Tickets info',
        'config' => [
            'type' => 'text',
            'size' => '12',
            'rows' => '3',
            'eval' => 'text,trim',
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ]
    ],
    'tx_pagelist_eventticketslink' => [
        'exclude' => 1,
        'label' => 'Tickets link',
        'config' => [
            'type' => 'input',
            'eval' => 'trim',
            'renderType' => 'inputLink',
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ]
    ],
    'tx_pagelist_eventlocation' => [
        'exclude' => 1,
        'label' => 'Location',
        'config' => [
            'type' => 'text',
            'size' => '12',
            'rows' => '3',
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
            'default' => time(),
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ]
    ],
    'tx_pagelist_eventstart' => [
        'exclude' => 1,
        'label' => 'Start Date & Time',
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
        'label' => 'Show Start Time',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
            'items' => [
                [
                    0 => '',
                    1 => '',
                ]
            ],
        ]
    ],
    'tx_pagelist_eventfinish' => [
        'exclude' => 1,
        'label' => 'End Date & Time',
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
        'label' => 'Show End Time',
        'config' => [
            'type' => 'check',
            'renderType' => 'checkboxToggle',
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
            'items' => [
                [
                    0 => '',
                    1 => '',
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
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
            'items' => [
                [
                    0 => '',
                    1 => '',
                    'invertStateDisplay' => true
                ]
            ],
        ]
    ],
    'tx_pagelist_shortcut' => [
        'exclude' => 1,
        'label' => 'Shortcut to',
        'config' => [
            'type' => 'input',
            'eval' => 'trim',
            'renderType' => 'inputLink',
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ]
    ],
);

if ($pagelistConiguration['pagelistEnableVacancies']) {
    ExtensionManagementUtility::addTcaSelectItem(
        'pages',
        'doktype',
        [
            'Vacancy',
            $pagelistVacancy,
            'apps-pagetree-vacancy',
            'group' => 'default',
        ],
        '1',
        'after'
    );
}

if ($pagelistConiguration['pagelistEnableProducts']) {
    ExtensionManagementUtility::addTcaSelectItem(
        'pages',
        'doktype',
        [
            'Product',
            $pagelistProduct,
            'apps-pagetree-product',
            'group' => 'default',
        ],
        '1',
        'after'
    );
}

if ($pagelistConiguration['pagelistEnableEvents']) {
    ExtensionManagementUtility::addTcaSelectItem(
        'pages',
        'doktype',
        [
        'Event',
        $pagelistEvent,
        'apps-pagetree-event',
        'group' => 'default',
    ],
        '1',
        'after'
    );
}
if ($pagelistConiguration['pagelistEnableArticles']) {
    ExtensionManagementUtility::addTcaSelectItem(
        'pages',
        'doktype',
        [
        'Article',
        $pagelistArticle,
        'apps-pagetree-article',
        'group' => 'default',
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
                $pagelistVacancy => 'apps-pagetree-vacancy',
            ],
        ],
    ]
);


ExtensionManagementUtility::addTCAcolumns('pages', $tempColumns, 1);

// Add Pagelist image to all page types
ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--palette--;Pagelist image;pagelistimages,',
    '1',
    'before:media'
);

// Add 'not in list' field to certian page types
ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'tx_pagelist_notinlist',
    '1,3,4,6,7',
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


$GLOBALS['TCA']['pages']['palettes']['pagelistarticlegeneral']['showitem'] = '
    title,
    --linebreak--,subtitle,
    --linebreak--,slug,
    --linebreak--,tx_pagelist_datetime,lastUpdated,
    --linebreak--,abstract,
    --linebreak--,tx_pagelist_images,
    --linebreak--,tx_pagelist_shortcut,
';

$GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem'] = $GLOBALS['TCA']['pages']['types'][1]['showitem'];
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

$GLOBALS['TCA']['pages']['palettes']['pagelistproductgeneral']['showitem'] = '
    title,
    --linebreak--,subtitle,
    --linebreak--,slug,
    --linebreak--,abstract,
    --linebreak--,tx_pagelist_productprice,
    --linebreak--,tx_pagelist_images,
    --linebreak--,tx_pagelist_datetime,lastUpdated,
';


// Event page type
$GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem'] = $GLOBALS['TCA']['pages']['types'][1]['showitem'];
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
$GLOBALS['TCA']['pages']['palettes']['pagelisteventgeneral']['showitem'] = '
    title,
    --linebreak--,subtitle,
    --linebreak--,slug,
    --linebreak--,tx_pagelist_datetime,lastUpdated,
    --linebreak--,tx_pagelist_eventstart,tx_pagelist_starttime,tx_pagelist_eventfinish,tx_pagelist_endtime,
    --linebreak--,tx_pagelist_eventtickets,tx_pagelist_eventticketslink,
    --linebreak--,tx_pagelist_eventlocation,tx_pagelist_eventlocationlink,
    --linebreak--,abstract,
    --linebreak--,tx_pagelist_images,
    --linebreak--,tx_pagelist_shortcut,
';


$GLOBALS['TCA']['pages']['palettes']['pagelistimages']['showitem'] = 'tx_pagelist_images,';

// Vacancy page type
$GLOBALS['TCA']['pages']['types'][$pagelistVacancy]['showitem'] = $GLOBALS['TCA']['pages']['types'][1]['showitem'];
$GLOBALS['TCA']['pages']['types'][$pagelistVacancy]['showitem'] = str_replace(
    ';title,',
    ';,--palette--;Vacancy;pagelistvacancygeneral,',
    $GLOBALS['TCA']['pages']['types'][$pagelistVacancy]['showitem']
);
$GLOBALS['TCA']['pages']['types'][$pagelistVacancy]['showitem'] = str_replace(
    ';abstract,',
    '--palette--;;,',
    $GLOBALS['TCA']['pages']['types'][$pagelistVacancy]['showitem']
);
$GLOBALS['TCA']['pages']['types'][$pagelistVacancy]['showitem'] = str_replace(
    ';editorial,',
    '--palette--;;,',
    $GLOBALS['TCA']['pages']['types'][$pagelistVacancy]['showitem']
);
$GLOBALS['TCA']['pages']['types'][$pagelistVacancy]['showitem'] = str_replace(
    'pagelistimages,',
    '',
    $GLOBALS['TCA']['pages']['types'][$pagelistVacancy]['showitem']
);
$GLOBALS['TCA']['pages']['palettes']['pagelistvacancygeneral']['showitem'] = '
    title,
    --linebreak--,subtitle,
    --linebreak--,slug,
    --linebreak--,tx_pagelist_datetime,lastUpdated,
    --linebreak--,tx_pagelist_eventfinish,tx_pagelist_eventstart,
    --linebreak--,abstract,
    --linebreak--,tx_pagelist_images,
    --linebreak--,tx_pagelist_shortcut,
';


$GLOBALS['TCA']['pages']['palettes']['pagelistimages']['showitem'] = 'tx_pagelist_images,';

if ($pagelistConiguration['pagelistEnableInlineContentEditing']) {
    // Add a new tab to the pages TCA for managing tt_content elements
    $GLOBALS['TCA']['pages']['types']['136']['showitem'] .= ',--div--;Content, tx_pagelist_content';
    $GLOBALS['TCA']['pages']['types']['137']['showitem'] .= ',--div--;Content, tx_pagelist_content';
    $GLOBALS['TCA']['pages']['types']['138']['showitem'] .= ',--div--;Content, tx_pagelist_content';
    $GLOBALS['TCA']['pages']['types']['139']['showitem'] .= ',--div--;Content, tx_pagelist_content';

    // Add configuration to display tt_content elements inline
    $GLOBALS['TCA']['pages']['columns']['tx_pagelist_content'] = [
        'label' => 'Content',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tt_content',
            'foreign_field' => 'pid',
            'appearance' => [
                'useSortable' => true,
                'collapseAll' => true,
                'levelLinksPosition' => 'bottom',
                'showSynchronizationLink' => true,
                'showPossibleLocalizationRecords' => true,
                'showAllLocalizationLink' => true,
                'enabledControls' => [
                    'info' => true,
                    'new' => true,
                    'dragdrop' => true,
                    'sort' => false,
                    'hide' => true,
                    'delete' => true,
                    'localize' => true,
                ],
            ],
        ],
    ];
}

ExtensionManagementUtility::registerPageTSConfigFile(
    'pagelist',
    'Configuration/TSConfig/limit_to_pagelist_types.tsconfig',
    'Pagelist - Allow only Pagelist page types'
);
