<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index(Request $req){

        $userId = Auth::user()->id;
        $list_application =  Application::where('created_by', $userId)->orderBy('created_at', 'DESC')->get();

        //dd($list_application);
        return view('user.dashboard', compact('list_application'));
    }
}
