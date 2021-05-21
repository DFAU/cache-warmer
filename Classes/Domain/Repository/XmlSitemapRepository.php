<?php

declare(strict_types=1);

namespace DFAU\CacheWarmer\Domain\Repository;

use TYPO3\CMS\Core\Site\SiteFinder;

class XmlSitemapRepository
{
    /** @var SiteFinder */
    protected $siteFinder;

    public function __construct(SiteFinder $siteFinder)
    {
        $this->siteFinder = $siteFinder;
    }

    public function findAll()
    {
        $activeDomains = \array_map(function ($site) {
            return $site->getBase() . '/sitemap.xml';
        }, $this->siteFinder->getAllSites());

        // if (\class_exists(ConnectionPool::class)) {
        //     /** @var ConnectionPool $connectionPool */
        //     $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        //     $connection = $connectionPool->getConnectionForTable('sys_domain');
        //
        //     $queryBuilder = $connection->createQueryBuilder();
        //     $activeDomains = $queryBuilder
        //         ->select('domainName', 'sitemapFileName')
        //         ->from('sys_domain')
        //         ->where(
        //             $queryBuilder->expr()->like('redirectTo', $queryBuilder->expr()->literal('')),
        //             $queryBuilder->expr()->notLike('sitemapFileName', $queryBuilder->expr()->literal(''))
        //         )
        //         ->execute()
        //         ->fetchAll();
        // } else {
        //     $activeDomains = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('domainName,sitemapFileName', 'sys_domain', 'NOT hidden AND redirectTo LIKE "" AND sitemapFileName NOT LIKE ""');
        // }

        // $activeDomains = \array_map(function ($domainRecord) {
        //     return 'https://' . $domainRecord['domainName'] . '/' . $domainRecord['sitemapFileName'];
        // }, $activeDomains);

        return $activeDomains;
    }
}
