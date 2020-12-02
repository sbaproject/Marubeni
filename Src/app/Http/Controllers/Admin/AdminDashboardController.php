<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index($id, Request $request)
    {

        $userId = Auth::user()->id;
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

        //Get Applications By Condition
        $list_application = $this->list_application($id, $str_date, $end_date);

        //Count Applications By Condition
        $count_applying  = $this->list_application(config('const.application.status.applying'), $str_date, $end_date)->count();

        $count_approval  = $this->list_application(config('const.application.status.approvel'), $str_date, $end_date)->count();

        $count_declined  = $this->list_application(config('const.application.status.declined'), $str_date, $end_date)->count();

        $count_reject  = $this->list_application(config('const.application.status.reject'), $str_date, $end_date)->count();

        $count_completed  = $this->list_application(config('const.application.status.completed'), $str_date, $end_date)->count();

        // Type Application
        $intstatus = (int)$id;

        return view('admin.dashboard.index', compact('list_application', 'count_applying', 'count_approval', 'count_declined', 'count_reject', 'count_completed', 'str_date', 'end_date', 'intstatus'));
    }

    //Get List Application by Condition
    private function list_application($id, $str_date, $end_date)
    {
        $fillZero = config('const.num_fillzero');

        //List Leave
        $list_application_leave =  DB::table('applications')
            ->select(DB::raw("CONCAT(CONCAT(forms.prefix,'-'),LPAD(applications.`id`, " . $fillZero . ", '0')) AS application_no"), 'forms.name As form_name', 'applications.created_at As created_at', 'applications.status As status', 'applications.form_id As form_id', 'applications.id As id')

            //Join
            ->join('forms', 'applications.form_id', '=', 'forms.id')

            //When
            ->when($id == config('const.application.status.all'), function ($q) {
                $q = $q->where('applications.status', '!=', config('const.application.status.draft'));
            })
            ->when($id == config('const.application.status.applying'), function ($q) {
                $q = $q->where('applications.status', config('const.application.status.applying'));
            })
            ->when($id == config('const.application.status.approvel'), function ($q) {
                $q = $q->whereBetween('applications.status', [1, 98]);
            })
            ->when($id == config('const.application.status.declined'), function ($q) {
                $q = $q->where('applications.status', config('const.application.status.declined'));
            })
            ->when($id == config('const.application.status.reject'), function ($q) {
                $q = $q->where('applications.status', config('const.application.status.reject'));
            })
            ->when($id == config('const.application.status.completed'), function ($q) {
                $q = $q->where('applications.status', config('const.application.status.completed'));
            })

            //Where
            ->where('applications.created_at', '>=', $str_date)
            ->where('applications.created_at', '<=', $end_date)
            ->whereIn('forms.id', [config('const.form.leave')])

            //OrderBy
            ->orderBy('applications.id', 'DESC')
            ->whereNull('applications.deleted_at');

        //List Entertainment, Business Trip
        $list_application =  DB::table('applications')
            ->select(DB::raw("CONCAT(CONCAT(forms.prefix,'-'),LPAD(applications.`id`, " . $fillZero . ", '0')) AS application_no"), 'forms.name As form_name', 'applications.created_at As created_at', DB::raw('(CASE WHEN applications.status = 0 AND applications.current_step = 2 THEN 1 ELSE applications.status END) AS status'), 'applications.form_id As form_id', 'applications.id As id')

            //Join
            ->join('forms', 'applications.form_id', '=', 'forms.id')

            //When
            ->when($id == config('const.application.status.all'), function ($q) {
                $q = $q->where('applications.status', '!=', config('const.application.status.draft'));
            })
            ->when($id == config('const.application.status.applying'), function ($q) {
                $q = $q->where('applications.status', config('const.application.status.applying'))
                    ->whereNotIn('applications.id', function ($query) {
                        return $query->select('id')
                            ->from('applications')
                            ->where('applications.status', '=', 0)
                            ->Where('applications.current_step', '=', 2);
                    });
            })
            ->when($id == config('const.application.status.approvel'), function ($q) {
                $q = $q->whereBetween('applications.status', [0, 98])
                    ->whereNotIn('applications.id', function ($query) {
                        return $query->select('id')
                            ->from('applications')
                            ->where('applications.status', '=', 0)
                            ->Where('applications.current_step', '=', 1);
                    });
            })
            ->when($id == config('const.application.status.declined'), function ($q) {
                $q = $q->where('applications.status', config('const.application.status.declined'));
            })
            ->when($id == config('const.application.status.reject'), function ($q) {
                $q = $q->where('applications.status', config('const.application.status.reject'));
            })
            ->when($id == config('const.application.status.completed'), function ($q) {
                $q = $q->where('applications.status', config('const.application.status.completed'));
            })

            //Where
            ->where('applications.created_at', '>=', $str_date)
            ->where('applications.created_at', '<=', $end_date)
            ->whereIn('forms.id', [config('const.form.biz_trip'), config('const.form.entertainment')])

            //OrderBy
            ->orderBy('applications.id', 'DESC')
            ->whereNull('applications.deleted_at')
            ->union($list_application_leave)
            ->paginate(5);

        return  $list_application;
    }
}
