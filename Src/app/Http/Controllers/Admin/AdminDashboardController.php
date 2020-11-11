<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index($id,Request $request){

        $userId = Auth::user()->id;
        $data = $request->input();
        $str_date = null;

        if(!empty($data['dataDateFrom'])){

            $str_date = $data['dataDateFrom']. ' 00:00:00';

            if ($id == config('const.application.status.all')) {
                $list_application =  Application::where('created_at','>=',$str_date)->where('status', '!=' , config('const.application.status.draft'))->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.applying')) {
                $list_application =  Application::where('created_at','>=',$str_date)->where('status', config('const.application.status.applying'))->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.approvel')) {
                $list_application =  Application::where('created_at','>=',$str_date)->whereBetween('status', [1,98])->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.declined')) {
                $list_application =  Application::where('created_at','>=',$str_date)->where('status', config('const.application.status.declined'))->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.reject')) {
                $list_application =  Application::where('created_at','>=',$str_date)->where('status', config('const.application.status.reject'))->orderBy('id', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.completed')) {
                $list_application =  Application::where('created_at','>=',$str_date)->where('status', config('const.application.status.completed'))->orderBy('id', 'DESC')->paginate(5);
            }

            $count_applying =  Application::where('status', config('const.application.status.applying'))->where('created_at','>=',$str_date)->count();
            $count_approval =  Application::whereBetween('status', [1,98])->where('created_at','>=',$str_date)->count();
            $count_declined =  Application::where('status', config('const.application.status.declined'))->where('created_at','>=',$str_date)->count();
            $count_reject =  Application::where('status', config('const.application.status.reject'))->where('created_at','>=',$str_date)->count();
            $count_completed =  Application::where('status', config('const.application.status.completed'))->where('created_at','>=',$str_date)->count();

        }else{

            $str_date = Carbon::now();

            if ($id == config('const.application.status.all')) {
                $list_application =  Application::where('status', '!=' , config('const.application.status.draft'))->orderBy('created_at', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.applying')) {
                $list_application =  Application::where('status', config('const.application.status.applying'))->orderBy('created_at', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.approvel')) {
                $list_application =  Application::whereBetween('status', [1,98])->orderBy('created_at', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.declined')) {
                $list_application =  Application::where('status', config('const.application.status.declined'))->orderBy('created_at', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.reject')) {
                $list_application =  Application::where('status', config('const.application.status.reject'))->orderBy('created_at', 'DESC')->paginate(5);
            } else if ($id == config('const.application.status.completed')) {
                $list_application =  Application::where('status', config('const.application.status.completed'))->orderBy('created_at', 'DESC')->paginate(5);
            }

            $count_applying =  Application::where('status', config('const.application.status.applying'))->count();
            $count_approval =  Application::whereBetween('status', [1,98])->count();
            $count_declined =  Application::where('status', config('const.application.status.declined'))->count();
            $count_reject =  Application::where('status', config('const.application.status.reject'))->count();
            $count_completed =  Application::where('status', config('const.application.status.completed'))->count();

        }

        // Type Application
        $intstatus = (int)$id;

        return view('admin.dashboard', compact('list_application','count_applying','count_approval','count_declined','count_reject','count_completed','str_date','intstatus'));
    }
}
