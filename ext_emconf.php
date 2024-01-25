<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Pagelist',
    'description' => 'Page based news, events, products and vacancies or just page lists.',
    'category' => 'fe',
    'version' => '3.8.1',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Tanel Põld',
    'author_email' => 'tanel@brightside.ee',
    'author_company' => 'Brightside OÜ / t3brightside.com',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0 - 12.4.99',
            'fluid_styled_content' => '11.5.0 - 12.4.99',
            'paginatedprocessors' => '1.5.1 - 1.99.99',
            'embedassets' => '1.2.0 - 1.99.99',
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
