<?php

declare(strict_types=1);

namespace DFAU\CacheWarmer\Receiver;

use Bernard\Message\PlainMessage;
use DFAU\CacheWarmer\Queue;
use DFAU\CacheWarmer\UrlCollector\XmlSitemapUrlCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CollectXmlSitemapUrlsReceiver
{
    /**
     * @param PlainMessage $message
     */
    public static function collectXmlSitemapUrls(PlainMessage $message)
    {
        /** @var XmlSitemapUrlCollector $urlCollector */
        $urlCollector = GeneralUtility::makeInstance(XmlSitemapUrlCollector::class);
        $urls = $urlCollector->getUrls(['sitemap-url' => $message->url]);
        $urls = \array_unique($urls);

        /** @var Queue $queue */
        $queue = GeneralUtility::makeInstance(Queue::class);
        foreach ($urls as $url) {
            $queue->addUrl($url);
        }
    }
}
