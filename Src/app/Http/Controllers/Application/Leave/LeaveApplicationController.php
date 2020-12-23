<?php

namespace App\Http\Controllers\Application\Leave;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Entertainment\NotFoundFlowSettingException;
use App\Http\Controllers\ApplicationController;

class LeaveApplicationController extends ApplicationController
{
    public function __construct()
    {
        $this->formType = config('const.form.leave');

        parent::__construct();
    }

    protected function create()
    {
        parent::create();

        return view($this->viewInputName, $this->currentCompatData);
    }

    protected function show($id)
    {
        parent::show($id);

        return view($this->viewInputName, $this->currentCompatData);
    }

    protected function checkEmptyApplication($application)
    {
        if (empty($application->leave)) {
            abort(404);
        }
    }

    protected function doValidate($request, &$inputs)
    {
        if (isset($inputs['apply']) || isset($inputs['draft'])) {
            $rules = [];
            // attached file
            if ($request->file('input_file')) {
                $rules['input_file'] = config('const.rules.attached_file');
            }
            if (isset($inputs['apply'])) {

                $rules['code_leave'] = 'required_select';
                $rules['reason_leave'] = 'required';

                if ($inputs['code_leave'] !== null && $inputs['code_leave'] != "empty") {
                    if ($inputs['code_leave'] == config('const.code_leave.ML')) {
                        $rules['maternity_from'] = 'required';
                        $rules['maternity_to'] = 'required';
                    } else {
                        $rules['date_from'] = 'required';
                        $rules['date_to'] = 'required';
                        if (
                            $inputs['code_leave'] == config('const.code_leave.AL')
                            || $inputs['paid_type'] == config('const.paid_type.AL')
                        ) {
                            $rules['days_use'] = 'required';
                        }
                        if ($inputs['code_leave'] == config('const.code_leave.SL')) {
                            $rules['paid_type'] = 'required_select';
                        }
                    }
                }
            }

            $customAttributes = [
                'reason_leave'      => __('label.leave.caption.reason_leave'),
                'date_from'         => __('label.leave.caption.date_from'),
                'date_to'           => __('label.leave.caption.date_to'),
                'maternity_from'    => __('label.leave.caption.maternity_from'),
                'maternity_to'      => __('label.leave.caption.maternity_to'),
                'days_use'          => __('label.leave.caption.days_use'),
            ];

            $validator = Validator::make($inputs, $rules, [], $customAttributes);
            if ($validator->fails()) {
                unset($inputs['input_file']);
                return $validator;
            }
        }
    }

    protected function doSaveData($request, &$inputs, $app = null)
    {
        $msgErr = '';

        DB::beginTransaction();

        try {
            // get user
            $user = Auth::user();

            /**-------------------------
             * create application
             *-------------------------*/

            // get [leave form] id
            $formId = config('const.form.leave');
            // get status
            $status = $this->getActionType($inputs);
            // get current step
            $currentStep = config('const.budget.step_type.application');
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

            if (empty($group) && isset($inputs['apply'])) {
                throw new NotFoundFlowSettingException();
            }

            $filePath = $this->uploadAttachedFile($request, $inputs, $app, $user);

            // prepare data
            $application = [
                'form_id'       => $formId,
                'group_id'      => $group->id ?? null,
                'current_step'  => $currentStep,
                'status'        => $status,
                'subsequent'    => $inputs['subsequent'],
                'file_path'     => $filePath ?? null,
                'updated_by'    => $user->id,
                'updated_at'    => Carbon::now()
            ];

            // add
            if (!$request->id) {
                $application['created_by'] = $user->id;
                $application['created_at'] = Carbon::now();

                $applicationId = DB::table('applications')->insertGetId($application);
            }
            // update
            else {
                DB::table('applications')->where('id', $request->id)->update($application);
            }

            /**-------------------------
             * create [Leave Application] detail
             *-------------------------*/

            // prepare leave data
            $leaveData = [
                'code_leave'        => $inputs['code_leave'] !== 'empty' ? $inputs['code_leave'] : null,
                'paid_type'         => $inputs['paid_type'],
                'reason_leave'      => $inputs['reason_leave'],
                'date_from'         => $inputs['date_from'],
                'date_to'           => $inputs['date_to'],
                'time_day'          => $inputs['time_day'],
                'time_from'         => $inputs['time_from'],
                'time_to'           => $inputs['time_to'],
                'maternity_from'    => $inputs['maternity_from'],
                'maternity_to'      => $inputs['maternity_to'],
                'days_use'          => $inputs['days_use'],
                'times_use'         => $inputs['times_use'],
                'updated_by'        => $user->id,
                'updated_at'        => Carbon::now(),
            ];
            // for new
            if (!$request->id) {
                $leaveData['application_id'] = $applicationId;
                $leaveData['created_by'] = $user->id;
                $leaveData['created_at'] = Carbon::now();
            }

            DB::table('leaves')->updateOrInsert(['application_id' => $request->id], $leaveData);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            unset($inputs['input_file']);
            if ($ex instanceof NotFoundFlowSettingException) {
                $msgErr = $ex->getMessage();
            } else {
                $msgErr = __('msg.save_fail');
            }
        }

        return $msgErr;
    }

    protected function preview(Request $request, $id)
    {
        parent::preview($request, $id);

        return view($this->viewInputName, $this->currentCompatData);
    }

    protected function getInputs($request)
    {
        $inputs = $request->all();

        if (!isset($inputs['paid_type'])) {
            $inputs['paid_type'] = null;
        }

        if (!isset($inputs['subsequent'])) {
            $inputs['subsequent'] = null;
        }

        return $inputs;
    }
}
