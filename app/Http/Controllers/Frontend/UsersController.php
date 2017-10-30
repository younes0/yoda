<?php

namespace Yoda\Http\Controllers\Frontend;

use Yeb\Http\Controllers\PageController;
use Yeb\Http\Controllers\DatatableControllerTrait;
use Request, Response, Alert, Auth;

class UsersController extends PageController
{
    use DatatableControllerTrait;

    /**
     * Init UsersDatatable object used in getIndex() and getDatatable()
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->datatable = new \Yoda\Datatable\UsersDatatable();
    }

    /**
     * Users list with datatable
     *
     * @return view
     */    
    public function getIndex()
    {
        // merge previous jsVars with new ones
        $this->jsVars['columns'] = $this->datatable->getColumns();

        return view('frontend.users');
    }
}
