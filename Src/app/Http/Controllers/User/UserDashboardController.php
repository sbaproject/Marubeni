<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index($id, Request $request)
    {

        $userId = Auth::user()->id;
        $data = $request->input();
        $fillZero = config('const.num_fillzero');

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

        //List Leave
        $list_application_leave =  DB::table('applications')
            ->select(DB::raw("CONCAT(CONCAT(forms.prefix,'-'),LPAD(applications.`id`, " . $fillZero . ", '0')) AS application_no"), 'forms.name As form_name', 'applications.created_at As created_at', 'applications.status As status', 'applications.form_id As form_id', 'applications.id As id')

            //Join
            ->join('forms', 'applications.form_id', '=', 'forms.id')

            //Where
            ->where('applications.status', '!=', config('const.application.status.draft'))
            ->where('applications.created_at', '>=', $str_date)
            ->where('applications.created_at', '<=', $end_date)
            ->whereIn('forms.id', [config('const.form.leave')])

            ->orderBy('applications.id', 'DESC')
            ->whereNull('applications.deleted_at');

        //List Entertainment, Business Trip
        $list_application =  DB::table('applications')
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
                $q = $q->where('status', [1, 98]);
            })
            ->when($id == config('const.application.status.declined'), function ($q) {
                $q = $q->where('status', config('const.application.status.declined'));
            })
            ->when($id == config('const.application.status.reject'), function ($q) {
                $q = $q->where('status', config('const.application.status.reject'));
            })
            ->when($id == config('const.application.status.all'), function ($q) {
                $q = $q->where('applications.status', '!=', config('const.application.status.draft'));
            })
            ->when($id == config('const.application.status.completed'), function ($q) {
                $q = $q->where('status', config('const.application.status.completed'));
            })

            ->where('applications.created_at', '>=', $str_date)
            ->where('applications.created_at', '<=', $end_date)
            ->whereIn('forms.id', [config('const.form.biz_trip'), config('const.form.entertainment')])

            // ->when($id == config('const.application.status.approvel'), function ($q) {
            //     $q = $q->where('applications.status', '>=', 0);
            // })
            // ->when(intval($status) != config('const.application.status.approvel_in'), function ($q) use ($sta) {
            //     $q = $q->where('applications.status', '>=', $sta)
            //         ->whereNotIn('applications.id', function ($query) {
            //             return $query->select('id')
            //                 ->from('applications')
            //                 ->where('applications.status', '=', 0)
            //                 ->Where('applications.current_step', '=', 2);
            //         });
            // })

            ->orderBy('applications.id', 'DESC')
            ->whereNull('applications.deleted_at')
            ->union($list_application_leave)
            ->paginate(5);


        $count_applying =  Application::where('created_by', $userId)->where('status', config('const.application.status.applying'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
        $count_approval =  Application::where('created_by', $userId)->whereBetween('status', [1, 98])->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
        $count_declined =  Application::where('created_by', $userId)->where('status', config('const.application.status.declined'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
        $count_reject =  Application::where('created_by', $userId)->where('status', config('const.application.status.reject'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
        $count_completed =  Application::where('created_by', $userId)->where('status', config('const.application.status.completed'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();

        // Type Application
        $intstatus = (int)$id;

        return view('user.dashboard', compact('list_application', 'count_applying', 'count_approval', 'count_declined', 'count_reject', 'count_completed', 'str_date', 'end_date', 'intstatus'));
    }
}
