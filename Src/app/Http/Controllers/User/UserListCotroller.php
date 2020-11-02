<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libs\Common;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserListCotroller extends Controller
{
    public function index(Request $request)
    {
        // dropdownlist items
        $locations = config('const.location');
        $departments = Department::all();

        // get parameter query string
        $conditions = $request->all();

        // make [where] filter
        $where = [];
        $whereUserNo = '1=1';
        if (isset($conditions['location'])) {
            $where[] = ['location', '=', $conditions['location']];
        }
        if (isset($conditions['department'])) {
            $where[] = ['department_id', '=', $conditions['department']];
        }
        if (isset($conditions['name'])) {
            $where[] = ['name', 'LIKE', '%' . trim($conditions['name']) . '%'];
        }
        if (isset($conditions['user_no'])) {
            $fillZero = config('const.num_fillzero');
            $whereUserNo = "(id LIKE '%{$conditions['user_no']}%' OR LPAD('{$conditions['user_no']}', {$fillZero}, '0') = LPAD(id, {$fillZero}, '0'))";
        }

        // get data
        $users = User::where($where)
            ->whereRaw($whereUserNo)
            ->orderBy('id')
            ->with('department')
            ->paginate(config('const.paginator.items'));

        return view('user.list', compact('conditions', 'locations', 'departments', 'users'));
    }

    public function delete(User $user)
    {
        $loggedUser = Auth::user();
        // do not delete your self
        if($user->id === $loggedUser->id) {
            return Common::redirectBackWithAlertFail(__('msg.delete_fail'));
        }
        $user = User::find($user->id);
        $user->updated_by = $loggedUser->id;
        $user->deleted_at = Carbon::now();
        $user->save();

        return Common::redirectBackWithAlertSuccess(__('msg.delete_success'));
    }
}
