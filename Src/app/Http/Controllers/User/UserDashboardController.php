<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index(Request $request){

        $userId = Auth::user()->id;
        $data = $request->input();
        if(!empty($data['dataDateFrom']) && !empty($data['dataDateTo'])){

            $str_date = $data['dataDateFrom']. ' 00:00:00';
            $end_date = $data['dataDateTo']. ' 23:59:59';

            session(['str_date' => $str_date]);
            session(['end_date' => $end_date]);

            $list_application =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->orderBy('id', 'DESC')->paginate(5);
            $count_applying =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->where('status', config('const.application.status.applying'))->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_approval =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->whereBetween('status', [1,98])->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_declined =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->where('status', config('const.application.status.declined'))->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_reject =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->where('status', config('const.application.status.reject'))->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_completed =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->where('status', config('const.application.status.completed'))->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();

        }else{

            session()->forget('str_date');
            session()->forget('end_date');

            $list_application =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->orderBy('created_at', 'DESC')->paginate(5);
            $count_applying =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->where('status', config('const.application.status.applying'))->count();
            $count_approval =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->whereBetween('status', [1,98])->count();
            $count_declined =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->where('status', config('const.application.status.declined'))->count();
            $count_reject =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->where('status', config('const.application.status.reject'))->count();
            $count_completed =  Application::where('created_by', $userId)->whereNull('applications.deleted_at')->where('status', config('const.application.status.completed'))->count();

        }

        return view('user.dashboard', compact('list_application','count_applying','count_approval','count_declined','count_reject','count_completed'));
    }
}
