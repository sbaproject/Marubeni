<?php

namespace App\Http\Controllers\Application\Leave;

use PDF;
use Exception;
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
use App\Exceptions\Entertainment\NotFoundFlowSettingException;

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

        return view('application.leave.input', compact('codeLeaves', 'paidTypes', 'user'));
    }

    public function store(Request $request)
    {
        // get inputs
        $inputs = $request->all();
        if (!isset($inputs['paid_type'])) {
            $inputs['paid_type'] = null;
        }

        // check post method
        if (isset($inputs['apply']) || isset($inputs['draft']) || isset($inputs['pdf'])) {
            // export pdf
            if (isset($inputs['pdf'])) {
                return $this->pdf($request, $inputs);
            }
        } else {
            abort(404);
        }

        // validate
        $validator = $this->doValidate($request, $inputs);
        if (!empty($validator)) {
            return redirect()->back()->with('inputs', $inputs)->withErrors($validator);
        }

        // save
        $msgErr = $this->doSaveData($request, $inputs);
        if (!empty($msgErr)) {
            return Common::redirectBackWithAlertFail($msgErr)->with('inputs', $inputs);
        }

        // continue create new application after save success
        return $this->doRedirect($inputs);
    }

    public function show(Request $request, $id)
    {
        // check owner
        $application = Application::findOrFail($id);
        if (Auth::user()->id !== $application->created_by) {
            abort('403');
        }

        // get leave application
        // $model = Leave::where('application_id', $id)->first();

        // code leaves
        $codeLeaves = config('const.code_leave');

        // paid types
        $paidTypes = config('const.paid_type');

        // logged user
        $user = Auth::user();

        return view('application.leave.input', compact('codeLeaves', 'paidTypes', 'application', 'user'));
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

        // check post method
        if (isset($inputs['apply']) || isset($inputs['draft']) || isset($inputs['pdf'])) {
            // export pdf
            if (isset($inputs['pdf'])) {
                return $this->pdf($request, $inputs, $mApplication);
            }
        } else {
            abort(404);
        }

        // validate
        $validator = $this->doValidate($request, $inputs);
        if (!empty($validator)) {
            return redirect()->back()->with('inputs', $inputs)->withErrors($validator);
        }

        // save
        $msgErr = $this->doSaveData($request, $inputs, $mApplication);
        if (!empty($msgErr)) {
            return Common::redirectBackWithAlertFail($msgErr)->with('inputs', $inputs);
        }

        // continue create new application after save success
        if (isset($inputs['subsequent'])) {
            return Common::redirectRouteWithAlertSuccess('user.leave.create');
        }
        // back to list application
        return Common::redirectRouteWithAlertSuccess('user.form.index');
    }

    public function doValidate($request, &$inputs)
    {
        if (isset($inputs['apply']) || isset($inputs['draft'])) {
            $rules = [];
            // attached file
            if ($request->file('input_file')) {
                $rules['input_file'] = 'mimes:txt,pdf,jpg,jpeg,png|max:200';
            }
            if (isset($inputs['apply'])) {
                $rules['code_leave'] = 'required_select';
                // only for SICKLEAVE
                if ($inputs['code_leave'] == config('const.code_leave.SL')) {
                    $rules['paid_type'] = 'required_select';
                }
            }
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) {
                unset($inputs['input_file']);
                return $validator;
            }
        }
    }

    public function doSaveData($request, &$inputs, $app = null)
    {
        $msgErr = '';

        DB::beginTransaction();

        try {
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
                $currentStep = config('const.budget.step_type.settlement'); // [leave form] default = 2

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

                if (empty($group)) {
                    throw new NotFoundFlowSettingException();
                }

                $application['form_id'] = $formId;
                $application['group_id'] = $group->id;
                $application['current_step'] = $currentStep;
                $application['created_by'] = $user->id;
                $application['created_at'] = Carbon::now();
            }

            // delete old file
            if (!empty($app)) {
                // $leave = Leave::where('application_id', $mApplication->id)->first();
                $filePath = $app->file_path;
                // attchached file was changed
                if ($inputs['file_path'] != $filePath) {
                    if (!empty($app->file_path)) {
                        if (Storage::exists($app->file_path)) {
                            Storage::delete($app->file_path);
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

            $application['file_path'] = isset($filePath) ? $filePath : null;

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

            // prepare leave data
            $leaveData = [
                'code_leave' => $inputs['code_leave'] !== 'empty' ? $inputs['code_leave'] : null,
                'paid_type' => $inputs['paid_type'],
                'reason_leave' => $inputs['reason_leave'],
                'date_from' => $inputs['date_from'],
                'date_to' => $inputs['date_to'],
                'time_day' => $inputs['time_day'],
                'time_from' => $inputs['time_from'],
                'time_to' => $inputs['time_to'],
                'maternity_from' => $inputs['maternity_from'],
                'maternity_to' => $inputs['maternity_to'],
                // 'file_path' => isset($filePath) ? $filePath : null,
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

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            unset($inputs['input_file']);
            if ($ex instanceof NotFoundFlowSettingException) {
                $msgErr = $ex->getMessage();
            } else {
                $msgErr = $ex->getMessage();
            }
        }

        return $msgErr;
    }

    public function doRedirect($inputs)
    {
        // continue create new application after save success
        if (isset($inputs['subsequent'])) {
            return Common::redirectRouteWithAlertSuccess('user.leave.create');
        }
        // back to list application
        return Common::redirectRouteWithAlertSuccess('user.form.index');
    }

    public function pdf($request, $inputs)
    {
        // get logged user
        $user = Auth::user();

        // PDF::setOptions(['defaultFont' => 'Roboto-Black']);
        $pdf = PDF::loadView('application.leave.pdf', compact('user', 'inputs'));

        // preview pdf
        return $pdf->stream('Leave_Application.pdf');
        // download
        // return $pdf->download('Leave_Application.pdf');
    }
}
