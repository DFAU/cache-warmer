<?php

declare(strict_types=1);

namespace DFAU\CacheWarmer;

use Bernard\Message\PlainMessage;
use Bernard\Producer;
use Bernard\Util;
use DFAU\Ghost\CmsConfigurationFactory;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

class Queue implements SingletonInterface
{
    /** @var Producer */
    protected $producer;

    /** @var Dispatcher */
    protected $signalSlotDispatcher;

    /**
     * Queue constructor.
     *
     * @param Producer|null $producer
     * @param Dispatcher $signalSlotDispatcher $signalSlotDispatcher
     */
    public function __construct(Producer $producer = null, Dispatcher $signalSlotDispatcher = null)
    {
        if (null === $producer) {
            $queues = CmsConfigurationFactory::getQueueFactoryForConnectionName();
            /** @var Producer $producer */
            $producer = GeneralUtility::makeInstance(
                Producer::class,
                $queues,
                CmsConfigurationFactory::getEventDispatcherForDirectionAndConnectionName(
                    $queues,
                    CmsConfigurationFactory::MIDDLEWARE_DIRECTION_PRODUCER
                )
            );
        }

        $this->producer = $producer;

        if (null === $signalSlotDispatcher) {
            $signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        }

        $this->signalSlotDispatcher = $signalSlotDispatcher;
    }

    /**
     * @param string $url
     */
    public function addUrl(string $url)
    {
        $url = $this->emitBeforeAddingUrlSignal($url);
        $this->producer->produce(new PlainMessage('WarmCache', ['url' => $url]));
    }

    /**
     * @param string $url
     */
    public function addXmlSitemapUrl(string $url)
    {
        $url = $this->emitBeforeAddingUrlSignal($url);
        $this->producer->produce(new PlainMessage('CollectXmlSitemapUrls', ['url' => $url]));
    }

    public function clear()
    {
        // TODO this should relate more closely to the given producer in the constructor
        $queues = CmsConfigurationFactory::getQueueFactoryForConnectionName();

        foreach (['WarmCache', 'CollectXmlSitemapUrls'] as $messageName) {
            $queueName = Util::guessQueue(new PlainMessage($messageName));
            $queues->remove($queueName);
        }
    }

    /**
     * Emits a signal before the url is added
     *
     * @param string $url
     *
     * @return array Modified $url
     */
    protected function emitBeforeAddingUrlSignal($url)
    {
        $signalArguments = $this->signalSlotDispatcher->dispatch(__CLASS__, 'beforeAddingUrl', [$url]);
        return $signalArguments[0];
    }

    /**
     * Emits a signal before the xml sitemap url is added
     *
     * @param string $url
     *
     * @return array Modified $url
     */
    protected function emitBeforeAddingXmlSitemapUrlSignal($url)
    {
        $signalArguments = $this->signalSlotDispatcher->dispatch(__CLASS__, 'beforeAddingXmlSitemapUrl', [$url]);
        return $signalArguments[0];
    }
}
