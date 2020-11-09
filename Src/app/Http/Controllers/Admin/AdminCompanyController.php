<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Libs\Common;
use Carbon\Carbon;

class AdminCompanyController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $data = $request->input();
        $req_arr = $request->all();

        if (!empty($data['company_name']) or !empty($data['company_att_name']) or !empty($data['company_keyword'])) {
            $list_company = Company::query();

            if (!empty($data['company_name'])) {
                $list_company = $list_company->where('name', 'like', '%' . $data['company_name'] . '%');
            }
            if (!empty($data['company_att_name'])) {
                $list_company = $list_company->where('attendants_name', 'like', '%' . $data['company_att_name'] . '%');
            }

            if (!empty($data['company_keyword'])) {
                $list_company = $list_company->where(function ($query) use ($data) {
                    $query->orwhere('name', 'like', '%' . $data['company_keyword'] . '%')
                        ->orwhere('address', 'like', '%' . $data['company_keyword'] . '%')
                        ->orwhere('phone', 'like', '%' . $data['company_keyword'] . '%')
                        ->orwhere('attendants_name', 'like', '%' . $data['company_keyword'] . '%')
                        ->orwhere('attendants_department', 'like', '%' . $data['company_keyword'] . '%');
                });
            }

            $list_company = $list_company->whereNull('deleted_at')->orderBy('id', 'DESC')->paginate(5);
        } else {

            $list_company = Company::whereNull('deleted_at')->orderBy('id', 'DESC')->paginate(5);
        }

        return view('admin.company', compact('list_company', 'req_arr'));
    }

    public function create()
    {
        return view('admin.company_new');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'com_name'   => 'required',
            'com_country'   => 'required',
            'com_tel'   => 'required',
            'com_address'   => 'required',
            'att_name'   => 'required',
            'att_department'   => 'required',
            'att_mail' => 'required|email:rfc,dns',
        ],);
        // get data inputs
        $data = $request->input();

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

        return Common::redirectRouteWithAlertSuccess('admin.company.index');
    }
}
