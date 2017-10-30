<?php

namespace Yoda\Datatable;

use DB, Datatable;
use Carbon\Carbon;
use Yeb\Libraries\Datatable as YebDatatable;

class OriginsDatatable extends YebDatatable
{
    /**
     * Columns for the Datatable
     * See parent class for further documentation
     * 
     * @var array
     */
    protected $columns = [
        [ 'data' => 'id' ],
        [ 'data' => 'type' ],
        [ 'data' => 'name', 'encode' => false ],
        [ 'data' => 'tags'],
        [ 'data' => 'last_collect'], 
    ];

   /**
     * $hasActions determines if a 'Action(s)' column should be added 
     * See parent class for further documentation
     * 
     * @var array
     */
    protected $hasActions = true;

    /**
     * Returns all data after decoration & Chumper\Datatable\Datatable wrapping
     * See parent class for further documentation
     *
     * @return array Chumper/Datatable data array
     */
    public function getData()
    {
        $columns = array_fetch($this->columns, 'data');

        $this->query = DB::table('origins AS o')
            ->select(
                'o.*', 
                DB::raw('ARRAY_AGG(tt.tag_name) AS tags',
                'c.id AS last_collect'
            ))
            ->leftJoin(
                DB::raw('(SELECT * FROM collects ORDER BY updated_AT DESC LIMIT 1) AS c'), // limit join
                'c.origin_id', '=', 'o.id'
            )
            ->leftJoin('tagging_tagged AS tt', 'tt.taggable_id', '=', \DB::raw(
                "o.id AND tt.taggable_type = 'Yoda\Models\Origin'"
            ))
            ->groupBy('o.id')
        ;

        // decorates data & and use data to build and return Chumper\Datatable\Datatable array
        return $this->addDecorators(Datatable::query($this->query)->showColumns($columns))
            ->setSearchWithAlias()
            ->orderColumns($columns)
            ->setAliasMapping()
            ->make();
    }


    protected function decorateActions($m)
    {
        return \Button::normal('Source')
            ->small()
            ->asLinkTo('https://twitter.com/statuses/'.$m->source_id);
    }

    protected function decorateName($m)
    {
        return \HTML::link('https://twitter.com/intent/user?user_id='.$m->source_id, $m->name, [
            'target' => '_blank',
        ]);
    }

    protected function decorateCreatedAt($m)
    {
        return Carbon::parse($m->created_at)->diffForHumans();
    }
}
