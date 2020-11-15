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
        //Get cookie
        $cooCode = $request->cookie('code');

        if (!$this->CheckCode(Auth::user()->otp_token, $cooCode)) {

            //Get number random
            $num = rand(1, 10000000);
            $hashnum = Hash::make($num);

            //Create Cookie
            $code = cookie('code', $hashnum, 1);

            //Save token to DB
            $users = Auth::user();
            $users->otp_token = $num;
            $users->save();

            //Send Code Mail
            $message = [
                'type' =>  __('label.checkip.mail_content'),
                'task' =>  __('label.checkip.mail_the_code_is'),
                'content' => $num,
            ];
            SendEmail::dispatch($message)->delay(now()->addMinute(1));

            return response()->view('auth.checkip')->withCookie($code);
        } else {

            return response()->view('auth.checkip')->withCookie($cooCode);
        }
    }

    public function confirm(Request $request)
    {
        $data = $request->input();
        //Get cookie
        $cooCode = $request->cookie('code');

        //Check Validation
        $validator = Validator::make($data, [
            'code'   => 'required',
        ],);

        //Check Code
        $validator->after(function ($validator) use ($data, $cooCode) {
            if (!$this->CheckCode(trim($data['code']), $cooCode)) {
                $validator->errors()->add('code', __('label.checkip.valid_not_compare'));
            }
        });
        $validator->validate();

        //Create Cookie -> Confirm Success
        $confirm = cookie('confirm', $cooCode, 180);

        if (Gate::allows('admin-gate')) {
            return redirect()->route('admin.dashboard', config('const.application.status.all'))->withCookie($confirm);
        }
        // for user
        return redirect()->route('user.dashboard', config('const.application.status.all'))->withCookie($confirm);
    }

    private function CheckCode($code, $hashcode)
    {
        //Hash Check
        if (Hash::check($code, $hashcode)) {
            return true;
        } else {
            return false;
        }
    }
}
