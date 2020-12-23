<?php

namespace App\Http\Controllers\Application;

use PDF;
use Carbon\Carbon;
use App\Libs\Common;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\NotFoundFlowSettingException;

class ApplicationController extends Controller
{
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

        $this->viewInputName = "application.{$this->formTypeName}.input";
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
        $previewFlg = !$this->checkEditableApplication($application) || isset($showWithAdminFlg);

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
            $msg = __('msg.application.success.apply_ok');
        } elseif (isset($inputs['draft'])) {
            $msg = __('msg.application.success.draft_ok');
        }
        return Common::redirectRouteWithAlertSuccess($this->routeNameRedirectAfterSave, $msg);
    }

    protected function checkEmptyApplication($application)
    {
    }

    protected function preview(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $this->checkEmptyApplication($application);

        $loggedUser = Auth::user();
        $previewFlg = true;

        // check logged user has permission to access
        // if logged user is not owner of application and also not approval user(TO or CC) and also not admin role
        if ($application->created_by !== $loggedUser->id && Gate::denies('admin-gate')) {
            if (!$this->checkValidApproverOfApplication($request, $application, $loggedUser)) {
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
                        __('msg.application.error.inprogress_not_allow_edit')
                    );
                }
            }
        } else {
            abort(404);
        }

        // validate
        $validator = $this->doValidate($request, $inputs);
        if (!empty($validator)) {
            return redirect()->back()->with('inputs', $inputs)->withErrors($validator);
        }

        // save db
        $msgErr = $this->doSaveData($request, $inputs, $application);
        if (!empty($msgErr)) {
            return Common::redirectBackWithAlertFail($msgErr)->with('inputs', $inputs);
        }

        return $this->doRedirectSaveOk($inputs);
    }

    private function pdf($request, $inputs, $application = null)
    {
        if (!empty($application)) {
            $loggedUser = Auth::user();
            // check logged user has permission to access
            // if logged user is not owner of application and also not approval user(TO or CC) and also not admin role
            if ($application->created_by !== $loggedUser->id && Gate::denies('admin-gate')) {
                if (!$this->checkValidApproverOfApplication($request, $application, $loggedUser)) {
                    abort(403);
                }
            }
            $inputs['applicant'] = $application->applicant;
        } else {
            $inputs['applicant'] = Auth::user();
        }

        // PDF::setOptions(['defaultFont' => 'Roboto-Black']);
        $pdf = PDF::loadView("application.{$this->formTypeName}.pdf", compact('application', 'inputs'));

        // preview pdf
        $fileName = "{$this->formTypeName}.pdf";
        return $pdf->stream($fileName);
        // download
        // return $pdf->download($fileName);
    }

    private function checkValidApproverOfApplication($request, $application, $loggedUser)
    {
        $approver = DB::table('steps')
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

    public function getActionType($inputs)
    {
        if (isset($inputs['apply'])) {
            return config('const.application.status.applying');
        } else if (isset($inputs['draft'])) {
            return config('const.application.status.draft');
        }
    }

    public function getCurrentStep($application)
    {
        if (!empty($application)) {
            return $application->current_step;
        } else {
            return config('const.budget.step_type.application');
        }
    }

    public function getGroup($user, $currentStep = null, $budgetType = null, $budgetPosition = null, $budgetTypeCompare = null)
    {
        $group = DB::table('groups')
            ->select('groups.*')
            ->join('applicants', function ($join) use ($user) {
                $join->on('groups.applicant_id', '=', 'applicants.id')
                    ->where('applicants.role', $user->role)
                    ->where('applicants.location', $user->location)
                    ->where('applicants.department_id', $user->department_id)
                    ->whereNull('applicants.deleted_at');
            })
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

    public function saveApplicationMaster($request, $inputs, $app, $user)
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
        $group = $this->getGroup($user, $currentStep, $budgetType, $budgetPosition, $budgetTypeCompare);
        // not found available flow setting
        if (empty($group) && isset($inputs['apply'])) {
            throw new NotFoundFlowSettingException();
        }

        // get attached file
        $filePath = $this->uploadAttachedFile($request, $inputs, $app, $user);

        // prepare data
        $application = [
            'form_id'           => $formId,
            'group_id'          => $group->id ?? null,
            'current_step'      => $currentStep,
            'status'            => $status,
            'subsequent'        => $inputs['subsequent'],
            'budget_position'   => $budgetPosition,
            'file_path'         => $filePath ?? null,
            'updated_by'        => $user->id,
            'updated_at'        => Carbon::now()
        ];

        // save applications
        if (empty($app)) {
            $application['created_by'] = $user->id;
            $application['created_at'] = Carbon::now();

            $applicationId = DB::table('applications')->insertGetId($application);
        } else {
            DB::table('applications')->where('id', $app->id)->update($application);
        }

        return $applicationId ?? $app->id;
    }

    public function uploadAttachedFile($request, $inputs, $application, $loggedUser)
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
            $fileName = time() . $loggedUser->id . $extension;
            $filePath = $request->file('input_file')->storeAs('uploads/application/', $fileName);
        }

        return $filePath ?? null;
    }
}
