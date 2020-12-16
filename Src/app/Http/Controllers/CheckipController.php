<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CheckipController extends Controller
{
    public function index(Request $request)
    {
        if (!Hash::check($request->cookie('code'), Auth::user()->otp_token)) {

            //Get number random
            $num = rand(1, 10000000);
            $hashnum = Hash::make($num);

            //Create Cookie
            $code = cookie('code', $num, 10);

            //Save token to DB
            $users = Auth::user();
            $users->otp_token = $hashnum;
            $users->save();

            //Send Code Mail
            $message = [
                'type' =>  __('label.checkip.mail_content'),
                'task' =>  __('label.checkip.mail_the_code_is'),
                'content' => $num,
            ];
            SendEmail::dispatch($message)->delay(now()->addMinute(1));

            //dd(1);
            return response()->view('auth.checkip')->withCookie($code);
        } else {

            return response()->view('auth.checkip')->withCookie($request->cookie('code'));
        }
    }

    public function confirm(Request $request)
    {
        $data = $request->input();

        //Check Validation
        $validator = Validator::make($data, [
            'code'   => 'required',
        ],);

        //Check Code
        $validator->after(function ($validator) use ($data, $request) {
            if (!Hash::check(trim($data['code']), Auth::user()->otp_token) && !empty($request->cookie('code'))) {
                $validator->errors()->add('code', __('label.checkip.valid_not_compare'));
            }
            if (!(trim($data['code']) == $request->cookie('code'))) {
                $validator->errors()->add('code', __('label.checkip.valid_expired'));
            }
        });

        $validator->validate();

        //Create Cookie -> Confirm Success
        $confirm = cookie('confirm', $request->cookie('code'), 180);

        if (Gate::allows('admin-gate')) {
            return redirect()->route('admin.dashboard')->withCookie($confirm);
        }
        // for user
        return redirect()->route('user.dashboard')->withCookie($confirm);
    }
}
