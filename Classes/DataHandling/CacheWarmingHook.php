<?php


namespace DFAU\CacheWarmer\DataHandling;


use DFAU\CacheWarmer\Domain\Repository\XmlSitemapRepository;
use DFAU\CacheWarmer\Queue;
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Core\Error\Http\ServiceUnavailableException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use DFAU\CacheWarmer\Utility\FrontendSimulatorUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class CacheWarmingHook
{
    /**
     * ClearCache
     * Executed by the clearCachePostProc Hook
     *
     * @param array $cacheCmd
     *
     * @return    void
     */
    public function clearCache(array $cacheCmd)
    {
        // Hook in cache warmer here after varnish
        // Attention, there may be a race condition where something gets warmed before it got purged in varnish because varnish purges are sent through this controller upon __destruct

        /** @var Queue $queue */
        $queue = GeneralUtility::makeInstance(Queue::class);
        $cacheCmd = isset($cacheCmd['cacheCmd']) ? $cacheCmd['cacheCmd'] : $cacheCmd['uid_page'];

        if ($cacheCmd > 0) {

            try {
                FrontendSimulatorUtility::simulateEnvironmentForLinkGeneration($cacheCmd);
            } catch (PageNotFoundException $exception) {
                return;
            } catch (ServiceUnavailableException $exception) {
                return;
            }
            
            /** @var ContentObjectRenderer $cObj */
            $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $frontendLink = $cObj->typoLink_URL(['parameter' => $cacheCmd, 'forceAbsoluteUrl' => true]);
            FrontendSimulatorUtility::resetEnvironment();

            if ($frontendLink) {
                $queue->addUrl($frontendLink);
            }

        } elseif ($cacheCmd === 'pages' || $cacheCmd === 'all') {

            /** @var XmlSitemapRepository $xmlSitemapRepository */
            $xmlSitemapRepository = GeneralUtility::makeInstance(XmlSitemapRepository::class);
            foreach ($xmlSitemapRepository->findAll() as $xmlSitemapUrl) {
                $queue->addXmlSitemapUrl($xmlSitemapUrl);
            }

        }
    }
}
