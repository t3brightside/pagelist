<?php
$EM_CONF[$_EXTKEY] = [
	'title' => 'Pagelist',
	'description' => 'Create page lists, teasers and add page types for news, events and products.',
	'category' => 'fe',
	'version' => '2.3.4',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearcacheonload' => true,
	'author' => 'Tanel Põld',
	'author_email' => 'tanel@brightside.ee',
	'author_company' => 'Brightside OÜ / t3brightside.com',
	'constraints' => [
		'depends' => [
			'typo3' => '9.5.0 - 9.5.99',
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
