<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'DFAU Cache Warmer',
	'description' => '',
	'category' => 'be',
	'version' => '0.0.0',
	'state' => 'beta',
	'createDirs' => '',
	'clearCacheOnLoad' => true,
	'author' => 'Thomas Maroschik',
	'author_email' => 'tmaroschik@dfau.de',
	'author_company' => 'DFAU',
	'constraints' => array(
		'depends' => array(
			'php' => '7.0.0-0.0.0',
			'typo3' => '7.6.0-9.99.99',
            'ghost' => '*',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);

