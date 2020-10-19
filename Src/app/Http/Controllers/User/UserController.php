<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        // $user = User::where('id','=',1)->update(['name' => 'ssss']);

        // $user = User::where('password','=','123')->delete();

        // $user = User::find(1)->delete();

        // $user = User::find(1);

        // $user->password = 456;

        // $user = User::firstOrCreate(['id'=> 3]);

        // $user->name = 'abscwwws';

        // $user = User::findOrCreate()

        // $user->delete();

        // dd($user);

        // saving
        // $user->save();

        // creating
        // $user->update();
        return view('welcome');
    }

}
