<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libs\Common;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->input();

        $str_date = null;
        $end_date = null;

        //When Search by Time
        if (isset($data['dataDateFrom']) && isset($data['dataDateTo'])) {
            $str_date = $data['dataDateFrom'] . ' 00:00:00';
            $end_date = $data['dataDateTo'] . ' 23:59:59';
        } else {
            $str_date = config('const.init_time_search.from');
            $end_date = config('const.init_time_search.to');
        }

        if (!isset($data['typeApply'])) {
            $sta = -2;
            $end = 99;
            $stepStr = 1;
            $stepEnd = 2;

            // Type Application
            $intstatus = config('const.application.status.all');
        } else {

            //Set case in Status is Approvel
            if (intval($data['typeApply']) == config('const.application.status.applying')) {
                $sta = 0;
                $end = 98;
                $stepStr = 1;
                $stepEnd = 1;
            } else if (intval($data['typeApply']) == config('const.application.status.approvel')) {
                $sta = 0;
                $end = 98;
                $stepStr = 2;
                $stepEnd = 2;
            } else {
                $sta = intval($data['typeApply']);
                $end = intval($data['typeApply']);
                $stepStr = 1;
                $stepEnd = 2;
            }

            // Type Application
            $intstatus = $data['typeApply'];
        }

        // sorting columns
        $sortColNames = [
            'application_no'    => __('label.dashboard.application_no'),
            'form_name'  => __('label.dashboard.application_name'),
            'status'        => __('label.dashboard.status'),
            'created_at'     => __('label.dashboard.apply_date'),
        ];
        $sortable = Common::getSortable($request, $sortColNames, 0, 0, true);

        //Get Applications By Condition
        $list_application = $this->list_application($sta, $end, $stepStr, $stepEnd, $str_date, $end_date, $sortable);

        //Count Applications By Condition
        $count_applying  = $this->list_application_count(0, 98, 1, 1, $str_date, $end_date)->count();

        $count_approval  = $this->list_application_count(0, 98, 2, 2, $str_date, $end_date)->count();

        $count_declined  = $this->list_application_count(-1, -1, 1, 2, $str_date, $end_date)->count();

        $count_reject  = $this->list_application_count(-2, -2, 1, 2, $str_date, $end_date)->count();

        $count_completed  = $this->list_application_count(99, 99, 1, 2, $str_date, $end_date)->count();

        return view('admin_dashboard_index', compact('list_application', 'count_applying', 'count_approval', 'count_declined', 'count_reject', 'count_completed', 'str_date', 'end_date', 'intstatus', 'sortable'));
    }

    //Get List Application by Condition
    private function list_application($sta, $end, $stepStr, $stepEnd, $str_date, $end_date, $sortable)
    {

        //List
        $list_application =  DB::table('applications')
            ->select(
                'application_no',
                'forms.name As form_name',
                'applications.created_at As created_at',
                DB::raw('(CASE WHEN (applications.status >= 0 AND applications.status <= 98 AND applications.current_step = 1) THEN "' . __('label.dashboard.applying') . '" ELSE (CASE WHEN (applications.status = "' . config('const.application.status.declined') . '") THEN "' . __('label.dashboard.declined') . '" ELSE (CASE WHEN (applications.status = "' . config('const.application.status.reject') . '") THEN "' . __('label.dashboard.reject') . '" ELSE (CASE WHEN (applications.status = "' . config('const.application.status.completed') . '") THEN "' . __('label.dashboard.completed') . '" ELSE ("' . __('label.dashboard.approval') . '") END ) END) END) END) AS status'),
                'applications.status As status_css',
                'applications.current_step As current_step',
                'applications.form_id As form_id',
                'applications.id As id'
            )

            //Join
            ->join('forms', 'applications.form_id', '=', 'forms.id')

            //Where
            ->where('applications.status', '>=', $sta)
            ->where('applications.status', '<=', $end)
            ->where('applications.current_step', '>=', $stepStr)
            ->where('applications.current_step', '<=', $stepEnd)
            ->where('applications.created_at', '>=', $str_date)
            ->where('applications.created_at', '<=', $end_date)

            //OrderBy
            ->orderBy($sortable->s, $sortable->d)
            ->whereNull('applications.deleted_at')
            ->paginate(config('const.paginator.items'));

        return  $list_application;
    }

    //Get Count
    private function list_application_count($sta, $end, $stepStr, $stepEnd, $str_date, $end_date)
    {

        //List
        $list_application =  DB::table('applications')
            ->select(
                'application_no',
                'forms.name As form_name',
                'applications.created_at As created_at',
                'applications.status As status',
                'applications.current_step As current_step',
                'applications.form_id As form_id',
                'applications.id As id'
            )

            //Join
            ->join('forms', 'applications.form_id', '=', 'forms.id')

            //Where
            ->where('applications.status', '>=', $sta)
            ->where('applications.status', '<=', $end)
            ->where('applications.current_step', '>=', $stepStr)
            ->where('applications.current_step', '<=', $stepEnd)
            ->where('applications.created_at', '>=', $str_date)
            ->where('applications.created_at', '<=', $end_date)

            ->whereNull('applications.deleted_at')
            ->get();

        return  $list_application;
    }
}
