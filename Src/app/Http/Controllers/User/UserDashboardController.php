<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index(Request $request){

        $userId = Auth::user()->id;
        $data = $request->input();

        $str_date = null;
        $end_date = null;

        if(!empty($data['dataDateFrom']) && !empty($data['dataDateTo'])){

            $str_date = $data['dataDateFrom']. ' 00:00:00';
            $end_date = $data['dataDateTo']. ' 23:59:59';

            $list_application =  Application::where('created_by', $userId)->whereNull('deleted_at')->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->orderBy('id', 'DESC')->paginate(5);
            $count_applying =  Application::where('created_by', $userId)->whereNull('deleted_at')->where('status', config('const.application.status.applying'))->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_approval =  Application::where('created_by', $userId)->whereNull('deleted_at')->whereBetween('status', [1,98])->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_declined =  Application::where('created_by', $userId)->whereNull('deleted_at')->where('status', config('const.application.status.declined'))->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_reject =  Application::where('created_by', $userId)->whereNull('deleted_at')->where('status', config('const.application.status.reject'))->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_completed =  Application::where('created_by', $userId)->whereNull('deleted_at')->where('status', config('const.application.status.completed'))->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();

        }else{

            $str_date = Carbon::now();
            $end_date = Carbon::now();

            $list_application =  Application::where('created_by', $userId)->whereNull('deleted_at')->orderBy('created_at', 'DESC')->paginate(5);
            $count_applying =  Application::where('created_by', $userId)->whereNull('deleted_at')->where('status', config('const.application.status.applying'))->count();
            $count_approval =  Application::where('created_by', $userId)->whereNull('deleted_at')->whereBetween('status', [1,98])->count();
            $count_declined =  Application::where('created_by', $userId)->whereNull('deleted_at')->where('status', config('const.application.status.declined'))->count();
            $count_reject =  Application::where('created_by', $userId)->whereNull('deleted_at')->where('status', config('const.application.status.reject'))->count();
            $count_completed =  Application::where('created_by', $userId)->whereNull('deleted_at')->where('status', config('const.application.status.completed'))->count();

        }

        return view('user.dashboard', compact('list_application','count_applying','count_approval','count_declined','count_reject','count_completed','str_date','end_date'));
    }
}
