<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index(Request $request){

        $userId = Auth::user()->id;
        $data = $request->input();
        if(!empty($data['dataDateFrom'])){

            $str_date = $data['dataDateFrom']. ' 00:00:00';

            session(['str_date' => $str_date]);

            $list_application =  Application::whereNull('applications.deleted_at')->where('created_at','>=',$str_date)->where('status', '!=' , config('const.application.status.draft'))->orderBy('id', 'DESC')->paginate(5);
            $count_applying =  Application::whereNull('applications.deleted_at')->where('status', config('const.application.status.applying'))->where('created_at','>=',$str_date)->count();
            $count_approval =  Application::whereNull('applications.deleted_at')->whereBetween('status', [1,98])->where('created_at','>=',$str_date)->count();
            $count_declined =  Application::whereNull('applications.deleted_at')->where('status', config('const.application.status.declined'))->where('created_at','>=',$str_date)->count();
            $count_reject =  Application::whereNull('applications.deleted_at')->where('status', config('const.application.status.reject'))->where('created_at','>=',$str_date)->count();
            $count_completed =  Application::whereNull('applications.deleted_at')->where('status', config('const.application.status.completed'))->where('created_at','>=',$str_date)->count();

        }else{

            session()->forget('str_date');

            $list_application =  Application::whereNull('applications.deleted_at')->where('status', '!=' , config('const.application.status.draft'))->orderBy('created_at', 'DESC')->paginate(5);
            $count_applying =  Application::whereNull('applications.deleted_at')->where('status', config('const.application.status.applying'))->count();
            $count_approval =  Application::whereNull('applications.deleted_at')->whereBetween('status', [1,98])->count();
            $count_declined =  Application::whereNull('applications.deleted_at')->where('status', config('const.application.status.declined'))->count();
            $count_reject =  Application::whereNull('applications.deleted_at')->where('status', config('const.application.status.reject'))->count();
            $count_completed =  Application::whereNull('applications.deleted_at')->where('status', config('const.application.status.completed'))->count();

        }

        return view('admin.dashboard', compact('list_application','count_applying','count_approval','count_declined','count_reject','count_completed'));
    }
}
