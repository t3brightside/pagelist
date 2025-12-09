<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Pagelist',
    'description' => 'Page based news, events, products and vacancies or just page lists. Demo: microtemplate.t3brightside.com',
    'category' => 'fe',
    'version' => '5.1.0',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Tanel Põld',
    'author_email' => 'tanel@brightside.ee',
    'author_company' => 'Brightside OÜ / t3brightside.com',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0 - 14.9.99',
            'fluid_styled_content' => '12.4.0 - 14.9.99',
            'paginatedprocessors' => '1.7.0 - 1.9.99',
            'embedassets' => '1.4.0 - 1.9.99',
        ],
        'conflicts' => [
            'blog' => '',
        ],
    'suggests' => [
            'personnel' => '',
            'addresses' => '',
        ],
    ],
    'autoload' => [
         'psr-4' => [
                'Brightside\\Pagelist\\' => 'Classes'
         ]
    ],
];
