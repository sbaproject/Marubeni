<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserEditController extends Controller
{
    /** Show user info
     *  Method - GET
     */
    public function show(Http $request, User $user){
        return view('user.edit');
    }

    /** Update user info
     *  Method - PUT
     */
    public function update(Http $request, User $user){
        
    }
}
