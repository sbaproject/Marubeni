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

            $list_application =  Application::where('created_by', $userId)->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->orderBy('created_at', 'DESC')->paginate(5);
            $count_applying =  Application::where('created_by', $userId)->where('status', 0)->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_approval =  Application::where('created_by', $userId)->whereBetween('status', [1,98])->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_declined =  Application::where('created_by', $userId)->where('status', -1)->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_reject =  Application::where('created_by', $userId)->where('status', -2)->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();
            $count_completed =  Application::where('created_by', $userId)->where('status', 99)->where('created_at','>=',$str_date)->where('created_at','<=',$end_date)->count();

        }else{

            session()->forget('str_date');
            session()->forget('end_date');

            $list_application =  Application::where('created_by', $userId)->orderBy('created_at', 'DESC')->paginate(5);
            $count_applying =  Application::where('created_by', $userId)->where('status', 0)->count();
            $count_approval =  Application::where('created_by', $userId)->whereBetween('status', [1,98])->count();
            $count_declined =  Application::where('created_by', $userId)->where('status', -1)->count();
            $count_reject =  Application::where('created_by', $userId)->where('status', -2)->count();
            $count_completed =  Application::where('created_by', $userId)->where('status', 99)->count();

        }

        $applications = DB::table('applications')
            ->join('groups', 'applications.group_id', '=', 'groups.id')
            ->join('flows', 'groups.id', '=', 'flows.group_id')
            ->join('steps', 'flows.id', '=', 'steps.flow_id')
            ->select('steps.approver_id')
            ->orderBy('groups.id', 'asc')
            ->where('applications.created_by', $userId)
            ->whereNull('applications.deleted_at')
            ->get();
        
        //  dd($applications);
        return view('user.dashboard', compact('list_application','count_applying','count_approval','count_declined','count_reject','count_completed'));
    }
}
