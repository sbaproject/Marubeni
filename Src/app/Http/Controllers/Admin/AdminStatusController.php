<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminStatusController extends Controller
{
    public function index($status, Request $request)
    {
        $data = $request->input();
        $fillZero = config('const.num_fillzero');

        //Set case in Status is Approvel
        if (intval($status) == config('const.application.status.applying')) {
            $sta = 0;
            $end = 98;
            $stepStr = 1;
            $stepEnd = 1;
        } else if (intval($status) == config('const.application.status.approvel_un')) {
            $sta = 0;
            $end = 0;
            $stepStr = 2;
            $stepEnd = 2;
        } else if (intval($status) == config('const.application.status.approvel_in')) {
            $sta = 1;
            $end = 98;
            $stepStr = 2;
            $stepEnd = 2;
        } else {
            $sta = intval($status);
            $end = intval($status);
            $stepStr = 1;
            $stepEnd = 2;
        }

        //When Search by Time
        if (!empty($data['dataDateFrom']) && !empty($data['dataDateTo'])) {

            $str_date = $data['dataDateFrom'] . ' 00:00:00';
            $end_date = $data['dataDateTo'] . ' 23:59:59';
        } else {
            $str_date = config('const.init_time_search.from');
            $end_date = config('const.init_time_search.to');
        }

        //Get List
        $list_applications_status = DB::table('applications')
            ->select(DB::raw("CONCAT(CONCAT(forms.prefix,'-'),LPAD(applications.`id`, " . $fillZero . ", '0')) AS application_no"), 'forms.name As nameapp', 'applications.created_at as datecreate', 'users.name as nameuser', 'applications.form_id', 'applications.id')

            //Join
            ->join('forms', 'applications.form_id', '=', 'forms.id')
            ->join('groups', 'applications.group_id', '=', 'groups.id')
            ->join('steps', 'groups.id', '=', 'steps.group_id')
            ->join('users', 'users.id', '=', 'steps.approver_id')

            //When
            ->when(intval($status) == config('const.application.status.declined') or intval($status) == config('const.application.status.reject'), function ($q) {
                $q = $q->where(DB::raw('CAST(applications.current_step AS SIGNED)'), DB::raw('CAST(steps.step_type AS SIGNED)'))
                    ->where(DB::raw('0'), DB::raw('CAST(steps.select_order AS SIGNED)'));
            })
            ->when(intval($status) != config('const.application.status.completed') && intval($status) != config('const.application.status.declined') && intval($status) != config('const.application.status.reject'), function ($q) {
                $q = $q->where(DB::raw('CAST(applications.current_step AS SIGNED)'), DB::raw('CAST(steps.step_type AS SIGNED)'))
                    ->where(DB::raw('CAST(applications.status AS SIGNED)'), DB::raw('CAST(steps.select_order AS SIGNED)'));
            })
            ->when(intval($status) == config('const.application.status.completed'), function ($q) {
                $q = $q->where(DB::raw('CAST(applications.current_step AS SIGNED)'), DB::raw('CAST(steps.step_type AS SIGNED)'))
                    ->where(DB::raw('CAST(applications.status AS SIGNED)'), DB::raw('CAST(steps.order AS SIGNED)'));
            })

            //Where
            ->where('applications.status', '>=', $sta)
            ->where('applications.status', '<=', $end)
            ->where('applications.current_step', '>=', $stepStr)
            ->where('applications.current_step', '<=', $stepEnd)
            ->where('steps.approver_type', config('const.approver_type.to'))

            //Condition Time
            ->where('applications.created_at', '>=', $str_date)
            ->where('applications.created_at', '<=', $end_date)

            //OrderBy
            ->orderBy('applications.id', 'desc')
            ->whereNull('applications.deleted_at')
            ->paginate(5);

        // Type Application
        $intstatus = (int)$status;

        return view('user.status.index', compact('list_applications_status', 'intstatus', 'str_date', 'end_date'));
    }
}
