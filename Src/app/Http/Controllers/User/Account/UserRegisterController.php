<?php

namespace App\Http\Controllers\User\Account;

use App\Libs\Common;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRegisterController extends Controller
{
    public function create()
    {
        $data = User::getCompactData();

        return view('user.account.create', compact('data'));
    }

    public function store(Request $request)
    {
        // get data inputs
        $inputs = $request->input();

        // check validate before save
        $validator = User::makeValidator($inputs);

        // if fail will return to view
        $validator->validate();

        // save new user
        $user = new User();
        $user->user_no              = User::makeUserNoByAutoIncrementId();
        $user->department_id        = $inputs['department'];
        $user->password             = Hash::make('123'); // temp data
        $user->leave_days           = config('const.annual_leave_days_per_year');
        $user->leave_remaining_days = config('const.annual_leave_days_per_year');
        $user->created_by           = Auth::user()->id;

        $user->fill($inputs)->save();

        return Common::redirectRouteWithAlertSuccess('admin.user.index');
    }
}
