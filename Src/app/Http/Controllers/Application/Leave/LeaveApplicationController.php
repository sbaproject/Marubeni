<?php

namespace App\Http\Controllers\Application\Leave;

use Carbon\Carbon;
use App\Libs\Common;
use App\Models\Leave;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PDF;

class LeaveApplicationController extends Controller
{
    public function create(Request $request)
    {
        // code leaves
        $codeLeaves = config('const.code_leave');

        // paid types
        $paidTypes = config('const.paid_type');

        // logged user
        $user = Auth::user();

        // clear flash input
        // session()->flashInput([]);

        // return view('application.leave.input', compact('codeLeaves', 'paidTypes', 'user'));

        // PDF::setOptions(['defaultFont' => 'Roboto-Black']);
        $pdf = PDF::loadView('pdf', compact('codeLeaves', 'paidTypes'));

        return $pdf->stream('disney.pdf');
    }

    public function store(Request $request)
    {
        // get inputs
        $inputs = $request->all();
        if (!isset($inputs['paid_type'])) {
            $inputs['paid_type'] = null;
        }
        // dd($inputs['input_file']->getClientOriginalName());

        // validate
        $this->doValidate($request, $inputs);

        // save
        $this->doSaveData($request, $inputs);

        // continue create new application after save success
        if (isset($inputs['subsequent'])) {
            return Common::redirectRouteWithAlertSuccess('user.leave.create');
        }
        // back to list application
        return Common::redirectRouteWithAlertSuccess('user.form.index');
    }

    public function show(Request $request, $id)
    {
        // check owner
        $application = Application::findOrFail($id);
        if (Auth::user()->id !== $application->created_by) {
            abort('403');
        }

        // get leave application
        $model = Leave::where('application_id', $id)->first();

        // code leaves
        $codeLeaves = config('const.code_leave');

        // paid types
        $paidTypes = config('const.paid_type');

        // logged user
        $user = Auth::user();

        return view('application.leave.input', compact('codeLeaves', 'paidTypes', 'model', 'id', 'user'));
    }

    public function update(Request $request, $id)
    {
        // check owner
        $mApplication = Application::findOrFail($id);
        if (Auth::user()->id !== $mApplication->created_by) {
            abort('403');
        }

        // get inputs
        $inputs = $request->all();
        if (!isset($inputs['paid_type'])) {
            $inputs['paid_type'] = null;
        }

        // validate
        $this->doValidate($request, $inputs);

        // save
        $this->doSaveData($request, $inputs, $mApplication);

        // continue create new application after save success
        if (isset($inputs['subsequent'])) {
            return Common::redirectRouteWithAlertSuccess('user.leave.create');
        }
        // back to list application
        return Common::redirectRouteWithAlertSuccess('user.form.index');
    }

    public function doValidate($request, $inputs)
    {
        if (isset($inputs['apply']) || isset($inputs['draft'])) {
            $rules = [];
            // attached file
            if ($request->file('input_file')) {
                $rules['input_file'] = 'mimes:txt,pdf,jpg,jpeg,png|max:200';
            }
            if (isset($inputs['apply'])) {
                // rules for validation
                $rules['code_leave'] = 'required_select';
                // only for SICKLEAVE
                if ($inputs['code_leave'] == config('const.code_leave.SL')) {
                    $rules['paid_type'] = 'required_select';
                }
            }
            // dd($inputs);
            $validator = Validator::make($inputs, $rules);
            $validator->validate();
        }
    }

    public function doSaveData($request, $inputs, $mApplication = null)
    {
        DB::transaction(function () use ($request, $inputs, $mApplication) {
            // get user
            $user = Auth::user();

            /**-------------------------
             * create application
             *-------------------------*/

            // get status
            if (isset($inputs['apply'])) {
                $status = config('const.application.status.applying');
            } else if (isset($inputs['draft'])) {
                $status = config('const.application.status.draft');
            }

            // prepare data
            $application = [
                'status' => $status,
                'updated_by' => $user->id,
                'updated_at' => Carbon::now(),
            ];

            // for new
            if (!$request->id) {

                // get current step
                $currentStep = 2; // [leave form] default = 2

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

                $application['form_id'] = $formId;
                $application['group_id'] = $group->id;
                $application['current_step'] = $currentStep;
                $application['created_by'] = $user->id;
                $application['created_at'] = Carbon::now();
            }

            // add
            if (!$request->id) {
                $applicationId = DB::table('applications')->insertGetId($application);
            }
            // update
            else {
                DB::table('applications')->where('id', $request->id)->update($application);
            }

            /**-------------------------
             * create [Leave Application] detail
             *-------------------------*/
            // delete old file
            if ($request->id) {
                $leave = Leave::where('application_id', $mApplication->id)->first();
                $filePath = $leave->file_path;
                // attchached file was changed
                if ($inputs['file_path'] != $filePath) {
                    if (!empty($leave->file_path)) {
                        if (Storage::exists($leave->file_path)) {
                            Storage::delete($leave->file_path);
                        }
                    }
                    $filePath = null;
                }
            }
            // upload new attached file
            if ($request->file('input_file')) {
                // $extension = '.' . $request->file('input_file')->extension();
                $fileName = time() . $user->id . '_' . $request->file('input_file')->getClientOriginalName();
                $filePath = $request->file('input_file')->storeAs('uploads/application/', $fileName);
            }

            // prepare leave data
            $leaveData = [
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
                'file_path' => isset($filePath) ? $filePath : null,
                'days_use' => $inputs['days_use'],
                'times_use' => $inputs['times_use'],
                'updated_by' => $user->id,
                'updated_at' => Carbon::now(),
            ];
            // for new
            if (!$request->id) {
                $leaveData['application_id'] = $applicationId;
                $leaveData['created_by'] = $user->id;
                $leaveData['created_at'] = Carbon::now();
            }

            DB::table('leaves')->updateOrInsert(['application_id' => $request->id], $leaveData);
        });
    }

    public function pdf()
    {
    }
}
