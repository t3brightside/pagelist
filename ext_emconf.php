<?php
$EM_CONF[$_EXTKEY] = [
	'title' => 'Pagelist',
	'description' => 'Create page lists/teasers and add page types for news, events, products and vacancies.',
	'category' => 'fe',
	'version' => '3.0.0',
	'state' => 'stable',
	'clearCacheOnLoad' => true,
	'author' => 'Tanel Põld',
	'author_email' => 'tanel@brightside.ee',
	'author_company' => 'Brightside OÜ / t3brightside.com',
	'constraints' => [
		'depends' => [
			'typo3' => '11.5.0 - 11.5.99',
			'fluid_styled_content' => '11.5.0 - 11.5.99',
			'paginatedprocessors' => '1.0.0 - 1.0.99',
		],
		'conflicts' => [
			'blog' => '',
		],
    'suggests' => [
			'personnel' => '',
		],
		'autoload' => [
        'classmap' => ['Classes'],
    ],
	],
];
