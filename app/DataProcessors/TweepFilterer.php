<?php

namespace Yoda\DataProcessors;

use Yoda\Models\Tweep;
use Yoda\Libraries\Url;
use YebString;
use Str;

class TweepFilterer 
{
    const MONTHS_MAX = 3;
    const FOLLOWERS_MIN = 15;
    const TWEETS_MIN = 20;
    const TWEETS_PER_DAY_MIN = 0.05;
    const PROPER_LANG_PER_TWEET_MIN = 0.5;
    const LINKS_PER_TWEET_MIN = 0.2;

    public function filterRaw(Array $raw, $existing = true)
    {
        foreach (config('yoda.tweeps.keywords') as $key => $words) {
            $regex[$key] = $this->makeRegex($words);
        }

        $desc     = YebString::removeAccents(Str::lower($raw['description'] . $raw['screen_name']));
        $location = YebString::removeAccents(Str::lower($raw['location']));
        
        return (
            $raw['is_protected']
            || ($raw['followers'] < self::FOLLOWERS_MIN) 
            || is_null($raw['last_tweet_at'])
            || ($raw['last_tweet_at']->diffInMonths() > self::MONTHS_MAX)
            || ($raw['tweets_count'] < self::TWEETS_MIN)
            || preg_match_all($regex['location_out'], $location)
            || preg_match_all($regex['desc_out'], $desc)
            || preg_match_all($regex['location_out'], $desc)
            || !preg_match_all($regex['desc_in'], $desc)
            || ($existing && Tweep::find($raw['screen_name'])) // exists
        );
    }

    // after metrics update
    public function filterMetrics(Tweep $tweep)
    {
        if ( !$tweep->metrics_updated_at) return;

        return (
            ($tweep->tweets_per_day < self::TWEETS_PER_DAY_MIN)
            || ($tweep->proper_lang_per_tweet < self::PROPER_LANG_PER_TWEET_MIN)
            || ($tweep->links_per_tweet < self::LINKS_PER_TWEET_MIN)
        );
    }

    public function filterUrls(Tweep $tweep)
    {
        if ( !$tweep->tweets_urls) return;

        foreach ($tweep->tweets_urls as $url) {
            $host = Url::getHost($url);
            
            if ($host && $host->is_banned) { 
                return true;
            }
        }

        return false;
    }

    protected function makeRegex($words)
    {
        return sprintf('/(%s)/i', implode('|', $words));
    }
}
