<?php


namespace DFAU\CacheWarmer;


use Bernard\Message\DefaultMessage;
use Bernard\Producer;
use DFAU\Ghost\CmsConfigurationFactory;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

class Queue implements SingletonInterface
{

    /**
     * @var Producer
     */
    protected $producer;

    /**
     * @var Dispatcher
     */
    protected $signalSlotDispatcher;

    /**
     * Queue constructor.
     * @param Producer|null $producer
     * @param Dispatcher $signalSlotDispatcher $signalSlotDispatcher
     */
    public function __construct(Producer $producer = null, Dispatcher $signalSlotDispatcher = null)
    {
        if ($producer === null) {
            $queues = CmsConfigurationFactory::getQueueFactoryForConnectionName();
            /** @var Producer $producer */
            $producer = GeneralUtility::makeInstance(Producer::class, $queues, CmsConfigurationFactory::getMiddlewareForDirectionAndConnectionName($queues, CmsConfigurationFactory::MIDDLEWARE_DIRECTION_PRODUCER));
        }
        $this->producer = $producer;

        if ($signalSlotDispatcher === null) {
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
        $this->producer->produce(new DefaultMessage('WarmCache', ['url' => $url]));
    }

    /**
     * Emits a signal before the url is added
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param array $result
     * @return array Modified $url
     */
    protected function emitBeforeAddingUrlSignal($url)
    {
        $signalArguments = $this->signalSlotDispatcher->dispatch(__CLASS__, 'beforeAddingUrl', [$url]);
        return $signalArguments[0];
    }

}