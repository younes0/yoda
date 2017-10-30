<?php

namespace Yoda\Listeners;

use Yoda\Events;
use Yoda\Models\User;
use M6Web\Component\Firewall\Firewall;
use DB, Request, Auth;

class AuthEventListener
{
    /**
     * Handler the Auth.Login event: check IP if User logged as an Admin
     * 
     * @param  User   $user     
     * @param  String $remember
     * @return void
     */
    public function onUserLogin(User $user, $remember)
    {
        if ( !$user->is_admin || env('APP_DEBUG')) {
            return;
        }

        // setup firewall
        $firewall = (new Firewall())
            ->setDefaultState(false)
            ->setIpAddress(Request::ip());

        // retrieve whitelist/blacklist and add them to firewall
        foreach (['white', 'black'] as $type) {
            $entries = DB::table('firewall_entries')
                ->where('type', $type)
                ->lists('entry');

            if ( !empty($entries) ) {
                $value = ($type === 'white');
                $firewall->addList($entries, $type, $value);
            }
        }

        // if not allowed, logout
        if ( !$firewall->handle() ) {
            Auth::logout();
            return redirect('/auth/login');
        } 

        \Alert::success('Bonjour Administrateur')->flash();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen(
            'auth.login', 
            'Yoda\Listeners\AuthEventListener@onUserLogin'
        );
    }
}
