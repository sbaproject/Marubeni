<?php

namespace App\Http\Controllers\Application\Leave;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Application\ApplicationController;

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

    public function makeValidate($request, &$inputs)
    {
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

        return Validator::make($inputs, $rules, [], $customAttributes);
    }

    public function saveApplicationDetail($request, &$inputs, $application, $applicationId, $loginUser)
    {
        /////////////////////////////////////////////
        // Leaves table
        /////////////////////////////////////////////

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
            'updated_by'        => $loginUser->id,
            'updated_at'        => Carbon::now(),
        ];

        if (empty($application)) {
            $leaveData['application_id'] = $applicationId;
            $leaveData['created_by'] = $loginUser->id;
            $leaveData['created_at'] = Carbon::now();
        }

        DB::table('leaves')->updateOrInsert(['application_id' => $applicationId], $leaveData);
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
