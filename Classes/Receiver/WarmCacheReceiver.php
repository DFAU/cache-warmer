<?php


namespace DFAU\CacheWarmer\Receiver;

use Bernard\Message\PlainMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class WarmCacheReceiver
{

    /**
     * @param PlainMessage $message
     */
    public static function warmCache(PlainMessage $message)
    {
        GeneralUtility::getUrl($message->url);
    }
}