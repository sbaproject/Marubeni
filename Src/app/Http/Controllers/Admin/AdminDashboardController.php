<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request){

        $userId = Auth::user()->id;
        $data = $request->input();
        $str_date = null;

        if(!empty($data['dataDateFrom'])){

            $str_date = $data['dataDateFrom']. ' 00:00:00';

            $list_application =  Application::where('created_at','>=',$str_date)->where('status', '!=' , config('const.application.status.draft'))->orderBy('id', 'DESC')->paginate(5);
            $count_applying =  Application::where('status', config('const.application.status.applying'))->where('created_at','>=',$str_date)->count();
            $count_approval =  Application::whereBetween('status', [1,98])->where('created_at','>=',$str_date)->count();
            $count_declined =  Application::where('status', config('const.application.status.declined'))->where('created_at','>=',$str_date)->count();
            $count_reject =  Application::where('status', config('const.application.status.reject'))->where('created_at','>=',$str_date)->count();
            $count_completed =  Application::where('status', config('const.application.status.completed'))->where('created_at','>=',$str_date)->count();

        }else{

            $str_date = Carbon::now();

            $list_application =  Application::where('status', '!=' , config('const.application.status.draft'))->orderBy('created_at', 'DESC')->paginate(5);
            $count_applying =  Application::where('status', config('const.application.status.applying'))->count();
            $count_approval =  Application::whereBetween('status', [1,98])->count();
            $count_declined =  Application::where('status', config('const.application.status.declined'))->count();
            $count_reject =  Application::where('status', config('const.application.status.reject'))->count();
            $count_completed =  Application::where('status', config('const.application.status.completed'))->count();

        }

        return view('admin.dashboard', compact('list_application','count_applying','count_approval','count_declined','count_reject','count_completed','str_date'));
    }
}
