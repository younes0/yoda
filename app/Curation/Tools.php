<?php

namespace Yoda\Curation;

use Carbon\Carbon;
use Yoda\Models\Link;

class Tools
{
    public static function deleteDuplicatedLinks()
    {
        $rows = \DB::select('
            SELECT JSON_AGG(DISTINCT(A.id)) AS ids
            FROM links A, links B
            WHERE B.id != A.id 
            AND SUBSTR(B.content, 0, 800) = SUBSTR(A.content, 0, 800)
            AND A.created_at > ? 
            GROUP BY A.content
        ', [ Carbon::now()->subWeek() ]);
        
        foreach ($rows as $row) {
            $ids       = json_decode($row->ids);
            $firstId   = array_shift($ids);
            $firstLink = Link::find($firstId);

            // refer tweets to first
            foreach ($ids as $id) {
                foreach (Link::find($id)->tweets() as $tweet) {
                    $tweet->update(['expanded_url' => $firstLink->url]);
                }
            }

            // mass-delete other
            Link::destroy($ids);
        }
    }
}
