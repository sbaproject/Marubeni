<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormListController extends Controller
{
    public function index()
    {
        return view('application_formlist');
    }
}
