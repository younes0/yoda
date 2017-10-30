<?php 

namespace Yoda\Http\Controllers\Auth;

use Yeb\Http\Controllers\PageController;
use Yoda\Models\User;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class AuthController extends PageController 
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
   
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/home';
   
    protected $redirectAfterLogout = '/auth/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     * 
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email'     => 'required|email|max:255|unique:users',
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
            'password'  => 'required|confirmed|min:6',
        ]);
    }

   /**
     * Create a new user instance after a valid registration.
     * 
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'email'     => $data['email'],
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'password'  => bcrypt($data['password']),
        ]);
    }

    /**
     * When User logouts
     *
     * @return redirect
     */
    public function getLogout()
    {
        \Auth::logout();
        
        \Alert::success('Vous êtes maintenant deconnecté')->flash();

        return redirect($this->redirectAfterLogout);
    }

    /**
     * Failed loging message
     *
     * @return String
     */
    protected function getFailedLoginMessage()
    {
        return 'Ces identifiants ne sont pas valides.';
    }
}
