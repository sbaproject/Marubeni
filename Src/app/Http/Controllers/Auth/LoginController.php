<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use ThrottlesLogins;

    protected $maxAttempts = 2; // Default is 5

    protected $decayMinutes = 1; // Default is 1

    public function username(){
        return 'email';
    }

    public function show()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $rules = [
            'email' => 'required|email',
            'password' => 'required|string',
        ];

        $credentials = $request->only('email', 'password');

        // validate input
        $validator = Validator::make($credentials, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $isLogin = Auth::attempt($credentials, $request->filled('remember'));
        // login fail
        if (!$isLogin) {
            $this->incrementLoginAttempts($request);
            return redirect()->back()->withErrors(
                [
                    'login-fail' => 'Oppes! You have entered invalid credentials'
                ]
            )->withInput();
        }

        // clear login attempts
        $this->clearLoginAttempts($request);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        // remove all session data
        Session::flush();
        // logout
        Auth::logout();
        return back();
    }
}
