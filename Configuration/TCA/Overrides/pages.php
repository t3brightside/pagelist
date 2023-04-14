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
        'label' => 'Images',
        'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
            'tx_pagelist_images',
            [
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'overrideChildTca' => [
                    'types' => [
                        '0' => [
                            'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette
                            '
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette
                            '
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
);

if ($pagelistConiguration['pagelistEnableVacancies']) {
    ExtensionManagementUtility::addTcaSelectItem(
        'pages',
        'doktype',
        [
            'Vacancy',
            $pagelistVacancy,
            'apps-pagetree-vacancy'
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
            'apps-pagetree-product'
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
        'apps-pagetree-event'
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
$GLOBALS['TCA']['pages']['types'][$pagelistArticle]['showitem'] = str_replace(
    'personnelcontact,',
    ',',
    $GLOBALS['TCA']['pages']['types'][$pagelistArticle]['showitem']
);

if (ExtensionManagementUtility::isLoaded('personnel') and $pagelistConiguration['pagelistEnableArticlePersonnel']) {
    $GLOBALS['TCA']['pages']['palettes']['pagelistarticlegeneral']['showitem'] = '
        title,
        --linebreak--,subtitle,
        --linebreak--,slug,
        --linebreak--,tx_pagelist_datetime,lastUpdated,
        --linebreak--,abstract,
        --linebreak--,tx_personnel_authors,
        --linebreak--,tx_pagelist_images,
    ';
} else {
    $GLOBALS['TCA']['pages']['palettes']['pagelistarticlegeneral']['showitem'] = '
        title,
        --linebreak--,subtitle,
        --linebreak--,slug,
        --linebreak--,tx_pagelist_datetime,lastUpdated,
        --linebreak--,abstract,
        --linebreak--,author,author_email,
        --linebreak--,tx_pagelist_images,
    ';
}
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
$GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem'] = str_replace(
    'personnelcontact,',
    ',',
    $GLOBALS['TCA']['pages']['types'][$pagelistProduct]['showitem']
);

if (ExtensionManagementUtility::isLoaded('personnel') and $pagelistConiguration['pagelistEnableProductPersonnel']) {
    $GLOBALS['TCA']['pages']['palettes']['pagelistproductgeneral']['showitem'] = '
        title,
        --linebreak--,subtitle,
        --linebreak--,slug,
        --linebreak--,abstract,
        --linebreak--,tx_pagelist_productprice,
        --linebreak--,tx_personnel_authors,
        --linebreak--,tx_pagelist_images,
        --linebreak--,tx_pagelist_datetime,lastUpdated,
    ';
} else {
    $GLOBALS['TCA']['pages']['palettes']['pagelistproductgeneral']['showitem'] = '
        title,
        --linebreak--,subtitle,
        --linebreak--,slug,
        --linebreak--,abstract,
        --linebreak--,tx_pagelist_productprice,
        --linebreak--,tx_pagelist_images,
        --linebreak--,tx_pagelist_datetime,lastUpdated,author,author_email,
    ';
}

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
$GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem'] = str_replace(
    'personnelcontact,',
    ',',
    $GLOBALS['TCA']['pages']['types'][$pagelistEvent]['showitem']
);

if (ExtensionManagementUtility::isLoaded('personnel') and $pagelistConiguration['pagelistEnableEventPersonnel']) {
    $GLOBALS['TCA']['pages']['palettes']['pagelisteventgeneral']['showitem'] = '
        title,
        --linebreak--,subtitle,
        --linebreak--,slug,
        --linebreak--,tx_pagelist_datetime,lastUpdated,
        --linebreak--,tx_pagelist_eventstart,tx_pagelist_starttime,tx_pagelist_eventfinish,tx_pagelist_endtime,
        --linebreak--,tx_pagelist_eventlocation,
        --linebreak--,tx_pagelist_eventlocationlink,
        --linebreak--,abstract,
        --linebreak--,tx_personnel_authors,
        --linebreak--,tx_pagelist_images,
    ';
} else {
    $GLOBALS['TCA']['pages']['palettes']['pagelisteventgeneral']['showitem'] = '
        title,
        --linebreak--,subtitle,
        --linebreak--,slug,
        --linebreak--,tx_pagelist_datetime,lastUpdated,
        --linebreak--,tx_pagelist_eventstart,tx_pagelist_starttime,tx_pagelist_eventfinish,tx_pagelist_endtime,
        --linebreak--,tx_pagelist_eventlocation,
        --linebreak--,tx_pagelist_eventlocationlink,
        --linebreak--,abstract,
        --linebreak--,author,author_email,
        --linebreak--,tx_pagelist_images,
    ';
}

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
$GLOBALS['TCA']['pages']['types'][$pagelistVacancy]['showitem'] = str_replace(
    'personnelcontact,',
    ',',
    $GLOBALS['TCA']['pages']['types'][$pagelistVacancy]['showitem']
);

if (ExtensionManagementUtility::isLoaded('personnel') and $pagelistConiguration['pagelistEnableVacancyPersonnel']) {
    $GLOBALS['TCA']['pages']['palettes']['pagelistvacancygeneral']['showitem'] = '
        title,
        --linebreak--,subtitle,
        --linebreak--,slug,
        --linebreak--,tx_pagelist_datetime,lastUpdated,
        --linebreak--,tx_pagelist_eventfinish,tx_pagelist_eventstart,
        --linebreak--,abstract,
        --linebreak--,tx_personnel_authors,
        --linebreak--,tx_pagelist_images,
    ';
} else {
    $GLOBALS['TCA']['pages']['palettes']['pagelistvacancygeneral']['showitem'] = '
        title,
        --linebreak--,subtitle,
        --linebreak--,slug,
        --linebreak--,tx_pagelist_datetime,lastUpdated,
        --linebreak--,tx_pagelist_eventfinish,tx_pagelist_eventstart,
        --linebreak--,abstract,
        --linebreak--,author,author_email,
        --linebreak--,tx_pagelist_images,
    ';
}

$GLOBALS['TCA']['pages']['palettes']['pagelistimages']['showitem'] = 'tx_pagelist_images,';
