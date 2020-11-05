<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserStatusController extends Controller
{
    public function index($status, Request $request)
    {
        $userId = Auth::user()->id;
        $data = $request->input();

        //Set case in Status is Approvel
        if (intval($status) == 1 or intval($status) == 2) {
            $sta = 1;
            $end = 98;
        } else {
            $sta = intval($status);
            $end = intval($status);
        }

        if (!empty($data['dataDateFrom']) && !empty($data['dataDateTo'])) {

            //When Search
            $str_date = $data['dataDateFrom'] . ' 00:00:00';
            $end_date = $data['dataDateTo'] . ' 23:59:59';

            session(['str_date' => $str_date]);
            session(['end_date' => $end_date]);

            $list_applications_status = DB::table('applications')
                ->select('forms.name as nameapp', 'applications.created_at as datecreate', 'users.name as nameuser')

                ->join('forms', 'applications.form_id', '=', 'forms.id')
                ->join('groups', 'applications.group_id', '=', 'groups.id')
                ->join('flows', 'groups.id', '=', 'flows.group_id')
                ->join('steps', 'flows.id', '=', 'steps.flow_id')
                ->join('users', 'users.id', '=', 'steps.approver_id')

                ->where('applications.status', '>=', $sta)
                ->where('applications.status', '<=', $end)
                ->where('applications.created_by', $userId)
                ->where(DB::raw('CAST(steps.step_type AS SIGNED)'), DB::raw('CAST(applications.current_step AS SIGNED)'))
                ->where(DB::raw('CAST(steps.select_order AS SIGNED)'), DB::raw('CAST(applications.status AS SIGNED)'))

                //Condition Time
                ->where('applications.created_at', '>=', $str_date)
                ->where('applications.created_at', '<=', $end_date)

                ->orderBy('applications.id', 'desc')
                ->whereNull('applications.deleted_at')
                ->paginate(5);
        } else {

            session()->forget('str_date');
            session()->forget('end_date');

            //Load Page
            $list_applications_status = DB::table('applications')
                ->select('forms.name as nameapp', 'applications.created_at as datecreate', 'users.name as nameuser')

                ->join('forms', 'applications.form_id', '=', 'forms.id')
                ->join('groups', 'applications.group_id', '=', 'groups.id')
                ->join('flows', 'groups.id', '=', 'flows.group_id')
                ->join('steps', 'flows.id', '=', 'steps.flow_id')
                ->join('users', 'users.id', '=', 'steps.approver_id')

                ->where('applications.status', '>=', $sta)
                ->where('applications.status', '<=', $end)
                ->where('applications.created_by', $userId)
                ->where(DB::raw('CAST(steps.step_type AS SIGNED)'), DB::raw('CAST(applications.current_step AS SIGNED)'))
                ->where(DB::raw('CAST(steps.select_order AS SIGNED)'), DB::raw('CAST(applications.status AS SIGNED)'))

                ->where('steps.approver_type', 0)

                ->orderBy('applications.id', 'desc')
                ->whereNull('applications.deleted_at')
                ->paginate(5);
        }

        return view('user.status', compact('list_applications_status'));
    }
}
