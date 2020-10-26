<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserListCotroller extends Controller
{
    public function show(Request $request)
    {
        // dropdownlist items
        $locations = config('const.location');
        $departments = Department::all();

        // search conditions
        $conditions = $request->all();

        // make [where] filter
        $where = [];
        if (isset($conditions['location'])) {
            $where[] = ['location', '=', $conditions['location']];
        }
        if (isset($conditions['department'])) {
            $where[] = ['department_id', '=', $conditions['department']];
        }
        if (isset($conditions['name'])) {
            $where[] = ['name', 'LIKE', '%' . trim($conditions['name']) . '%'];
        }

        // get data
        $users = User::where($where)
            ->orderBy('id')
            ->orderBy('name')
            ->paginate(config('const.paginator.items'));

        return view('user.list', compact('conditions', 'locations', 'departments', 'users'));
    }
}
