<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class UserEditController extends Controller
{
    /** Show user info
     *  Method - GET
     */
    public function show(Request $request, User $user){
        return view('user.edit',compact('user'));
    }

    /** Update user info
     *  Method - PUT
     */
    public function update(Request $request, User $user){

        $data = $request->input();

        $user->fill($data);

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id, 'id')]
        ]);
        
        if($validator->fails()){
            return view('user.edit', compact('user'))->withErrors($validator);
        }

        $user->save();

        return redirect()->back();
    }
}
