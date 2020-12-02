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
        $user->department_id = $inputs['department'];
        $user->password = Hash::make('123'); // temp data
        $user->created_by = Auth::user()->id;

        $user->fill($inputs)->save();

        return Common::redirectRouteWithAlertSuccess('admin.user.index');
    }
}
