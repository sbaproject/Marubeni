<?php

namespace App\Http\Controllers\Application\Leave;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Libs\Common;
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

        $previewFlg = false;
        $inProgressFlg = false;

        // clear flash input
        // session()->flashInput([]);

        return view('application.leave.input', compact('codeLeaves', 'paidTypes', 'user', 'previewFlg', 'inProgressFlg'));
    }

    public function store(Request $request)
    {
        // get inputs
        $inputs = $this->getInputs($request);

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

        return $this->doRedirect($inputs);
    }

    public function show(Request $request, $id)
    {
        // check owner
        $application = Application::findOrFail($id);
        if (Auth::user()->id !== $application->created_by) {
            abort('403');
        }

        if (empty($application->leave)) {
            abort(404);
        }

        // if application is in approval progress => NOT ALLOWS EDIT
        $previewFlg = ($application->status != config('const.application.status.draft')
            && $application->status != config('const.application.status.applying')
            && $application->status != config('const.application.status.declined'))
            || ($application->current_step > config('const.application.step_type.application')
                && $application->status != config('const.application.status.declined'));

        // disabled draft button if application was applied.
        $inProgressFlg = $application->status != config('const.application.status.draft');
        // if(!$previewFlg){
        //     $inProgressFlg = DB::table('history_approval')->where('application', $id)->exists();
        // }

        // get leave application
        // $model = Leave::where('application_id', $id)->first();

        return view('application.leave.input', compact('application', 'previewFlg', 'inProgressFlg'));
    }

    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        // get inputs
        $inputs = $this->getInputs($request);

        // check post method
        if (isset($inputs['apply']) || isset($inputs['draft']) || isset($inputs['pdf'])) {
            // export pdf
            if (isset($inputs['pdf'])) {
                return $this->pdf($request, $inputs, $application);
            }
            if (Auth::user()->id !== $application->created_by) {
                abort('403');
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
        $msgErr = $this->doSaveData($request, $inputs, $application);
        if (!empty($msgErr)) {
            return Common::redirectBackWithAlertFail($msgErr)->with('inputs', $inputs);
        }

        return $this->doRedirect($inputs);
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

            // get current step
            $currentStep = config('const.budget.step_type.application');

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

            if (empty($group) && isset($inputs['apply'])) {
                throw new NotFoundFlowSettingException();
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
                $extension = '.' . $request->file('input_file')->extension();
                // $fileName = time() . $user->id . '_' . $request->file('input_file')->getClientOriginalName();
                $fileName = time() . $user->id . $extension;
                $filePath = $request->file('input_file')->storeAs('uploads/application/', $fileName);
            }

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

    public function doRedirect($inputs)
    {
        // continue create new application after save success
        // if (isset($inputs['subsequent']) && $inputs['subsequent'] == true) {
        //     return Common::redirectRouteWithAlertSuccess('user.leave.create');
        // }
        // back to list application
        if (isset($inputs['apply'])) {
            $msg = __('msg.application.success.apply_ok');
        } elseif (isset($inputs['draft'])) {
            $msg = __('msg.application.success.draft_ok');
        }
        
        return Common::redirectRouteWithAlertSuccess('user.form.index', $msg);
    }

    public function pdf($request, $inputs, $application = null)
    {
        if ($application != null) {

            $loggedUser = Auth::user();
            // get list of approver (include TO & CC)
            $approvers = DB::table('steps')
                ->select('steps.approver_id')
                ->where('steps.flow_id', function ($query) use ($request, $loggedUser) {
                    $query->select('steps.flow_id')
                        ->from('applications')
                        ->join(
                            'steps',
                            'steps.group_id',
                            'applications.group_id'
                        )
                        ->where('steps.approver_id', '=', $loggedUser->id)
                        ->where('applications.id', $request->id)
                        ->where('applications.deleted_at', '=', null)
                        ->limit(1);
                })
                ->where('steps.step_type', $application->current_step)
                ->first();

            // check logged user has permission to access
            // if logged user is not owner of application and also not approval user(TO or CC).
            if (empty($approvers) && $application->created_by !== $loggedUser->id) {
                abort(403);
            }
        }

        if (empty($application)) {
            $inputs['applicant'] = Auth::user();
        } else {
            $inputs['applicant'] = $application->applicant;
        }

        // PDF::setOptions(['defaultFont' => 'Roboto-Black']);
        $pdf = PDF::loadView('application.leave.pdf', compact('application', 'inputs'));

        // preview pdf
        return $pdf->stream('Leave_Application.pdf');
        // download
        // return $pdf->download('Leave_Application.pdf');
    }

    public function preview(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $previewFlg = true;

        if (empty($application->leave)) {
            abort(404);
        }

        $loggedUser = Auth::user();

        // get list of approver (include TO & CC)
        $approvers = DB::table('steps')
            ->select('steps.approver_id')
            ->where('steps.flow_id', function ($query) use ($id, $loggedUser) {
                $query->select('steps.flow_id')
                    ->from('applications')
                    ->join('steps', 'steps.group_id', 'applications.group_id')
                    ->where('steps.approver_id', '=', $loggedUser->id)
                    ->where('applications.id', $id)
                    ->where('applications.deleted_at', '=', null)
                    ->limit(1);
            })
            ->where('steps.step_type', $application->current_step)
            ->first();

        // check logged user has permission to access
        // if logged user is not owner of application and also not approval user(TO or CC).
        if (empty($approvers) && $application->created_by !== $loggedUser->id) {
            abort(403);
        }

        return view('application.leave.input', compact('application', 'previewFlg'));
    }

    public function previewPdf(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $inputs = $request->input();

        if (isset($inputs['pdf'])) {
            return $this->pdf($request, $inputs, $application);
        } else {
            abort(404);
        }
    }

    public function getInputs($request)
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
