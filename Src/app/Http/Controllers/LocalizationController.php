<?php

namespace App\Http\Controllers;

use App\Libs\Common;
use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    public function index($lang){
        Common::setLocale();
    }
}
