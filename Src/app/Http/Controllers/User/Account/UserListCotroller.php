<?php

namespace App\Http\Controllers\User\Account;

use Carbon\Carbon;
use App\Libs\Common;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserListCotroller extends Controller
{
    public function index(Request $request)
    {
        // dropdownlist items
        $locations = config('const.location');
        $departments = Department::all();

        // get parameter query string
        $conditions = $request->input();

        // search conditions
        $where = [];
        if (isset($conditions['location'])) {
            $where[] = ['users.location', '=', $conditions['location']];
        }
        if (isset($conditions['department'])) {
            $where[] = ['users.department_id', '=', $conditions['department']];
        }
        if (isset($conditions['name'])) {
            $where[] = ['users.name', 'LIKE', '%' . trim($conditions['name']) . '%'];
        }
        if (isset($conditions['user_no'])) {
            $fillZero = config('const.num_fillzero');
            $where[] = [DB::raw("LPAD(users.id,{$fillZero}, '0')"), "LIKE",  '%' . $conditions['user_no'] . '%'];
        }

        // sorting columns
        $sortableCols = [
            'user_number'       => __('label._no_'),
            'department_name'   => __('validation.attributes.department'),
            'user_name'         => __('validation.attributes.user.name'),
        ];
        $sortable = Common::getSortable($request, $sortableCols, 0, 0, true);

        // selection columns
        $userNo = "LPAD(users.id, " . config('const.num_fillzero') . ", '0')";
        $selectCols = [
            'users.id           as user_id',
            DB::raw($userNo . " as user_number"),
            'users.name         as user_name',
            'departments.name   as department_name',
        ];

        // get results
        $users = User::join('departments', function ($join) {
            $join->on('users.department_id', '=', 'departments.id')
                ->where('departments.deleted_at', null);
        })
            ->where($where)
            ->orderByRaw($sortable->order_by)
            ->select($selectCols)
            ->paginate(config('const.paginator.items'));

        return view('user.account.index', compact('conditions', 'locations', 'departments', 'users', 'sortable'));
    }

    public function delete(User $user)
    {
        $loggedUser = Auth::user();
        // do not delete your self
        if ($user->id === $loggedUser->id) {
            return Common::redirectBackWithAlertFail(__('msg.delete_fail'));
        }
        $user = User::find($user->id);
        $user->updated_by = $loggedUser->id;
        $user->deleted_at = Carbon::now();
        $user->save();

        return Common::redirectBackWithAlertSuccess(__('msg.delete_success'));
    }
}
