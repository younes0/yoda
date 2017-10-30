<?php

namespace Yoda\Models;

use Yeb\Laravel\ExtendedModel;

class Origin extends ExtendedModel 
{
    public $table = 'origins';

    protected $guarded = ['id'];

    public function collects()
    {
        return $this->hasMany(Collect::class, 'origin_id');
    }

    public function getLatestTweet()
    {
        $row = \DB::table('tweets AS t')
            ->select('t.id')
            ->leftJoin('collects AS c', 'c.id', '=', 't.collect_id')
            ->leftJoin('origins AS o', 'o.id', '=', 'c.origin_id')
            ->orderBy('t.source_id', 'DESC')
            ->limit(1)
            ->first();

        return $row ? Tweet::find($row->id) : null;
    }
}
