<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class UserChangePassController extends Controller
{
    /** Show change-password form
     *  Method - GET
     */
    public function show(Request $request){
        return view('user.changepass');
    }

    /** Update new password
     *  Method - PUT
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
                'confirmed'
            ]
        ];

        // validate
        $validator = Validator::make($request->input(), $rules);
        $validator->validate();

        // update new password
        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($newPassword);
        
        $user->save();

        Auth::user()->password = $user->password;

        return redirect()->back();
    }
}
