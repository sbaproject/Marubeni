<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\Applicant;
use App\Libs\Common;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminApplicantController extends Controller
{
    public function index(Request $request)
    {
        $list_applicant = DB::table('applicants')
            ->join('departments', 'departments.id', '=', 'applicants.department_id')            
            ->select('applicants.id','applicants.location', 'applicants.role', 'applicants.department_id', 'departments.name')
            ->orderBy('applicants.id', 'desc')
            ->whereNull('applicants.deleted_at')
            ->whereNull('departments.deleted_at')            
            ->paginate(config('const.paginator.items'));

        $locations = config('const.location');
        $roles = config('const.role');     

        return view('admin_applicant_index', compact('list_applicant', 'locations', 'roles'));
    }

    public function create()
    {
        $data = [
            'locations' => config('const.location'),
            'roles' => config('const.role'),
            'departments' => Department::where('role', 0)->get(),
        ];

        return view('admin_applicant_create', compact('data'));
    }

    public function store(Request $request)
    {
        // get data inputs
        $data = $request->input();


        // Check Applicant exists!
        $ruleName = ['required'];
        $ruleName[] = Rule::unique('applicants')->where('location', $data['location'])->where('department_id', $data['department_id'])->where('role', $data['role']);

        $validator = Validator::make($data, [
            'location'   => 'required',
            'department_id'   => 'required',
            'role' => $ruleName
        ],);

        $validator->validate();

        //save
        $applicant = new Applicant();
        $applicant->created_by = Auth::user()->id;
        $applicant->fill($data)->save();

        return Common::redirectRouteWithAlertSuccess('admin.applicant.index');
    }

    public function show(Applicant $applicant)
    {
        $idapplicant = $applicant->id;

        $data = [
            'locations' => config('const.location'),
            'roles' => config('const.role'),
            'departments' => Department::where('role', 0)->get(),
        ];

        return view('admin_applicant_show', compact('applicant', 'idapplicant', 'data'));
    }

    public function update($id, Request $request)
    {
        // get data inputs
        $data = $request->input();

        $applicant = Applicant::find($id);

        // Check Applicant exists!
        $ruleName = ['required'];
        $ruleName[] = Rule::unique('applicants')->ignore($applicant)->where('location', $data['location'])->where('department_id', $data['department_id'])->where('role', $data['role']);

        $validator = Validator::make($data, [
            'location'   => 'required',
            'department_id'   => 'required',
            'role' => $ruleName
        ],);

        $validator->validate();

        //save
        $applicant->fill($data);
        $applicant->updated_by = Auth::user()->id;
        $applicant->save();

        return Common::redirectRouteWithAlertSuccess('admin.applicant.index');
    }

    public function delete($id)
    {
        $group = DB::table('groups')->where('applicant_id', $id)->whereNull('deleted_at')->first();

        if (!empty($group)) {
            return Common::redirectBackWithAlertFail(__('msg.delete_applicant_valid'));
        }
        $applicant = Applicant::find($id);
        $applicant->delete();
        return Common::redirectBackWithAlertSuccess(__('msg.delete_success'));
    }
}
