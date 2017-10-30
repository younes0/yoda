<?php 

namespace Yoda\Http\Controllers\Auth;

use Yeb\Http\Controllers\PageController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends PageController 
{
    use ResetsPasswords;

    /**
     * Default redirecting route after logging
     * @var string
     */
    protected $redirectTo = '/users';

    /**
     * Create a new password controller instance.
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Auth\PasswordBroker  $passwords
     * @return void
     */
    public function __construct(Guard $auth, PasswordBroker $passwords)
    {
        parent::__construct();
        $this->auth = $auth;
        $this->passwords = $passwords;

        $this->middleware('guest');
    }
}
