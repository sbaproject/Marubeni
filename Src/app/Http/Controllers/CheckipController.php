<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Hash;

class CheckipController extends Controller
{
    public function index(Request $request)
    {

        $value = $request->cookie('code');

        if (empty($value)) {

            //Get number random
            $num = rand(1, 1000000);
            $hashnum = Hash::make($num);

            //Set Cookie
            $code = cookie('code', $hashnum, 1);

            //Save token
            $users = Auth::user();
            $users->otp_token = $num;
            $users->save();

            $message = [
                'type' => 'Xác nhận đang ngoài mạng',
                'task' => $num,
                'content' => ':là mã xác nhận!',
            ];
            SendEmail::dispatch($message);

            return response()->view('auth.checkip')->withCookie($code);
        } else {

            return response()->view('auth.checkip');
        }
    }

    public function confirm(Request $request)
    {
        $data = $request->input();

        $cooCode = $request->cookie('code');

        if (!empty($cooCode)) {

            if (Hash::check($data['code'], $cooCode)) {

                $confirm = cookie('confirm', $cooCode, 180);

                if (Gate::allows('admin-gate')) {
                    return redirect()->route('admin.dashboard', config('const.application.status.all'))->withCookie($confirm);
                }
                // for user
                return redirect()->route('user.dashboard', config('const.application.status.all'))->withCookie($confirm);
            }
        }
        return redirect()->route('checkip');
    }
}
