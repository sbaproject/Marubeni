<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

            $list_application =  Application::where('created_by', $userId)->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->orderBy('created_at', 'DESC')->get();
            $count_applying =  Application::where('status', 0)->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_approval =  Application::whereBetween('status', [1,98])->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_declined =  Application::where('status', -1)->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_reject =  Application::where('status', -2)->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_completed =  Application::where('status', 99)->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();

        }else{

            $list_application =  Application::where('created_by', $userId)->orderBy('created_at', 'DESC')->get();
            $count_applying =  Application::where('status', 0)->count();
            $count_approval =  Application::whereBetween('status', [1,98])->count();
            $count_declined =  Application::where('status', -1)->count();
            $count_reject =  Application::where('status', -2)->count();
            $count_completed =  Application::where('status', 99)->count();

        }

        //dd($list_application);
        return view('user.dashboard', compact('list_application','count_applying','count_approval','count_declined','count_reject','count_completed'));
    }
}
