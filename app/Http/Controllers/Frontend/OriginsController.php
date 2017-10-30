<?php

namespace Yoda\Http\Controllers\Frontend;

use Yeb\Http\Controllers\PageController;
use Yeb\Http\Controllers\DatatableControllerTrait;
use Request, Response, Alert, Auth;

class OriginsController extends PageController
{
    use DatatableControllerTrait;

    /**
     * Init UsersDatatable object used in getIndex() and getDatatable()
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->datatable = new \Yoda\Datatable\OriginsDatatable();
    }

    /**
     * Users list with datatable
     *
     * @return view
     */    
    public function getIndex()
    {
        $this->jsVars['columns'] = $this->datatable->getColumns();
        $this->data['title']     = 'Origins';

        return view('frontend.datatable');
    }
}
