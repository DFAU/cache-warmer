<?php
defined('TYPO3_MODE') or die();


$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ghost']['connections'][\DFAU\Ghost\CmsConfigurationFactory::DEFAULT_CONNECTION_NAME]['receivers']['WarmCache'] = \DFAU\CacheWarmer\Receiver\CacheWarmer::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][\DFAU\CacheWarmer\Command\CacheWarmerCommandController::class] = \DFAU\CacheWarmer\Command\CacheWarmerCommandController::class;