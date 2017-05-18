<?php


namespace DFAU\CacheWarmer\Receiver;


use Bernard\Message\DefaultMessage;
use GuzzleHttp\Client;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CacheWarmer
{

    /**
     * @param DefaultMessage $message
     */
    public static function warmCache(DefaultMessage $message)
    {
        GeneralUtility::getUrl($message->url);
    }
}