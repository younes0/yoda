<?php

namespace Yoda\Http\Controllers\Frontend;

use Yeb\Http\Controllers\PageController;
use Request, Response, Alert, Auth;
use Yoda\Models\Host;

class HostController extends PageController
{
    public function __construct()
    {
        parent::__construct();

        $url = Request::get('url');
        $this->host = Host::firstOrCreateFromUrl($url); 
    }

    public function anyIndex()
    {
        $action = Request::route('action');

        $actions = [
            'ban'     => 'is_banned',
            'ignore'  => 'is_ignored',
            'trust'   => 'is_trusted',
            'paywall' => 'can_have_paywall',
        ];

        $this->host->update([$actions[$action] => 1]);

        \Alert::success('Opération éffectuée')->flash();

        return back();
    }
}
