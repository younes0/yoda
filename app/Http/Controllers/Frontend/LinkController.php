<?php

namespace Yoda\Http\Controllers\Frontend;

use Yeb\Http\Controllers\PageController;
use Request, Response, Alert, Auth;
use Yoda\Models\Link;

class LinkController extends PageController
{
    protected $link;

    public function __construct()
    {
        parent::__construct();

        $this->link = Link::findOrFail(Request::route('link_id'));
    }

    public function anyApprove($id, $value)
    {
        $this->link->approve($value === 'true');
        
        Alert::success($id.' mis à jour')->flash();

        return back();
    }
}
