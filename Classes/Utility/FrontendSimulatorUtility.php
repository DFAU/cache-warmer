<?php

declare(strict_types=1);

namespace DFAU\CacheWarmer\Utility;

use Doctrine\Instantiator\Instantiator;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class FrontendSimulatorUtility
{
    /** @var mixed */
    protected static $tsfeBackup;

    /**
     * @param int $pid
     */
    public static function simulateEnvironmentForLinkGeneration(int $pid)
    {
        self::$tsfeBackup = isset($GLOBALS['TSFE']) ? $GLOBALS['TSFE'] : null;
        /** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $typoScriptFrontendController */
        $GLOBALS['TSFE'] = $typoScriptFrontendController = (new Instantiator())->instantiate(TypoScriptFrontendController::class);
        $typoScriptFrontendController->absRefPrefix = '/';
        $typoScriptFrontendController->config = ['config' => ['absRefPrefix' => '/', 'typolinkEnableLinksAcrossDomains' => true], 'mainScript' => 'index.php'];
        $typoScriptFrontendController->id = $pid;
        $typoScriptFrontendController->fe_user = new \stdClass();
        $typoScriptFrontendController->sys_page = GeneralUtility::makeInstance(PageRepository::class);
        $typoScriptFrontendController->tmpl = GeneralUtility::makeInstance(TemplateService::class);
        $typoScriptFrontendController->cObjectDepthCounter = 100;
        $typoScriptFrontendController->cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        // v10change TODO: adapt as its protected now - this whole
        // $typoScriptFrontendController->getPageAndRootline();
    }

    /**
     * Resets $GLOBALS['TSFE'] if it was previously changed by simulateEnvironmentForPid()
     */
    public static function resetEnvironment()
    {
        if (!empty(self::$tsfeBackup)) {
            $GLOBALS['TSFE'] = self::$tsfeBackup;
        }
    }
}
