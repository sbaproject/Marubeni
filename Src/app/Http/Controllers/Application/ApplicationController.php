<?php

namespace App\Http\Controllers\Application;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Libs\Common;
use App\Models\Step;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\HistoryApproval;
use App\Jobs\SendMailBackGround;
use Illuminate\Support\Facades\DB;
use App\Mail\ApplicationNoticeMail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\NotFoundFlowSettingException;

class ApplicationController extends Controller
{
    // channel logging
    protected const LOG_CHANNEL = 'application';

    // flag logging
    protected const LOG_FLG = 'post_application';

    // type of application
    protected $formType;

    // type form name
    protected $formTypeName;

    // current compat data to passing to view
    protected $currentCompatData;

    // view name of input page
    protected $viewInputName;

    // routing name to redirect after save
    private $routeNameRedirectAfterSave = 'user.form.index';

    public function __construct()
    {
        $this->formTypeName = $this->getFormTypeName();

        $this->viewInputName = "application_{$this->formTypeName}_input";
    }

    protected function create()
    {
        // preview mode (all inputs is in readonly mode)
        $previewFlg = false;

        // application is in approval progressing
        $inProgressFlg = false;

        // set default compact data
        $this->currentCompatData = compact('previewFlg', 'inProgressFlg');
    }

    protected function store(Request $request)
    {
        return $this->updateData($request, null);
    }

