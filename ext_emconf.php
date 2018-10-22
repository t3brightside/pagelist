<?php
$EM_CONF[$_EXTKEY] = array (
	'title' => 'Page List',
	'description' => 'Content element and page types to list pages, news, events, products etc.',
	'category' => 'fe',
	'version' => '2.1.2',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearcacheonload' => true,
	'author' => 'Tanel Põld',
	'author_email' => 'tanel@brightside.ee',
	'author_company' => 'Brightside OÜ / t3brightside.com',
	'constraints' =>
	array (
		'depends' =>
		array (
			'typo3' => '8.7.0 - 9.5.99',
			'fluid_styled_content' => '',
		),
		'conflicts' =>
		array (
		),
		'suggests' =>
		array (
		),
	),
);
