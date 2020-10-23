<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRegisterController extends Controller
{
    public function show(){

        $user = Auth::user();

        // only for admin
        if($user->role !== ROLE['Admin']){
            return redirect()->route('user.dashboard');
        }

        return view('user.register');

    }

    public function store(){
        
    }
}
