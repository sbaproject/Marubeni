<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminStatusController extends Controller
{
    public function index($status, Request $request)
    {
        $data = $request->input();
        $fillZero = config('const.num_fillzero');

        //Set case in Status is Approvel
        if (intval($status) == config('const.application.status.approvel_un') or intval($status) == config('const.application.status.approvel_in')) {
            $sta = 1;
            $end = 98;
            if (intval($status) == config('const.application.status.approvel_un')) {
                $stepStr = 1;
                $stepEnd = 1;
            } else if (intval($status) == config('const.application.status.approvel_in')) {
                $stepStr = 2;
                $stepEnd = 2;
            }
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
                ->select(DB::raw("CONCAT(CONCAT(forms.prefix,'-'),LPAD(applications.`id`, " . $fillZero . ", '0')) AS application_no"), 'forms.name As nameapp', 'applications.created_at as datecreate', 'users.name as nameuser', 'applications.form_id', 'applications.id')

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
                ->select(DB::raw("CONCAT(CONCAT(forms.prefix,'-'),LPAD(applications.`id`, " . $fillZero . ", '0')) AS application_no"), 'forms.name As nameapp', 'applications.created_at as datecreate', 'users.name as nameuser', 'applications.form_id', 'applications.id')

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
                ->where(DB::raw('CAST(steps.step_type AS SIGNED)'), DB::raw('CAST(applications.current_step AS SIGNED)'))
                ->where(DB::raw('CAST(steps.select_order AS SIGNED)'), DB::raw('CAST(applications.status AS SIGNED)'))
                ->where('steps.approver_type', 0)

                ->orderBy('applications.id', 'desc')
                ->whereNull('applications.deleted_at')
                ->paginate(5);
        }

        // Type Application
        $intstatus = (int)$status;

        return view('admin.status', compact('list_applications_status', 'intstatus', 'str_date', 'end_date'));
    }
}
