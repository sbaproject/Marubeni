<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index($id, Request $request)
    {
        $data = $request->input();

        $str_date = null;
        $end_date = null;

        //When Search by Time
        if (!empty($data['dataDateFrom']) && !empty($data['dataDateTo'])) {

            $str_date = $data['dataDateFrom'] . ' 00:00:00';
            $end_date = $data['dataDateTo'] . ' 23:59:59';
        } else {
            $str_date = config('const.init_time_search.from');
            $end_date = config('const.init_time_search.to');
        }

        //Set case in Status is Approvel
        if (intval($id) == config('const.application.status.all')) {
            $sta = -2;
            $end = 99;
            $stepStr = 1;
            $stepEnd = 2;
        } else if (intval($id) == config('const.application.status.applying')) {
            $sta = 0;
            $end = 98;
            $stepStr = 1;
            $stepEnd = 1;
        } else if (intval($id) == config('const.application.status.approvel')) {
            $sta = 0;
            $end = 98;
            $stepStr = 2;
            $stepEnd = 2;
        } else {
            $sta = intval($id);
            $end = intval($id);
            $stepStr = 1;
            $stepEnd = 2;
        }

        //Get Applications By Condition
        $list_application = $this->list_application($sta, $end, $stepStr, $stepEnd, $str_date, $end_date);

        //Count Applications By Condition
        $count_applying  = $this->list_application(0, 98, 1, 1, $str_date, $end_date)->count();

        $count_approval  = $this->list_application(0, 98, 2, 2, $str_date, $end_date)->count();

        $count_declined  = $this->list_application(-1, -1, 1, 2, $str_date, $end_date)->count();

        $count_reject  = $this->list_application(-2, -2, 1, 2, $str_date, $end_date)->count();

        $count_completed  = $this->list_application(99, 99, 1, 2, $str_date, $end_date)->count();

        // Type Application
        $intstatus = (int)$id;

        return view('user.dashboard.index', compact('list_application', 'count_applying', 'count_approval', 'count_declined', 'count_reject', 'count_completed', 'str_date', 'end_date', 'intstatus'));
    }

    //Get List Application by Condition
    private function list_application($sta, $end, $stepStr, $stepEnd, $str_date, $end_date)
    {
        $fillZero = config('const.num_fillzero');
        $userId = Auth::user()->id;

        //List
        $list_application =  DB::table('applications')
            ->select(DB::raw("CONCAT(CONCAT(forms.prefix,'-'),LPAD(applications.`id`, " . $fillZero . ", '0')) AS application_no"), 'forms.name As form_name', 'applications.created_at As created_at', 'applications.status As status', 'applications.current_step As current_step', 'applications.form_id As form_id', 'applications.id As id')

            //Join
            ->join('forms', 'applications.form_id', '=', 'forms.id')

            //Where
            ->where('applications.status', '>=', $sta)
            ->where('applications.status', '<=', $end)
            ->where('applications.current_step', '>=', $stepStr)
            ->where('applications.current_step', '<=', $stepEnd)
            ->where('applications.created_at', '>=', $str_date)
            ->where('applications.created_at', '<=', $end_date)
            ->where('applications.created_by', $userId)

            //OrderBy
            ->orderBy('applications.id', 'DESC')
            ->whereNull('applications.deleted_at')
            ->paginate(5);

        return  $list_application;
    }
}
