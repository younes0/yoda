<?php 

namespace Yoda\DataProcessors;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class TweetTransformer extends TransformerAbstract 
{
    public function transform(Array $res) 
    {
        return [
            'source_id'      => (int) $res['id'],
            'published_at'   => Carbon::parse($res['created_at']),
            'content'        => $res['text'],
            'lang'           => $res['lang'],
            'user_id'        => $res['user']['id'],
            'user_name'      => $res['user']['screen_name'],
            'retweet_count'  => (int) $res['retweet_count'],
            'favorite_count' => (int) $res['favorite_count'],
            'is_retweet'     => isset($res['retweeted_status']),
            'urls'           => $this->getUrls($res),
            'hashtags'       => $this->getHashtags($res),
            'images'         => $this->getImages($res),
        ];
    }

    protected function getIsTruncated(Array $res)
    {
        return str_contains($res['text'], '...');
    }

    protected function getUrls(Array $res)
    {
        return array_column($res['entities']['urls'], 'expanded_url');
    }

    protected function getHashtags(Array $res)
    {
        return array_column($res['entities']['hashtags'], 'text');
    }

    protected function getImages(Array $res)
    {
        $out = [];

        if (isset($res['entities']['media'])) {
            foreach ($res['entities']['media'] as $media) {
                if ($media['type']  === 'photo') {
                    $out[] = $media['media_url'];
                }
            }
        }

        return $out;
    }
}
