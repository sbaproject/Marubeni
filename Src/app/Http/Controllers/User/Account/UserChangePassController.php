<?php

namespace App\Http\Controllers\User\Account;

use App\Libs\Common;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserChangePassController extends Controller
{
    /** Show change-password form
     *  Method - GET
     */
    public function show(Request $request){
        return view('user.account.changepass');
    }

    /** Update new password
     *  Method - POST
     */
    public function update(Request $request){

        $currentPassword = $request->current_password;
        $newPassword = $request->new_password;
        $checkCurrentPwd = false;

        if(isset($currentPassword)){
            $checkCurrentPwd = Hash::check($currentPassword, Auth::user()->password);
        }

        // validation rules
        $rules = [
            'current_password' => [
                'required',
                // check current password
                function($attribute, $value, $fail) use ($checkCurrentPwd){
                    if(!$checkCurrentPwd){
                        return $fail(__("validation.change_pass.current_incorrect"));
                    }
                }
            ],
            'new_password' => [
                'required',
                // check same current password
                function($attribute, $value, $fail) use ($checkCurrentPwd, $currentPassword) {
                    // check current password is OK
                    if($checkCurrentPwd){
                        // current password and new password are the same
                        if($currentPassword == $value){
                            return $fail(__('validation.change_pass.new_same_current'));
                        }
                    }
                },
                'min:8',
                'max:20',
                'regex:/^[a-zA-Z0-9_@.#&+%!-]+$/',
                // 'confirmed'
            ],
            'confirm_new_password' => [
                'required',
                'same:new_password',
            ]
        ];

        // validate
        $validator = Validator::make($request->input(), $rules);
        $validator->validate();

        // update new password
        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($newPassword);
        $user->updated_by = Auth::user()->id;
        
        $user->save();

        Auth::user()->password = $user->password;

        return Common::redirectBackWithAlertSuccess();
    }
}
