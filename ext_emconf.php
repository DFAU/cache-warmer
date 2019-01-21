<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'DFAU Cache Warmer',
	'description' => '',
	'category' => 'be',
	'shy' => 0,
	'version' => '0.0.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => 'bottom',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Thomas Maroschik',
	'author_email' => 'tmaroschik@dfau.de',
	'author_company' => 'DFAU',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
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
	'suggests' => array(
	),
);

