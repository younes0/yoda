<?php

namespace Yoda\Libraries;

use Carbon\Carbon;
use Session;

class View
{    
    /**
     * Returns 'active' if passed $route is current
     * Usage: <li active="{!! YodaView::liActive('my-route') !!}"> instead of
     * <li class="{!!  \Request::is('my-route') ? 'active' : null !!}">      * 
     * @param  String $route route
     * @return String generated html
     */
    static public function liActive($route)
    {
        return \Request::is($route) ? 'active' : null;
    }
}
