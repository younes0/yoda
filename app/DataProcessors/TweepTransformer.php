<?php 

namespace Yoda\DataProcessors;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class TweepTransformer extends TransformerAbstract 
{
    public function transform(Array $res) 
    {
        return [
            'screen_name'   => $res['screen_name'],
            'source_id'     => (int) $res['id_str'],
            'location'      => $res['location'],
            'description'   => $res['description'],
            'followers'     => (int) $res['followers_count'],
            'is_protected'  => (bool) $res['protected'],
            'tweets_count'  => (int) $res['statuses_count'],
            'last_tweet_at' => isset($res['status']['created_at'])
                ? Carbon::parse($res['status']['created_at'])
                : null,
        ];
    }
}
