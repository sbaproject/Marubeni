<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Libs\Common;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        // get data inputs
        $data = $request->input();

        $validator = Validator::make($data, [
            'name'   => 'required|unique:companies',
            'country'   => 'required',
            'phone'   => 'required|numeric',
            'address'   => 'required',
            'attendants_name'   => 'required',
            'attendants_department'   => 'required',
            'email' => 'required|email:rfc,dns',
        ],);

        $validator->validate();

        //save
        $company = new Company();
        $company->created_by = Auth::user()->id;
        $company->fill($data)->save();

        return Common::redirectRouteWithAlertSuccess('admin.company.index');
    }

    public function show(Company $company)
    {
        $idcompany = $company->id;

        return view('admin.company_edit', compact('company', 'idcompany'));
    }

    public function update($id, Request $request)
    {
        // get data inputs
        $data = $request->input();

        $company = Company::find($id);

        // Check Companay'sName exists!
        $ruleName = ['required'];
        $ruleName[] = Rule::unique('companies')->ignore($company->name, 'name');

        $validator = Validator::make($data, [
            'name'   => $ruleName,
            'country'   => 'required',
            'phone'   => 'required|numeric',
            'address'   => 'required',
            'attendants_name'   => 'required',
            'attendants_department'   => 'required',
            'email' => 'required|email:rfc,dns',
        ],);

        $validator->validate();

        //save
        $company->fill($data);
        $company->updated_by = Auth::user()->id;
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
