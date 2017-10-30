<?php 

namespace Yoda\DataProcessors;

use Yoda\Models\Link;
use Yoda\Libraries\Url;
use League\Url\Url as LeagueUrl;
use Carbon\Carbon;

class LinkFilterer
{
    const MIN_LENGTH = 1000;
    const MAX_WEEKS = 2;
    const LANG = 'fr';

    public function filterRaw($url)
    {
        $host       = Url::getHost($url);
        $league     = LeagueUrl::createFromUrl($url);
        $urlWoQuery = (String) $league->setQuery([]);
        
        return ( 
            ($host && ($host->is_ignored || $host->is_banned))
            || ends_with($league->getHost(), static::$domainExts)
            || ends_with($url, static::$fileExts)
            || ends_with($urlWoQuery, static::$fileExts)
        );
    }

    // after infos fetch
    public function filterScraped(Link $link)
    {
        $maxOld = Carbon::now()->subWeeks(self::MAX_WEEKS);

        return (
            !$link->html
            || !$link->content
            || !in_array($link->type, ['link', 'rich'])
            || ($link->lang !== self::LANG)
            || $link->has_paywall 
            || ($link->published_at && $link->published_at->lt($maxOld))
            || (strlen($link->content) < self::MIN_LENGTH)
        );
    }

    static protected $domainExts = [
        '.be',
        '.ca',
        '.ch',
        '.dz',
        '.lu',
        '.ma',
        '.sn',
        '.tn',
        '.uk',
    ];

    static protected $fileExts = [
        'aac',
        'gif', 
        'jpeg', 
        'jpg', 
        'mp3',
        'mp4',
        'ogg',
        'pdf', 
        'png',
    ];
}
