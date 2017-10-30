<?php

namespace Yoda\Datatable;

use DB, Datatable;
use Carbon\Carbon;
use Yeb\Libraries\Datatable as YebDatatable;

class UsersDatatable extends YebDatatable
{
    /**
     * Columns for the Datatable
     * See parent class for further documentation
     * 
     * @var array
     */
    protected $columns = [
        [ 'data' => 'id' ],
        [ 'data' => 'email' ],
        [ 'data' => 'firstname', 'title' => 'Prénom' ],
        [ 'data' => 'lastname', 'title' => 'Nom' ],
        [ 'data' => 'deleted_at' ],
        [ 'data' => 'created_at' ],
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

        $this->query = DB::table('users AS u')
            ->select('u.*')
            ->groupBy('u.id')
        ;

        // decorates data & and use data to build and return Chumper\Datatable\Datatable array
        return $this->addDecorators(Datatable::query($this->query)->showColumns($columns))
            ->searchColumns(['email', 'firstname', 'lastname'])
            ->setSearchWithAlias()
            ->orderColumns($columns)
            ->setAliasMapping()
            ->make();
    }

    /**
     * action decorator 
     * 
     * @param  Collection $m Query result row
     * @return String html output
     */
    public function decorateActions($m)
    {
        return 
            \HTML::link('/', 'Voir/Éditer', [
                'class'    => 'btn btn-success btn-sm',
                'disabled' => 'disabled',
            ]);
    }
    
    /**
     * created_at decorator
     * 
     * @param  Collection $m Query result row
     * @return String html output
     */
    protected function decorateCreatedAt($m)
    {
        return Carbon::parse($m->created_at)->diffForHumans();
    }
}
