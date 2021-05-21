<?php

declare(strict_types=1);

namespace DFAU\CacheWarmer\Command;

use DFAU\CacheWarmer\Queue;
use DFAU\CacheWarmer\UrlCollector\XmlSitemapUrlCollector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;


class CacheWarmerCommand extends Command
{
    /** @var \DFAU\CacheWarmer\Queue */
    protected $queue;

    public function injectQueue(Queue $queue)
    {
        $this->queue = $queue;
    }

    protected function configure()
    {
        $this
            ->setDescription('curl sitemap')
            ->addArgument('sitemapUrl', InputArgument::REQUIRED, 'SitemapUrl')
            ->addArgument('command', InputArgument::REQUIRED, 'SitemapUrl');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $sitemapUrl = $input->getArgument('sitemapUrl');
        $command = $input->getArgument('command');

        if ($command === 'clear') {
            $this->clearQueueCommand();
        } else {
            $this->collectXmlSitemapUrlsCommand($sitemapUrl);
        }

        return Command::SUCCESS;
    }

    /**
     * @param string $sitemapUrl
     */
    private function collectXmlSitemapUrlsCommand(string $sitemapUrl)
    {
        /** @var XmlSitemapUrlCollector $urlCollector */
        $urlCollector = GeneralUtility::makeInstance(XmlSitemapUrlCollector::class);
        $urls = $urlCollector->getUrls(['sitemap-url' => $sitemapUrl]);
        $urls = \array_unique($urls);

        foreach ($urls as $url) {
            $this->queue->addUrl($url);
        }
    }

    public function clearQueueCommand()
    {
        $this->queue->clear();
    }
}
