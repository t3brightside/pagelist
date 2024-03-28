<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Pagelist',
    'description' => 'Page based news, events, products and vacancies or just page lists.',
    'category' => 'fe',
    'version' => '4.0.0',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Tanel Põld',
    'author_email' => 'tanel@brightside.ee',
    'author_company' => 'Brightside OÜ / t3brightside.com',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0 - 13.9.99',
            'fluid_styled_content' => '12.4.0 - 13.9.99',
            'paginatedprocessors' => '1.6.0 - 1.9.99',
            'embedassets' => '1.3.0 - 1.9.99',
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
