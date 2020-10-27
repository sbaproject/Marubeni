<?php

namespace App\Http\Controllers\User;

use App\Libs\Common;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserRegisterController extends Controller
{
    public function show()
    {
        $locations = config('const.location');
        $roles = config('const.role');
        $approvals = config('const.approval');
        $departments = Department::all();

        return view('user.register', compact('locations', 'roles', 'departments', 'approvals'));
    }

    public function store(Request $request)
    {
        $data = $request->input();

        $validator = Validator::make($data, [
            'location' => ['required', Rule::in(config('const.location'))],
            'department' => 'required',
            'name' => 'required',
            'role' => ['required', Rule::in(config('const.role'))],
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|phone_number',
            'approval' => ['required', Rule::in(config('const.approval'))],
        ]);

        $validator->validate();

        $user = new User();
        $user->department_id = $data['department'];
        $user->password = Hash::make('123'); // temp data

        $user->fill($data)->save();

        return Common::redirectBackWithAlertSuccess();
    }
}
