<?php

namespace App\Http\Controllers\User;

use App\Libs\Common;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserEditController extends Controller
{
    /** Show user info
     *  Method - GET
     */
    public function show(Request $request, User $user)
    {
        // prepare compact data for view
        $data = User::getCompactData($user);

        return view('user.edit', compact('data'));
    }

    /** Update user info
     *  Method - POST
     */
    public function update(Request $request, User $user)
    {
        $inputs = $request->input();

        // fill inputs into user model
        $user->fill($inputs);
        $user->department_id = $inputs['department'];
        $user->updated_by = Auth::user()->id;

        // prepare compact data for view
        $data = User::getCompactData($user);

        // check validate before update
        $validator = User::makeValidator($inputs, $user, $data, true);

        // validate fail
        // if ($validator->fails()) {
        //     return Common::redirectViewWithAlertFail('user.edit', compact('data'))->withErrors($validator);
        // }

        $validator->validate();

        // update
        $user->save();

        return Common::redirectRouteWithAlertSuccess('admin.user.index');
    }
}
