<?php


namespace DFAU\CacheWarmer\UrlCollector;


use GuzzleHttp\Client;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class XmlSitemapUrlCollector implements UrlCollectorInterface
{

    const XPATH_SITEMAP_LOC = '//s:sitemap/s:loc';
    const XPATH_URL_LOC = '//s:url/s:loc';

    /**
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public function getUrls(array $options = []) : array
    {
        $sitemaps = [$options['sitemap-url']];

        $urls = [];
        try {
            while ($sitemapUrl = array_shift($sitemaps)) {
                $sitemapBody = GeneralUtility::getUrl($sitemapUrl);
                if (!$sitemapBody) {
                    continue;
                }
                $reader = $this->getXmlReader($sitemapBody);
                $sitemaps = array_merge($sitemaps, $this->getUrlsAsStrings($this->getLocElements($reader, self::XPATH_SITEMAP_LOC)));
                $urls = array_merge($urls, $this->getUrlsAsStrings($this->getLocElements($reader, self::XPATH_URL_LOC)));
            }
        } catch (\Exception $e) {
            if (!$this->isInvalidXmlException($e)) {
                throw $e;
            }
        }

        return $urls;
    }

    /**
     * @param $xmlElements
     *
     * @return string[]
     */
    protected function getUrlsAsStrings($xmlElements, array &$sitemaps = [])
    {
        /** @var string[] $return */
        $stringUrls = [];
        foreach ($xmlElements as $xmlElement) {
            $stringUrls[] = (string)$xmlElement;
        }
        return $stringUrls;
    }

    /**
     * @param $e
     *
     * @return bool
     */
    protected function isInvalidXmlException(\Exception $e)
    {
        return $e->getMessage() == 'String could not be parsed as XML';
    }

    /**
     * @param $response
     *
     * @return \SimpleXMLElement
     */
    protected function getXmlReader($response)
    {
        $xml = new \SimpleXMLElement($response);
        $this->registerNamespaces($xml);
        return $xml;
    }

    protected function getLocElements(\SimpleXMLElement $reader, $locType = self::XPATH_URL_LOC)
    {
        return $reader->xpath($locType);
    }

    protected function registerNamespaces(\SimpleXMLElement $xml)
    {
        $xml->registerXPathNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    }
}