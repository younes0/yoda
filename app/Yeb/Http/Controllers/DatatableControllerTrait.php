<?php

namespace Yeb\Http\Controllers;

trait DatatableControllerTrait
{
    /**
     * Datatable Helper
     * @var \Yskel\Datatable\CompaniesDatatable
     */
    protected $datatable;
    
    /**
     * Datatable json data called by Javascript plugin
     *
     * @return json
     */ 
    public function getDatatable()
    {
        return $this->datatable->getData();
    }
}
