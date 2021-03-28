<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use App\Libs\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserCompanyController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        // $idcompany = DB::table('INFORMATION_SCHEMA.TABLES')->select('AUTO_INCREMENT')->where('TABLE_NAME', 'companies')->get()[0]->AUTO_INCREMENT;

        return view('user.company.create');
    }

    public function store(Request $request)
    {
        // get data inputs
        $data = $request->input();

        $validator = Validator::make($data, [
            'name'                  => 'required|unique:companies',
            'country'               => 'required',
            'phone'                 => 'required|numeric',
            'address'               => 'required',
            'attendants_name'       => 'required',
            'attendants_department' => 'required',
            'email'                 => 'required|email:rfc,dns',
        ],);

        $validator->validate();

        //save
        $company = new Company();
        $company->created_by = Auth::user()->id;
        $company->fill($data)->save();

        return Common::redirectBackWithAlertSuccess(__('msg.save_success'));
    }
}
