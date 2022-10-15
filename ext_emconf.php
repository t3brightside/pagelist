<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Pagelist',
    'description' => 'Create page lists/teasers and add page types for news, events, products and vacancies.',
    'category' => 'fe',
    'version' => '3.3.2',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Tanel Põld',
    'author_email' => 'tanel@brightside.ee',
    'author_company' => 'Brightside OÜ / t3brightside.com',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0 - 12.99.99',
            'fluid_styled_content' => '11.5.0 - 12.99.99',
            'paginatedprocessors' => '1.4.0 - 1.99.99',
        ],
        'conflicts' => [
            'blog' => '',
        ],
    'suggests' => [
            'personnel' => '',
        ],
    ],
    'autoload' => [
         'psr-4' => [
                'Brightside\\Pagelist\\' => 'Classes'
         ]
    ],
];
