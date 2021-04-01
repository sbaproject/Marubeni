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
        $conditions = $request->only(['user_no', 'department', 'name', 'show_del_user']);

        // sorting columns
        $sortableCols = [
            'user_no'           => __('label._no_'),
            'department_name'   => __('validation.attributes.department'),
            'user_name'         => __('validation.attributes.user.name'),
        ];
        $sortable = Common::getSortable($request, $sortableCols, 0, 0, true);

        // selection columns
        $selectCols = [
            'users.id           as user_id',
            'users.user_no',
            'users.name         as user_name',
            'departments.name   as department_name',
        ];

        // get results
        $users = User::join('departments', function ($join) {
            $join->on('users.department_id', '=', 'departments.id');
        })
        ->select($selectCols)
        ->where('users.super_admin_flg', config('const.check.off')) // not show super admin account
        ->when(isset($conditions['location']), function ($q) use ($conditions) {
            return $q->where('users.location', '=', $conditions['location']);
        })
        ->when(isset($conditions['department']), function ($q) use ($conditions) {
            return $q->where('users.department_id', '=', $conditions['department']);
        })
        ->when(isset($conditions['name']), function ($q) use ($conditions) {
            return $q->where('users.name', 'LIKE', '%' . trim($conditions['name']) . '%');
        })
        ->when(isset($conditions['user_no']), function ($q) use ($conditions) {
            return $q->where("users.user_no", "LIKE",  '%' . $conditions['user_no'] . '%');
        })
        ->when(isset($conditions['show_del_user']) && $conditions['show_del_user'] == "on", function ($q) {
            return $q->where('users.deleted_at', "<>", NULL);
        })
        ->orderByRaw($sortable->order_by);

        // allow trashed user
        if (isset($conditions['show_del_user']) && $conditions['show_del_user'] == "on") {
            $users = $users->withTrashed();
        }

        $users = $users->paginate(config('const.paginator.items'));

        return view('account_index', compact('conditions', 'locations', 'departments', 'users', 'sortable'));
    }

    public function delete(User $user)
    {
        $loggedUser = Auth::user();

        // do not delete your self
        if ($user->id === $loggedUser->id) {
            return Common::redirectBackWithAlertFail(__('msg.delete_fail'));
        }

        // super admin account do not delete
        if ($user->super_admin_flg == config('const.check.on')) {
            return Common::redirectBackWithAlertFail(__('msg.delete_fail'));
        }

        $user = User::find($user->id);
        $user->updated_by = $loggedUser->id;
        $user->deleted_at = Carbon::now();
        $user->save();

        return Common::redirectBackWithAlertSuccess(__('msg.delete_success'));
    }

    public function restore($id)
    {
        $restoreUser = User::where('id', $id)->withTrashed()->first();

        // not found user
        if (empty($restoreUser)) {
            abort(404);
        }

        // restore
        User::withTrashed()
            ->where('id', $id)
            ->update(
                [
                    'deleted_at' => null,
                    'updated_by' => Auth::user()->id
                ]
            );

        return Common::redirectBackWithAlertSuccess(__('msg.restore_success'));
    }
}
