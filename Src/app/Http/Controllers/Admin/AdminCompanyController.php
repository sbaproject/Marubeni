<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Libs\Common;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

            $list_company = $list_company->orderBy('id', 'DESC')->paginate(5);
        } else {

            $list_company = Company::orderBy('id', 'DESC')->paginate(5);
        }

        return view('admin.company', compact('list_company', 'req_arr'));
    }

    public function create()
    {
        $idcompany = DB::table('INFORMATION_SCHEMA.TABLES')->select('AUTO_INCREMENT')->where('TABLE_NAME', 'companies')->get()[0]->AUTO_INCREMENT;

        return view('admin.company_new', compact('idcompany'));
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

        // $validator->after(function ($validator) {
        //     if (0 == 0) {
        //         $validator->errors()->add('com_name', 'Something is wrong with this field!');
        //     }
        // });

        if ($validator->fails()) {
            return redirect('admin/company/add')
                        ->withErrors($validator)
                        ->withInput();
        }

        // $validator->after(function ($validator) {
        //     if ($this->somethingElseIsInvalid()) {
        //         $validator->errors()->add('field', 'Something is wrong with this field!');
        //     }
        // });


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

    public function show(Company $company)
    {
        $idcompany = $company->id;

        return view('admin.company_edit',compact('company','idcompany'));
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
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

        $company = Company::find($data['id']);

        $company->name                      = $data['com_name'];
        $company->country                   = $data['com_country'];
        $company->phone                     = $data['com_tel'];
        $company->address                   = $data['com_address'];
        $company->attendants_name           = $data['att_name'];
        $company->attendants_department     = $data['att_department'];
        $company->email                     = $data['att_mail'];
        $company->memo                      = $data['text_content'];
        $company->updated_by                = Auth::user()->id;
        $company->updated_at                = Carbon::now();
        $company->save();

        return Common::redirectRouteWithAlertSuccess('admin.company.index');
    }

    public function delete($id)
    {
        $company = Company::find($id);
        $company->delete();
        return Common::redirectBackWithAlertSuccess(__('msg.delete_success'));
    }
}
