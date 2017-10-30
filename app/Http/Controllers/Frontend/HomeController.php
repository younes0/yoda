<?php

namespace Yoda\Http\Controllers\Frontend;

use Yeb\Http\Controllers\PageController;
use Request, Response, Alert, Auth;

class HomeController extends PageController
{
    public function getIndex()
    {   
        return view('frontend.home');
    }
}
