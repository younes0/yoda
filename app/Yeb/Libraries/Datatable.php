<?php

namespace Yeb\Libraries;

use DB;

abstract class Datatable
{
    /**
     * Columns for the Datatable
     * Array properties available (datatable convention): 
     * `data`: (String) query result column
     * `title`: (String) if missing, column title takes data name
     * `class`: (String) cell (td) html class
     * `sortable`: (Boolean) enable/disable sorting for column
     * Example: [[ 'data' => 'created_at', 'title' => 'Créé le', 'sortable' => false ],[ ... ]]
     * @var array
     */
    protected $columns;
     
    /**
     * $hasActions determines if a 'Action(s)' column should be added 
     * To add content to 'Action(s)' cells, use decorateActions (see below for explanations
     * @var boolean
     */
    protected $hasActions = false;

    /**
     * Query before Datatable transformation
     * @var Array
     */
    protected $query;

    /**
     * constructor
     */
    public function __construct()
    {
        // adds actions column to columns if $hasActions is set to true
        if ($this->hasActions) {
            array_unshift($this->columns, [ 
                'data'     => 'actions', 
                'class'    => 'actions', 
                'title'    => '', 
                'sortable' => false,
            ]);
        }
    }
    
    /**
     * Returns all data after decoration & Chumper\Datatable\Datatable wrapping
     * Example in Child class:    
     * public function getData()
     * {
     *     $columns = array_pluck($this->columns, 'data');
     *
     *     $this->query = DB::table('users AS u');
     *
     *    // decorates data & and use data to build and return Yajra\Datatables object
     *     return $this->addDecorators(Datatables::of($this->query))
     *         ->smart(true)
     *         ->escapeColumns([])
     *         ->make(true);
     * }
     * 
     * @return array Chumper\Datatable\Datatable data array
     */
    abstract public function getData();

    /**
     * Returns all columns
     * Usage in Javascript Datatables
     * @return array columns
     */
    public function getColumns()
    {
        $this->addTitles();
        
        return $this->columns;
    }

    /**
     * Returns Query, useful for test
     * @return Array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Add columns decorators (change column raw content to proper html/string output)
     * see getData() doc
     * @param Chuper/Datatable $datatable [description]
     */
    protected function addDecorators($datatable)
    {    
        $class   = new \ReflectionClass($this);
        $methods = $class->getMethods();

        foreach (array_pluck($methods, 'name') as $method) {
            // get decorate* methods            
            if (\Str::startsWith($method, 'decorate')) {
                
                // define column name based on method
                $column = \Str::snake(str_replace('decorate', null, $method));

                $datatable->addColumn($column, function($m) use ($method, $column) {

                    // check encode value
                    $array = array_first($this->columns, function($key, $value) use ($column) {
                        return $value['data'] === $column;
                    });
                
                    return $this->$method($m);
                });
            }
        }

        return $datatable;
    }

    /**
     * Adds columns titles (fetch from $this->columns) to final json output
     * @param array $columns 
     * @return [type] [description]
     */
    protected function addTitles()
    {
        foreach ($this->columns as &$column) {
            if ( !isset($column['title']) ) {
                $column['title'] = $column['data'];
            }
        }

        return $this;
    }
}
