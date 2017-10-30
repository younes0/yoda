<?php 

namespace Yoda\DataProcessors;

use Yoda\Models\Tweet;
use Yoda\Libraries\Url;

class TweetFilterer
{
    const LANG = 'fr';

    public function filterRaw(Array $raw)
    {
        return (
            empty($raw['urls'])
            || ($raw['lang'] !== self::LANG)
            || Tweet::where(array_only($raw, ['source_id']))->first()
            || (($host = Url::getHost($raw['urls'][0])) && $host->is_ignored)
        );
    }
}
