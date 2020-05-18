<?php
$EM_CONF[$_EXTKEY] = [
	'title' => 'Pagelist',
	'description' => 'Create page lists, teasers and add page types for news, events and products.',
	'category' => 'fe',
	'version' => '2.4.0',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearcacheonload' => true,
	'author' => 'Tanel Põld',
	'author_email' => 'tanel@brightside.ee',
	'author_company' => 'Brightside OÜ / t3brightside.com',
	'constraints' => [
		'depends' => [
			'typo3' => '9.5.0 - 10.4.99',
			'fluid_styled_content' => '',
		],
		'conflicts' => [
			'blog' => '',
		],
    'suggests' => [
			'personnel' => '',
		],
	],
];
