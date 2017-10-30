<?php

namespace Yoda\Http\Controllers\Open;

use Yeb\Http\Controllers\PageController;
use Request, Response, Alert, Auth;

class LandingController extends PageController
{
    /**
     * Public landing page for Yoda
     *
     * @return view
     */
    public function getIndex()
    {
        return redirect('home');
    }
}
