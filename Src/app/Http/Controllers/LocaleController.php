<?php

namespace App\Http\Controllers;

use App\Libs\Common;

class LocaleController extends Controller
{
    public function __invoke($locale)
    {
        Common::setLocale($locale);
        return redirect()->back();
    }
}
