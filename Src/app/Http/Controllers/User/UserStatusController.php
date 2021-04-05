<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Libs\Common;

class UserStatusController extends Controller
{
    public function index($status, Request $request)
    {
        $userId = Auth::user()->id;
        $data = $request->input();
        $fillZero = config('const.num_fillzero');
        $completedStatus = config('const.application.status.completed');

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

        // sorting columns
        $sortColNames = [
            'application_no'    => __('label.status_no'),
            'form_name'         => __('label.status_application_type'),
            'datecreate'        => __('label.status_apply_date'),
            'next_approver'     => __('label.status_next_approver'),
        ];
        $sortable = Common::getSortable($request, $sortColNames, 0, 0, true);

        //Get List
        $list_applications_status = DB::table('applications')
            ->select(
                'applications.id',
                'applications.application_no',
                'applications.form_id',
                'applications.group_id',
                'applications.current_step',
                'applications.created_at    as datecreate',
                'applications.updated_at    as updated_at',
                'forms.name                 as form_name',
                'forms.id                   as form_id',
                'users.name                 as next_approver',
                'users.id                   as next_approver_id',
                // get final approver
                DB::raw("
                    (
                        select  approver_id
                        from    steps as st
                        where   flow_id = steps.flow_id
                                and `order` = {$completedStatus}
                        order by step_type desc limit 1
                    ) as final_approver_id
                "),
            )

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
            ->where('applications.created_by', $userId)
            ->where('steps.approver_type', config('const.approver_type.to'))

            //Condition Time
            ->where('applications.created_at', '>=', $str_date)
            ->where('applications.created_at', '<=', $end_date)

            //OrderBy
            ->orderBy($sortable->s, $sortable->d)
            ->whereNull('applications.deleted_at')
            ->paginate(config('const.paginator.items'));

        // Type Application
        $intstatus = (int)$status;

        return view('user_status_index', compact('list_applications_status', 'intstatus', 'str_date', 'end_date', 'sortable'));
    }
}
