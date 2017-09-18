<?php
defined('TYPO3_MODE') or die();


$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ghost']['connections'][\DFAU\Ghost\CmsConfigurationFactory::DEFAULT_CONNECTION_NAME]['receivers']['WarmCache'] = \DFAU\CacheWarmer\Receiver\WarmCacheReceiver::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ghost']['connections'][\DFAU\Ghost\CmsConfigurationFactory::DEFAULT_CONNECTION_NAME]['receivers']['CollectXmlSitemapUrls'] = \DFAU\CacheWarmer\Receiver\CollectXmlSitemapUrlsReceiver::class;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][\DFAU\CacheWarmer\Command\CacheWarmerCommandController::class] = \DFAU\CacheWarmer\Command\CacheWarmerCommandController::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][\DFAU\CacheWarmer\DataHandling\CacheWarmingHook::class] = \DFAU\CacheWarmer\DataHandling\CacheWarmingHook::class . '->clearCache';