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

class AdminDepartmentController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        
        $list_department = Department::where('role', 0)->orderBy('id', 'DESC')->paginate(config('const.paginator.items'));

        return view('admin_department_index', compact('list_department'));
    }

    public function create()
    {
        return view('admin_department_create');
    }

    public function store(Request $request)
    {
        // get data inputs
        $data = $request->input();

        $validator = Validator::make($data, [
            'name'   => 'required|unique:departments',
        ],);

        $validator->validate();

        //save
        $department = new Department();
        $department->created_by = Auth::user()->id;
        $department->role = 0;
        $department->fill($data)->save();

        return Common::redirectRouteWithAlertSuccess('admin.department.index');
    }

    public function show(Department $department)
    {
        $iddepartment = $department->id;

        return view('admin_department_show', compact('department', 'iddepartment'));
    }

    public function update($id, Request $request)
    {
        // get data inputs
        $data = $request->input();

        $department = Department::find($id);

        // Check Companay'sName exists!
        $ruleName = ['required'];
        $ruleName[] = Rule::unique('departments')->ignore($department->name, 'name');

        $validator = Validator::make($data, [
            'name'   => $ruleName
        ],);

        $validator->validate();

        //save
        $department->fill($data);
        $department->updated_by = Auth::user()->id;
        $department->save();

        return Common::redirectRouteWithAlertSuccess('admin.department.index');
    }

    public function delete($id)
    {
        $applicant_count = Applicant::where('department_id', $id)->count();

        if ($applicant_count > 0) {
            return Common::redirectBackWithAlertFail(__('msg.delete_department_valid'));
        }
        $department = Department::find($id);
        $department->delete();
        return Common::redirectBackWithAlertSuccess(__('msg.delete_success'));
    }
}
