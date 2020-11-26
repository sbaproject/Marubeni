<?php

namespace App\Http\Controllers\Error;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErrorPageController extends Controller
{
    public function notfound(){
        return view('errors.404');
    }
    public function forbidden()
    {
        return view('errors.403');
    }
}
