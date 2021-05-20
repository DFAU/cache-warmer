<?php

declare(strict_types=1);

namespace DFAU\CacheWarmer\UrlCollector;

interface UrlCollectorInterface
{
    /**
     * @param array $options
     *
     * @return string[]
     */
    public function getUrls(array $options = []): array;
}
