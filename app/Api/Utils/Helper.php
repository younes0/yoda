<?php

namespace Yoda\Api\Utils;

use Yoda\Api\Clients\ClientsFacade as Clients;
use Carbon\Carbon;

class Helper
{   
    public function postJediwp(
        $url, $title, $content, Array $tags = [], Carbon $date = null, 
        $votes = 1, $author = null, Array $extra = []
    ) {
        return Clients::jediwp()->post('posts', [
            'json' => array_merge([
                'type'        => 'wpri_submit',
                'status'      => $date ? 'future' : 'publish', 
                'date_gmt'    => $date ? $date->toRfc3339String() : null,
                'title'       => $title,
                'content_raw' => $content,
                'submit_cat'  => $tags,
                'author'      => $author,
                'post_meta'   => [
                    [ 'key' => 'wpri_url', 'value' => $url ],
                    [ 'key' => 'wpri_upvotes', 'value' => $votes ],
                ],
            ], $extra)
        ]);
    }

    public function getHomeTimeline(array $query = [], $accountId = null)
    {
        return Clients::twitter($accountId)->get('statuses/home_timeline.json', compact('query'));
    }

    public function getListStatuses($listId, array $query = [], $accountId = null)
    {
        if ( !$listId) { // facade bug?
            throw new \Exception;
        }

        $query['list_id'] = $listId;

        return Clients::twitter($accountId)->get('lists/statuses.json', compact('query'));
    }
}
