<?php

namespace App\Http\Controllers\Application\Leave;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Libs\Common;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $inputs = $request->all();
        if (!isset($inputs['paid_type'])) {
            $inputs['paid_type'] = null;
        }

        // dd($request->all());

        // validate
        $this->doValidate($inputs);

        // save
        return $this->doSaveData($inputs);
    }

    public function doValidate($inputs)
    {
        if (isset($inputs['apply'])) {
            // rules for validation
            $rules = [
                'code_leave' => 'required_select'
            ];
            // only for SICKLEAVE
            if ($inputs['code_leave'] == config('const.code_leave.SL')) {
                $rules['paid_type'] = 'required_select';
            }
            // attached file
            if(isset($inputs['file_path'])){
                $rules['file_path'] = 'mimes:jpg|max:10240';
            }

            $validator = Validator::make($inputs, $rules);
            $validator->validate();
        }
    }

    public function doSaveData($inputs)
    {
        DB::transaction(function () use ($inputs) {
            // get user
            $user = Auth::user();

            // get [leave form] id
            $formId = config('const.form.leave');

            // get group
            $group = DB::table('groups')
                ->select('groups.*')
                ->join('applicants', function ($join) use ($user) {
                    $join->on('groups.applicant_id', '=', 'applicants.id')
                        ->where('applicants.role', '=', $user->role)
                        ->where('applicants.location', '=', $user->location)
                        ->where('applicants.department_id', '=', $user->department_id)
                        ->where('applicants.deleted_at', '=', null);
                })
                ->where('groups.budget_id', '=', null)
                ->where('groups.deleted_at', '=', null)
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

            // prepare data
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

            /**-------------------------
             * create [Leave Application] detail
             *-------------------------*/
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

        if (isset($inputs['subsequent'])) {
            return Common::redirectRouteWithAlertSuccess('user.leave.create');
        }

        return Common::redirectRouteWithAlertSuccess('user.form.index');
    }
}
