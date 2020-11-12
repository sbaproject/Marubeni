<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use App\Libs\Common;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserCompanyController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        $idcompany = DB::table('INFORMATION_SCHEMA.TABLES')->select('AUTO_INCREMENT')->where('TABLE_NAME', 'companies')->get()[0]->AUTO_INCREMENT;

        return view('user.company', compact('idcompany'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'com_name'   => 'required',
            'com_country'   => 'required',
            'com_tel'   => 'required|numeric',
            'com_address'   => 'required',
            'att_name'   => 'required',
            'att_department'   => 'required',
            'att_mail' => 'required|email:rfc,dns',
        ],);
        // get data inputs
        $data = $request->input();

        // Check Company'sName exists !
        $checkexist_com_name = Company::where('name', $data['com_name'])->count();

        $validator->after(function ($validator) use ($checkexist_com_name) {
            if ($checkexist_com_name > 0) {
                $validator->errors()->add('com_name', __('label.company.check_exist_com_name'));
            }
        });

        if ($validator->fails()) {
            return redirect('user/company/add')
                ->withErrors($validator)
                ->withInput();
        }

        $dataCompany = new Company([
            'name'                      => $data['com_name'],
            'country'                   => $data['com_country'],
            'phone'                     => $data['com_tel'],
            'address'                   => $data['com_address'],
            'attendants_name'           => $data['att_name'],
            'attendants_department'     => $data['att_department'],
            'email'                     => $data['att_mail'],
            'memo'                      => $data['text_content'],
            'created_by'                => Auth::user()->id,
            'created_at'                => Carbon::now()
        ]);

        $dataCompany->save();

        return Common::redirectBackWithAlertSuccess(__('msg.save_success'));
    }
}
