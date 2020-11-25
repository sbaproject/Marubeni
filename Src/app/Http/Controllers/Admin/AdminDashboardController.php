<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index($id, Request $request)
    {

        $userId = Auth::user()->id;
        $data = $request->input();

        $str_date = null;
        $end_date = null;

        if (!empty($data['dataDateFrom']) && !empty($data['dataDateTo'])) {

            $str_date = $data['dataDateFrom'] . ' 00:00:00';
            $end_date = $data['dataDateTo'] . ' 23:59:59';

            if ($id == config('const.application.status.all')) {
                $list_application =  Application::where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->where('status', '!=', config('const.application.status.draft'))->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.applying')) {
                $list_application =  Application::where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->where('status', config('const.application.status.applying'))->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.approvel')) {
                $list_application =  Application::where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->whereBetween('status', [1, 98])->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.declined')) {
                $list_application =  Application::where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->where('status', config('const.application.status.declined'))->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.reject')) {
                $list_application =  Application::where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->where('status', config('const.application.status.reject'))->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.completed')) {
                $list_application =  Application::where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->where('status', config('const.application.status.completed'))->orderBy('id', 'DESC')->paginate(5);
            }

            $count_applying =  Application::where('status', config('const.application.status.applying'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
            $count_approval =  Application::whereBetween('status', [1, 98])->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
            $count_declined =  Application::where('status', config('const.application.status.declined'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
            $count_reject =  Application::where('status', config('const.application.status.reject'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
            $count_completed =  Application::where('status', config('const.application.status.completed'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
        } else {

            $str_date = config('const.time_search.from');
            $end_date = config('const.time_search.to');

            if ($id == config('const.application.status.all')) {
                $list_application =  Application::where('status', '!=', config('const.application.status.draft'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.applying')) {
                $list_application =  Application::where('status', config('const.application.status.applying'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.approvel')) {
                $list_application =  Application::whereBetween('status', [1, 98])->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.declined')) {
                $list_application =  Application::where('status', config('const.application.status.declined'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.reject')) {
                $list_application =  Application::where('status', config('const.application.status.reject'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.completed')) {
                $list_application =  Application::where('status', config('const.application.status.completed'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->orderBy('id', 'DESC')->paginate(5);
            }

            $count_applying =  Application::where('status', config('const.application.status.applying'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
            $count_approval =  Application::whereBetween('status', [1, 98])->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
            $count_declined =  Application::where('status', config('const.application.status.declined'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
            $count_reject =  Application::where('status', config('const.application.status.reject'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
            $count_completed =  Application::where('status', config('const.application.status.completed'))->where('created_at', '>=', $str_date)->where('created_at', '<=', $end_date)->count();
        }

        // Type Application
        $intstatus = (int)$id;

        return view('admin.dashboard', compact('list_application', 'count_applying', 'count_approval', 'count_declined', 'count_reject', 'count_completed', 'str_date', 'end_date', 'intstatus'));
    }
}
