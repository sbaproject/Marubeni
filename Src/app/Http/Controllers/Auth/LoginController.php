<?php

namespace App\Http\Controllers\Auth;

use App\Libs\Common;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * login too many attempts
     */
    protected $maxAttempts = 6;
    protected $decayMinutes = 5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * condtions to get user login
     */
    protected function credentials($request)
    {
        $credentials = $request->only($this->username(), 'password');
        // add more conditions here
        // $credentials['filed_here'] = 'value_here';
        return $credentials;
    }

    /**
     * redirect after login successfuly
     */
    protected function authenticated($request, $user)
    {
        // return redirect('/');
        return Common::redirectHome();
    }

    /**
     * redirect after logged-out successfuly
     */
    protected function loggedOut($request)
    {
        // default redirect to '/'
        // if want to redirect to another routes, change here.
        // return redirect()->intended();
        return redirect()->route('login');
    }
}
