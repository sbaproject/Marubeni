<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;

class UserDashboardController extends Controller
{
    public function index(Request $req){

        $list_application =  Application::all();
        //dd($list_application);
        return view('user.dashboard', compact('list_application'));
    }
}
