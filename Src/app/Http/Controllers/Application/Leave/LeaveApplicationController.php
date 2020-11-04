<?php

namespace App\Http\Controllers\Application\Leave;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LeaveApplicationController extends Controller
{
    public function create()
    {
        // code leaves
        $codeLeaves = config('const.code_leave');

        // paid types
        $paidTypes = config('const.paid_type');

        return view('application.leave.create', compact('codeLeaves', 'paidTypes'));
    }

    public function store(Request $request)
    {
        // get inputs
        $inputs = $request->input();

        // dd($inputs);

        DB::transaction(function () use ($inputs) {
            // get user
            $user = Auth::user();

            // get [leave form] id
            $formId = config('const.form.leave');

            // get applicant
            $applicant = DB::table('applicants')
                ->select('id')
                ->where([
                    ['role', $user->role],
                    ['location', $user->location],
                    ['department_id', $user->department_id],
                ])
                ->whereNull('deleted_at')
                ->first();

            // get group
            $group = DB::table('groups')
                ->select('id')
                ->where('applicant_id', $applicant->id)
                ->whereNull(['budget_id', 'deleted_at'])
                ->first();

            /**-------------------------
             * create application
             *-------------------------*/

            // get status
            if (isset($inputs['apply'])) {
                $status = config('const.application.status.applying');
            } else if (isset($inputs['draft'])) {
                $status = config('const.application.status.draft');
            }

            // get current step
            $currentStep = 2; // [leave form] default = 2

            // application data
            $application = [
                'form_id' => $formId,
                'group_id' => $group->id,
                'current_step' => $currentStep,
                'status' => $status,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $applicationId = DB::table('applications')->insertGetId($application);

            /**
             * create [Leave Application] detail
             */
            $leaveData = [
                'application_id' => $applicationId,
                'code_leave' => $inputs['code_leave'],
                'paid_type' => $inputs['paid_type'],
                'reason_leave' => $inputs['reason_leave'],
                'date_from' => $inputs['date_from'],
                'date_to' => $inputs['date_to'],
                'time_day' => $inputs['time_day'],
                'time_from' => $inputs['time_from'],
                'time_to' => $inputs['time_to'],
                'maternity_from' => $inputs['maternity_from'],
                'maternity_to' => $inputs['maternity_to'],
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            DB::table('leaves')->insert($leaveData);
        });

        return 'OK';
    }
}
