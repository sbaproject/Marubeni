<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Carbon\Carbon;
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
            $stepStr = intval($status);
            $stepEnd = intval($status);
        } else {
            $sta = intval($status);
            $end = intval($status);
            $stepStr = 1;
            $stepEnd = 2;
        }

        if (!empty($data['dataDateFrom']) && !empty($data['dataDateTo'])) {

            //When Search
            $str_date = $data['dataDateFrom'] . ' 00:00:00';
            $end_date = $data['dataDateTo'] . ' 23:59:59';

            $list_applications_status = DB::table('applications')
                ->select('forms.name as nameapp', 'applications.created_at as datecreate', 'users.name as nameuser')

                //Join
                ->join('forms', 'applications.form_id', '=', 'forms.id')
                ->join('groups', 'applications.group_id', '=', 'groups.id')
                ->join('flows', 'groups.id', '=', 'flows.group_id')
                ->join('steps', 'flows.id', '=', 'steps.flow_id')
                ->join('users', 'users.id', '=', 'steps.approver_id')

                //Where
                ->where('applications.status', '>=', $sta)
                ->where('applications.status', '<=', $end)
                ->where('applications.current_step', '>=', $stepStr)
                ->where('applications.current_step', '<=', $stepEnd)
                ->where('applications.created_by', $userId)
                ->where(DB::raw('CAST(steps.step_type AS SIGNED)'), DB::raw('CAST(applications.current_step AS SIGNED)'))
                ->where(DB::raw('CAST(steps.select_order AS SIGNED)'), DB::raw('CAST(applications.status AS SIGNED)'))
                ->where('steps.approver_type', 0)

                //Condition Time
                ->where('applications.created_at', '>=', $str_date)
                ->where('applications.created_at', '<=', $end_date)

                ->orderBy('applications.id', 'desc')
                ->whereNull('applications.deleted_at')
                ->paginate(5);
        } else {

            $str_date = Carbon::now();
            $end_date = Carbon::now();

            //Load Page
            $list_applications_status = DB::table('applications')
                ->select('forms.name As nameapp', 'applications.created_at As datecreate', 'users.name As nameuser')

                //Join
                ->join('forms', 'applications.form_id', '=', 'forms.id')
                ->join('groups', 'applications.group_id', '=', 'groups.id')
                ->join('flows', 'groups.id', '=', 'flows.group_id')
                ->join('steps', 'flows.id', '=', 'steps.flow_id')
                ->join('users', 'users.id', '=', 'steps.approver_id')

                //Where
                ->where('applications.status', '>=', $sta)
                ->where('applications.status', '<=', $end)
                ->where('applications.current_step', '>=', $stepStr)
                ->where('applications.current_step', '<=', $stepEnd)
                ->where('applications.created_by', $userId)
                ->where(DB::raw('CAST(steps.step_type AS SIGNED)'), DB::raw('CAST(applications.current_step AS SIGNED)'))
                ->where(DB::raw('CAST(steps.select_order AS SIGNED)'), DB::raw('CAST(applications.status AS SIGNED)'))
                ->where('steps.approver_type', 0)

                ->orderBy('applications.id', 'desc')
                ->whereNull('applications.deleted_at')
                ->paginate(5);
        }

        // Type Application
        $intstatus = (int)$status;

        return view('user.status', compact('list_applications_status', 'intstatus', 'str_date', 'end_date'));
    }
}
