<?php


namespace DFAU\CacheWarmer\Command;

use DFAU\CacheWarmer\UrlCollector\XmlSitemapUrlCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

class CacheWarmerCommandController extends CommandController
{

    /**
     * @var \DFAU\CacheWarmer\Queue
     * @inject
     */
    protected $queue;

    /**
     * @param string $sitemapUrl
     */
    public function collectXmlSitemapUrlsCommand(string $sitemapUrl)
    {
        /** @var XmlSitemapUrlCollector $urlCollector */
        $urlCollector = GeneralUtility::makeInstance(XmlSitemapUrlCollector::class);
        $urls = $urlCollector->getUrls(['sitemap-url' => $sitemapUrl]);
        $urls = array_unique($urls);

        foreach ($urls as $url) {
            $this->queue->addUrl($url);
        }
    }

}