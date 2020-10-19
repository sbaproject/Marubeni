<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function show(){
        $user = new User();
        $user->email = 'user1@gmail.com';
        $user->password = '123';
        $user->name = 'user1';
        $user->department_id = 1;
        $user->location = 1;
        $user->save();
        return view('auth.register');
    }
}