    protected function show($id)
    {
        $application = Application::findOrFail($id);

        // check available application
        $this->checkEmptyApplication($application);

        // check valid permission accessing to application
        if (Auth::user()->id !== $application->created_by) {
            if (Gate::denies('admin-gate')) {
                abort(403);
            } else {
                $showWithAdminFlg = true;
            }
        }

        // not allows editing
        $previewFlg = !$this->checkEditableApplication($application) || isset($showWithAdminFlg) || Common::detectMobile();

        // disabled draft button if application was applied.
        $inProgressFlg = $application->status != config('const.application.status.draft');
        // if(!$previewFlg){
        //     $inProgressFlg = DB::table('history_approval')->where('application', $id)->exists();
        // }

        $this->currentCompatData = compact('application', 'previewFlg', 'inProgressFlg');
    }

    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        return $this->updateData($request, $application);
    }

    protected function doRedirectSaveOk($inputs)
    {
        // back to list application
        if (isset($inputs['apply'])) {
            $msg = __('msg.application_success_apply_ok');
        } elseif (isset($inputs['draft'])) {
            $msg = __('msg.application_success_draft_ok');
        }
        return Common::redirectRouteWithAlertSuccess($this->routeNameRedirectAfterSave, $msg);
    }

    protected function checkEmptyApplication($application)
    {
    }

    private function updateData($request, $application)
    {
        // get inputs
        $inputs = $this->getInputs($request);

        // check post method
        if (isset($inputs['apply']) || isset($inputs['draft']) || isset($inputs['pdf'])) {
            // export pdf
            if (isset($inputs['pdf'])) {
                return $this->pdf($request, $inputs, $application);
            }
            // only owner able to edit (edit mode)
            if (!empty($application) && Auth::user()->id !== $application->created_by) {
                abort(403);
            }
            // check valid status of application to determine editable or not
            if (!isset($inputs['draft']) && !empty($application)) {
                if (!$this->checkEditableApplication($application)) {
                    return Common::redirectRouteWithAlertFail(
                        $this->routeNameRedirectAfterSave,
                        __('msg.application_error_inprogress_not_allow_edit')
                    );
                }
            }
        } else {
            abort(404);
        }

        // validate
        $validator = $this->doValidate($request, $inputs);
        if (!empty($validator)) {
            Common::setAlertFail(__('msg.validation_fail'));
            return redirect()->back()->with('inputs', $inputs)->withErrors($validator);
        }

        // save data
        $msgErr = $this->doSaveData($request, $inputs, $application);
        if (!empty($msgErr)) {
            return Common::redirectBackWithAlertFail($msgErr)->with('inputs', $inputs);
        }

        return $this->doRedirectSaveOk($inputs);
    }

    private function doValidate($request, &$inputs)
    {
        if (isset($inputs['apply']) || isset($inputs['draft'])) {

            $validator = $this->makeValidate($request, $inputs);

            if ($validator->fails()) {
                unset($inputs['input_file']);
                return $validator;
            }
        }
    }

    private function doSaveData($request, &$inputs, $application = null)
    {
        $msgErr = '';

        DB::beginTransaction();

        try {
            // get logged user
            $loginUser = Auth::user();

            // Applications table
            $newApplication = $this->saveApplicationMaster($request, $inputs, $application, $loginUser);

            // Application Detail table
            $this->saveApplicationDetail($request, $inputs, $application, $newApplication['id'], $loginUser);


            // commit DB
            DB::commit();

            // send mail to first approver (TO) & CC of each step
            $this->sendNoticeMail($inputs, $newApplication);
        } catch (Exception $ex) {

            DB::rollBack();
            unset($inputs['input_file']);

            // log stacktrace
            report($ex);

            if ($ex instanceof NotFoundFlowSettingException) {
                $msgErr = $ex->getMessage();
            } else {
                $msgErr = __('msg.save_fail');
            }
        }

        return $msgErr;
    }

    private function saveApplicationMaster($request, $inputs, $app, $loginUser)
    {
        // get type form of application
        $formId = $this->formType;
        // get status
        $status = $this->getActionType($inputs);
        // get current step
        $currentStep = $this->getCurrentStep($app);

        if ($this->formType == config('const.form.leave')) {
            $budgetType = config('const.budget.budget_type.leave');
            $budgetPosition = null;
            $budgetTypeCompare = null;
        } elseif ($this->formType == config('const.form.biz_trip')) {
            $budgetType = config('const.budget.budget_type.business');
            $budgetPosition = $inputs['budget_position'];
            $budgetTypeCompare = null;
        } elseif ($this->formType == config('const.form.entertainment')) {
            $budgetType = config('const.budget.budget_type.entertainment');
            $budgetPosition = $inputs['budget_position'];
            $budgetTypeCompare = $this->getBudgetTypeCompare($inputs, $budgetType, $currentStep, $budgetPosition);
        }

        // get group
        $group = $this->getGroup($loginUser, $currentStep, $budgetType, $budgetPosition, $budgetTypeCompare);
        // not found available flow setting
        if (empty($group) && isset($inputs['apply'])) {
            throw new NotFoundFlowSettingException();
        }

        // get attached file
        $filePath = $this->uploadAttachedFile($request, $inputs, $app, $loginUser);

        // prepare data
        $application = [
            'form_id'           => $formId,
            'group_id'          => $group->id ?? null,
            'current_step'      => $currentStep,
            'status'            => $status,
            'subsequent'        => $inputs['subsequent'],
            'subsequent_reason' => $inputs['subsequent_reason'],
            'budget_position'   => $budgetPosition,
            'file_path'         => $filePath ?? null,
            'updated_by'        => $loginUser->id,
            'updated_at'        => Carbon::now()
        ];

        // save application
        if (empty($app)) {
            $application['application_no']  = Application::makeApplicationNoByAutoIncrementId($formId);
            $application['created_by']      = $loginUser->id;
            $application['created_at']      = Carbon::now();

            $applicationId = DB::table('applications')->insertGetId($application);
        } else {
            DB::table('applications')->where('id', $app->id)->update($application);
        }

        $application['id'] = $applicationId ?? $app->id;

        return $application;
    }

    private function checkValidApproverOfApplication($request, $application, $loginUser)
    {
        $approver = DB::table('steps')
            ->select('steps.approver_id')
            ->where('steps.flow_id', function ($query) use ($request, $loginUser) {
                $query->select('steps.flow_id')
                    ->from('applications')
                    ->join(
                        'steps',
                        'steps.group_id',
                        'applications.group_id'
                    )
                    ->where('steps.approver_id', '=', $loginUser->id)
                    ->where('applications.id', $request->id)
                    ->where('applications.deleted_at', '=', null)
                    ->limit(1);
            })
            ->where('steps.step_type', $application->current_step)
            ->first();

        return !empty($approver);
    }

    private function checkEditableApplication($application)
    {
        return ($application->status == config('const.application.status.draft')
            ||  ($application->status == config('const.application.status.applying') && $application->current_step == config('const.application.step_type.application'))
            ||  $application->status == config('const.application.status.declined'));
    }

    private function getFormTypeName()
    {
        if ($this->formType == config('const.form.leave')) {
            return 'leave';
        } elseif ($this->formType == config('const.form.biz_trip')) {
            return 'business';
        } elseif ($this->formType == config('const.form.entertainment')) {
            return 'entertainment';
        }
    }

    private function getActionType($inputs)
    {
        if (isset($inputs['apply'])) {
            return config('const.application.status.applying');
        } else if (isset($inputs['draft'])) {
            return config('const.application.status.draft');
        }
    }

    private function getCurrentStep($application)
    {
        if (!empty($application)) {
            return $application->current_step;
        } else {
            return config('const.budget.step_type.application');
        }
    }

    private function getGroup($user, $currentStep = null, $budgetType = null, $budgetPosition = null, $budgetTypeCompare = null)
    {
        $group = DB::table('groups')
            ->select('groups.*')
            ->join(
                'applicants',
                function ($join) use ($user) {
                    $join->on('groups.applicant_id', '=', 'applicants.id')
                        ->where('applicants.role', $user->role)
                        ->where('applicants.location', $user->location)
                        ->where('applicants.department_id', $user->department_id)
                        ->whereNull('applicants.deleted_at');
                }
            )
            ->when(
                $this->formType == config('const.form.biz_trip') || $this->formType == config('const.form.entertainment'),
                function ($query) use ($currentStep, $budgetType, $budgetPosition) {
                    $query->join('budgets', function ($join) use ($currentStep, $budgetType, $budgetPosition) {
                        $join->on('groups.budget_id', '=', 'budgets.id')
                            ->where('budgets.budget_type', $budgetType)
                            ->where('budgets.step_type', $currentStep)
                            ->where('budgets.position', $budgetPosition)
                            ->whereNull('budgets.deleted_at');
                    });
                }
            )
            ->when(
                $this->formType == config('const.form.entertainment'),
                function ($query) use ($budgetTypeCompare) {
                    $query->where('groups.budget_type_compare', $budgetTypeCompare)
                        ->whereNull('groups.deleted_at');
                }
            )
            ->when($this->formType == config('const.form.leave'), function ($query) {
                $query->whereNull('groups.budget_id')
                    ->whereNull('groups.budget_type_compare');
            })
            ->whereNull('groups.deleted_at')
            ->first();

        return $group;
    }

    private function uploadAttachedFile($request, $inputs, $application, $loginUser)
    {
        // delete old file
        if (!empty($application)) {
            // $leave = Leave::where('application_id', $mApplication->id)->first();
            $filePath = $application->file_path;
            // attchached file was changed
            if ($inputs['file_path'] != $filePath) {
                if (!empty($application->file_path)) {
                    if (Storage::exists($application->file_path)) {
                        Storage::delete($application->file_path);
                    }
                }
                $filePath = null;
            }
        }
        // upload new attached file
        if ($request->file('input_file')) {
            $extension = '.' . $request->file('input_file')->extension();
            // $fileName = time() . $user->id . '_' . $request->file('input_file')->getClientOriginalName();
            $fileName = time() . $loginUser->id . $extension;
            $filePath = $request->file('input_file')->storeAs('uploads/application/', $fileName);
        }

        return $filePath ?? null;
    }

    private function sendNoticeMail($inputs, $application)
    {
        if (!isset($inputs['apply'])) {
            return;
        }

        // get approvers(TO && CC) for application depends on current step of application
        $nextApprovers = Step::getApproversByGroupId($application['group_id']);

        foreach ($nextApprovers as $item) {
            // just get next first approver(TO)
            if ($item->approver_type == config('const.approver_type.to')) {
                if (isset($firstToFlg)) {
                    continue;
                }
                $firstToFlg = true;
            }
            // CC is multi
            $formType = trans("label.form.{$application['form_id']}", [], 'en');
            $stepName = trans("label.step_type.{$application['current_step']}", [], 'en');
            $title = "New {$formType} Application has been applied for {$stepName} Step!";
            $msgParams = [
                'approver_type' => $item->approver_type,
                'form_type' => $formType,
                'step_name' => $stepName,
                'applicant_name' => Auth::user()->name,
                'applicant_location' => Auth::user()->location,
                'department_name' => Auth::user()->department->name,
                'url' => route('user.approval.show', $application['id']),
            ];
            Common::sendApplicationNoticeMail(
                'mails.application_first_to_all_cc_by_step',
                $title,
                $item->approver_mail,
                [],
                $msgParams
            );
        }
    }

    protected function preview(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $this->checkEmptyApplication($application);

        $loginUser = Auth::user();
        $previewFlg = true;

        // check logged user has permission to access
        // if logged user is not owner of application and also not approval user(TO or CC) and also not admin role
        if ($application->created_by !== $loginUser->id && Gate::denies('admin-gate')) {
            if (!$this->checkValidApproverOfApplication($request, $application, $loginUser)) {
                abort(403);
            }
        }

        $this->currentCompatData = compact('application', 'previewFlg');
    }

    protected function previewPdf(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $inputs = $request->input();

        if (isset($inputs['pdf'])) {
            return $this->pdf($request, $inputs, $application);
        } else {
            abort(404);
        }
    }

    private function pdf($request, $inputs, $application = null)
    {
        if (!empty($application)) {
            $loginUser = Auth::user();
            // check logged user has permission to access
            // if logged user is not owner of application and also not approval user(TO or CC) and also not admin role
            if ($application->created_by !== $loginUser->id && Gate::denies('admin-gate')) {
                if (!$this->checkValidApproverOfApplication($request, $application, $loginUser)) {
                    abort(403);
                }
            }

            // get applicant info
            $inputs['applicant'] = $application->applicant;

            // get last approval of complete application
            if ($application->status == config('const.application.status.completed')) {
                $conditions = [
                    'application_id' => $application->id,
                    'step' => $application->current_step,
                    'status' => config('const.application.status.completed'),
                ];
                $lastApproval = HistoryApproval::getHistory($conditions)->first();
                if (!empty($lastApproval)) {
                    $inputs['lastApproval'] = $lastApproval;
                }
            }
        } else {
            $inputs['applicant'] = Auth::user();
        }

        // PDF::setOptions(['defaultFont' => 'Roboto-Black']);
        $pdf = PDF::loadView("application_{$this->formTypeName}_pdf", compact('application', 'inputs'));

        // preview pdf
        $fileName = "{$this->formTypeName}.pdf";
        return $pdf->stream($fileName);
        // download
        // return $pdf->download($fileName);
    }
}
