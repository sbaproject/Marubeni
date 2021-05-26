<?php

namespace App\Http\Controllers\User;

use Exception;
use Carbon\Carbon;
use App\Libs\Common;
use App\Models\Step;
use App\Models\User;
use App\Models\Leave;
use App\Models\Businesstrip2;
use App\Models\Entertainment2;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\HistoryApproval;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exceptions\NotFoundFlowSettingException;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        $fillZero = config('const.num_fillzero');

        // search conditions
        $conditions = [
            'form_id' => '',
            'keyword' => '',
        ];
        $params = [
            'userId'                => Auth::user()->id,
            'completed1'            => config('const.application.status.completed'),
            'completed2'            => config('const.application.status.completed'),
            'approver_type_to_1'    => config('const.approver_type.to'),
            'approver_type_to_2'    => config('const.approver_type.to'),
            'approver_type_cc'      => config('const.approver_type.cc'),
        ];

        if (isset($request->application_type)) {
            $conditions['form_id'] = " and a.form_id = :formId";
            $params['formId'] = $request->application_type;
        }

        if (isset($request->keyword)) {
            $likeApplicantName = " us.name like :applicantName";
            $likeApplicationNo = "application_no like :applicationNo";

            $conditions['keyword'] = " and (" . $likeApplicationNo . " or " . $likeApplicantName . ")";
            $params['applicantName'] = '%' . $request->keyword . '%';
            $params['applicationNo'] = '%' . $request->keyword . '%';
        }

        // sorting columns
        $sortColNames = [
            'application_no'    => __('label.application_no'),
            'application_type'  => __('label.status_application_type'),
            'apply_date'        => __('label.status_apply_date'),
            'next_approver'     => __('label.status_next_approver'),
            'step_type'         => __('label.step'),
        ];
        $sortable = Common::getSortable($request, $sortColNames, 2, 1, true);

        // get data
        $sql = $this->getSqlIndex($conditions, $sortable, $fillZero);
        $data = DB::select($sql, $params);

        // check exist in business2, entainment2 -> when step 2
        foreach ($data as $key => $value) {
            $del = true;
            if ($value->current_step === config('const.application.step_type.settlement')) {
                $busi2 = Businesstrip2::where('application_id', '=', $value->application_id)->first();
                $enter2 = Entertainment2::where('application_id', '=', $value->application_id)->first();
                if ($busi2 !== null || $enter2 !== null) {
                    $del = false;
                }

                if ($del) {
                    unset($data[$key]);
                }
            }
        }
        if (!isset($data)) $data = [];

        // paginator
        $page = $request->page;
        $size = config('const.paginator.items');
        $collect = collect($data);
        $data = new LengthAwarePaginator(
            $collect->forPage($page, $size),
            $collect->count(),
            $size,
            $page,
            ['path' => route('user.approval.index')]
        );

        $compacts = [
            'data',
            'inputs',
            'sortable',
        ];
        return view('approval_index', compact($compacts));
    }

    public function show(Request $request, $id)
    {
        // get detail of application
        $application = DB::table('applications')
            ->select(
                'applications.*',
                'steps.flow_id',
                'steps.approver_id',
                'steps.approver_type',
                'steps.order',
                'steps.select_order',
                'applicant.name as applicant_name',
                'departments.name as applicant_department_name',
                DB::raw('(select MAX(step_type) from steps where flow_id = flows.id) as step_count')
            )
            ->join('groups', 'groups.id', 'applications.group_id')
            ->leftJoin('steps', function ($join) {
                $join->on('steps.group_id', '=', 'groups.id')
                    ->whereRaw('steps.select_order = applications.status')
                    ->whereRaw('steps.step_type = applications.current_step');
            })
            ->leftJoin('flows', 'flows.id', '=', 'steps.flow_id')
            ->join('forms', 'forms.id', '=', 'applications.form_id')
            ->leftJoin('users', 'users.id', '=', 'steps.approver_id')
            ->join('users as applicant', 'applicant.id', '=', 'applications.created_by')
            ->join('departments', 'applicant.department_id', '=', 'departments.id')
            ->where('applications.id', '=', $id)
            ->where('applications.status', '<>', config('const.application.status.draft'))
            ->where('applications.deleted_at', '=', null)
            ->first();

        if (empty($application)) {
            return $this->redirectError(__('msg.application_error_404'));
        }

        // get list of approver (include TO & CC)
        $approvers = $this->getAvailableApprovers($id, $application->current_step);

        // check logged user has permission to access
        $approverIds = Arr::pluck($approvers, 'approver_id');
        if (!in_array(Auth::user()->id, $approverIds) && $application->created_by !== Auth::user()->id) {
            return $this->redirectError(__('msg.application_error_403'));
        }

        // detect current logged user is TO or CC approve_type
        $this->determineApproverType($application, $approvers, $flgUserTO, $flgUserCC);

        // get history approval of application
        $conditions = [
            'application_id' => $application->id,
        ];
        $comments = HistoryApproval::getHistory($conditions)->get();

        $compacts = [
            'application',
            'approvers',
            'flgUserTO',
            'flgUserCC',
            'comments',
        ];
        return view('approval_show', compact($compacts));
    }

    public function update(Request $request, $id)
    {
        if (!isset($request->approve) && !isset($request->reject) && !isset($request->declined)) {
            abort(404);
        }

        $user = Auth::user();
        $inputs = $request->input();

        //check logged user has approval permission
        if (!$user->approval) {
            return $this->redirectError(__('msg.application_error_403'));
        }

        // selection columns
        $cols = [
            'applications.*',
            'steps.flow_id',
            'steps.approver_id',
            'steps.approver_type',
            'steps.step_type',
            'steps.order',
            'steps.select_order',
            'groups.applicant_id           as group_applicant_id',
            'groups.budget_type_compare',
            'approver.email                as approver_mail',
            'approver.role                 as approver_role',
            'approver.location             as approver_location',
            'approver.department_id        as approver_department',
            'applicant.email               as applicant_mail',
            'applicant.name                as applicant_name',
            'applicant.location            as applicant_location',
            'applicant.department_id       as applicant_department_id',
            'departments.name              as applicant_department_name',
            DB::raw('(select max(step_type) from steps where flow_id = flows.id) as step_count')
        ];

        $isLeaveApplication = $inputs['form_id'] == config('const.form.leave');
        if (!$isLeaveApplication) {
            $cols[] = 'budgets.budget_type';
        }

        $application = DB::table('applications')
            ->select($cols)
            ->join('groups', 'groups.id', 'applications.group_id')
            ->when(!$isLeaveApplication, function ($query) {
                $query->join('budgets', 'budgets.id', 'groups.budget_id');
            })
            ->join('steps', function ($join) {
                $join->on('steps.group_id', '=', 'groups.id')
                    ->whereRaw('steps.select_order = applications.status')
                    ->whereRaw('steps.step_type = applications.current_step');
            })
            ->join('flows', 'flows.id', '=', 'steps.flow_id')
            ->join('users as approver', 'approver.id', '=', 'steps.approver_id')
            ->join('users as applicant', 'applicant.id', '=', 'applications.created_by')
            ->join('departments', function ($join) {
                $join->on('departments.id', '=', 'applicant.department_id');
            })
            ->where('applications.id', '=', $id)
            ->where('applications.deleted_at', '=', null)
            ->first();

        // not found application
        if (empty($application)) {
            return $this->redirectError(__('msg.application_error_404'));
        }
        // check available status application
        if ($application->status < 0 || $application->status > 98) {
            return $this->redirectError(__('msg.application_error_unvalid_action'));
        }
        // check available approval for current user
        // if logged user is approver TO (able to Approve|Decline|Reject)
        if ($application->approver_id != $user->id) {
            // approver CC able to DECLINE only.
            if (isset($request->declined)) {
                $approvers = $this->getAvailableApprovers($id, $application->current_step);
                $flgApproverCC = $this->isApproverCC($approvers);
            }
            if (!$flgApproverCC) {
                return $this->redirectError(__('msg.application_error_403'));
            }
        }
        // check application has modified before approve
        if ($application->updated_at !== $inputs['last_updated_at']) {
            return $this->redirectError(__('msg.application_error_review_before_approve'));
        }

        DB::beginTransaction();
        try {
            if (isset($request->approve)) {
                $newApplication['status'] = $application->order;
                // make application next to step of approval flow (with Leave Application just have one step)
                if ($application->order == config('const.application.status.completed')) {
                    if ($application->form_id == config('const.form.biz_trip') || $application->form_id == config('const.form.entertainment')) {
                        // setup for next step ortherwise application is completed
                        if ($application->current_step < $application->step_count) {
                            // get next step of approval flow
                            $nextStep = $application->current_step + 1;
                            // get group for next step
                            $group = DB::table('groups')
                                ->select('groups.*')
                                ->join('budgets', function ($join) use ($nextStep, $application) {
                                    $join->on('groups.budget_id', '=', 'budgets.id')
                                        ->where('budgets.budget_type', '=', $application->budget_type)
                                        ->where('budgets.step_type', '=', $nextStep)
                                        ->where('budgets.position', '=', $application->budget_position)
                                        ->where('budgets.deleted_at', '=', null);
                                })
                                ->where([
                                    'groups.applicant_id' => $application->group_applicant_id,
                                    'groups.budget_type_compare' => $application->budget_type_compare,
                                    'groups.deleted_at' => null,
                                ])
                                ->first();

                            // not found available flow setting
                            if (empty($group)) {
                                throw new NotFoundFlowSettingException();
                            }

                            $newApplication['current_step']   = $nextStep;
                            $newApplication['group_id']       = $group->id;
                            $newApplication['status']         = config('const.application.status.applying');
                        }
                    } elseif ($application->form_id == config('const.form.leave')) {
                        // comment this code because leave_remaining_days and leave_remaining_time is subtract when user apply
                        // for leave application
                        // $leave = Leave::where('application_id', $application->id)->first();
                        // if (!empty($leave)) {
                        //     // if leave_code is AL or SL (with paid_type = AL)
                        //     if (
                        //         $leave->code_leave == config('const.code_leave.AL')
                        //         || ($leave->code_leave == config('const.code_leave.SL') && $leave->paid_type == config('const.paid_type.AL'))
                        //     ) {
                        //         $applicant = User::find($application->created_by);
                        //         if (!empty($applicant)) {

                        //             //--------------------------------------------------
                        //             // calculating total annual remaining time of applicant (only for annual leave)
                        //             //--------------------------------------------------
                        //             $dayUse = empty($leave->days_use) ? 0 : $leave->days_use;
                        //             $timeUse = empty($leave->times_use) ? 0 : $leave->times_use;
                        //             // working hours per day
                        //             $workingHourPerDay = config('const.working_hours_per_day');
                        //             // get total remaining time (by hours)
                        //             $remainingHours = ($applicant->leave_remaining_days * $workingHourPerDay) + $applicant->leave_remaining_time;
                        //             // total hours take this time
                        //             $totalHourUse = $remainingHours - (($dayUse * $workingHourPerDay) + $timeUse);
                        //             // update annual leave remaining time of applicant
                        //             $applicant->leave_remaining_days = intval($totalHourUse / $workingHourPerDay);
                        //             $applicant->leave_remaining_time = (($totalHourUse % $workingHourPerDay) / $workingHourPerDay) * $workingHourPerDay;

                        //             $applicant->updated_by = $user->id;
                        //             $applicant->save();
                        //         }
                        //     }
                        // }
                    }
                }
            } elseif (isset($request->reject)) {
                $newApplication['status'] = config('const.application.status.reject');
                $application->order = $newApplication['status'];
            } elseif (isset($request->declined)) {
                $newApplication['status'] = config('const.application.status.declined');
                $application->order = $newApplication['status'];
            }

            $newApplication['comment']    = $inputs['comment'];
            $newApplication['updated_by'] = $user->id;
            $newApplication['updated_at'] = Carbon::now();

            DB::table('applications')->where('id', $id)->update($newApplication);

            // create approval history
            $historyData = [
                'approved_by'       => $user->id,
                'application_id'    => $application->id,
                'status'            => $application->order,
                'step'              => $application->step_type,
                'comment'           => $newApplication['comment'],
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ];
            DB::table('history_approval')->insert($historyData);

            // commit db
            DB::commit();

            // send notice mail
            $this->sendApprovalApplicationMail($request, $application, $newApplication);

            return Common::redirectRouteWithAlertSuccess('user.approval.index', __('msg.application_success_approve_ok'));
        } catch (Exception $ex) {

            // rollback db
            DB::rollBack();

            // log stacktrace
            report($ex);

            // get msg error to show to client
            if ($ex instanceof NotFoundFlowSettingException) {
                $msgErr = $ex->getMessage();
            } else {
                $msgErr = __('msg.save_fail');
            }
            return Common::redirectBackWithAlertFail($msgErr)->withInput();
        }
    }

    public function skip(Request $request, $id)
    {
        $completedStatus = config('const.application.status.completed');

        // login user
        $loginUser = Auth::user();

        // get application
        $cols = [
            'applications.*',
            'steps.flow_id',
            'steps.approver_id',
            'steps.approver_type',
            'steps.step_type',
            'steps.order',
            'steps.select_order',
            'groups.applicant_id           as group_applicant_id',
            'groups.budget_type_compare',
            'approver.email                as approver_mail',
            'approver.name                 as approver_name',
            'approver.role                 as approver_role',
            'approver.location             as approver_location',
            'approver.department_id        as approver_department',
            'applicant.email               as applicant_mail',
            'applicant.name                as applicant_name',
            'applicant.location            as applicant_location',
            'departments.name              as applicant_department_name',
            DB::raw('(select max(step_type) from steps where flow_id = flows.id) as step_count'),
            // get final approver
            DB::raw("
                    (
                        select  approver_id
                        from    steps as st
                        where   flow_id = steps.flow_id
                                and `order` = {$completedStatus}
                        order by step_type desc limit 1
                    ) as final_approver_id
                "),
        ];

        $isLeaveApplication = $request->skip_form_id == config('const.form.leave');
        if (!$isLeaveApplication) {
            $cols[] = 'budgets.budget_type';
        }

        $application = DB::table('applications')
            ->select($cols)
            ->join('groups', 'groups.id', 'applications.group_id')
            ->when(!$isLeaveApplication, function ($query) {
                $query->join('budgets', 'budgets.id', 'groups.budget_id');
            })
            ->join('steps', function ($join) {
                $join->on('steps.group_id', '=', 'groups.id')
                    ->whereRaw('steps.select_order = applications.status')
                    ->whereRaw('steps.step_type = applications.current_step');
            })
            ->join('flows', 'flows.id', '=', 'steps.flow_id')
            ->join('users as approver', 'approver.id', '=', 'steps.approver_id')
            ->join('users as applicant', 'applicant.id', '=', 'applications.created_by')
            ->join('departments', function ($join) {
                $join->on('departments.id', '=', 'applicant.department_id');
            })
            ->where('applications.id', '=', $id)
            ->where('applications.deleted_at', '=', null)
            ->first();

        // not found application
        if (empty($application)) {
            return Common::redirectBackWithAlertFail(__('msg.application_error_404'));
        }

        // check owner of application
        if ($application->created_by != $loginUser->id) {
            abort(403);
        }

        // check data was not modified before skip
        if ($request->skip_last_updated_at != $application->updated_at) {
            return Common::redirectBackWithAlertFail(__('msg.application_error_review_before_approve'));
        }

        // check status application is applying
        if (!($application->status >= 0 && $application->status <= 98)) {
            return Common::redirectBackWithAlertFail(__('msg.application_error_unvalid_action'));
        }

        // check do not skip final approver
        if ($application->final_approver_id == $application->approver_id) {
            return Common::redirectBackWithAlertFail(__('msg.application_error_skip_final_approver'));
        }

        DB::beginTransaction();
        try {
            $newApplication['status'] = $application->order;
            // make application next to step of approval flow (with Leave Application just have one step)
            if ($application->order == config('const.application.status.completed')) {
                if ($application->form_id == config('const.form.biz_trip') || $application->form_id == config('const.form.entertainment')) {
                    // setup for next step ortherwise application is completed
                    if ($application->current_step < $application->step_count) {
                        // get next step of approval flow
                        $nextStep = $application->current_step + 1;
                        // get group for next step
                        $group = DB::table('groups')
                            ->select('groups.*')
                            ->join('budgets', function ($join) use ($nextStep, $application) {
                                $join->on('groups.budget_id', '=', 'budgets.id')
                                    ->where('budgets.budget_type', '=', $application->budget_type)
                                    ->where('budgets.step_type', '=', $nextStep)
                                    ->where('budgets.position', '=', $application->budget_position)
                                    ->where('budgets.deleted_at', '=', null);
                            })
                            ->where([
                                'groups.applicant_id' => $application->group_applicant_id,
                                'groups.budget_type_compare' => $application->budget_type_compare,
                                'groups.deleted_at' => null,
                            ])
                            ->first();

                        // not found available flow setting
                        if (empty($group)) {
                            throw new NotFoundFlowSettingException();
                        }

                        $newApplication['current_step']   = $nextStep;
                        $newApplication['group_id']       = $group->id;
                        $newApplication['status']         = config('const.application.status.applying');
                    }
                }
            }

            $newApplication['updated_by'] = $loginUser->id;
            $newApplication['updated_at'] = Carbon::now();

            DB::table('applications')->where('id', $id)->update($newApplication);

            // create approval history
            $historyData = [
                'application_id'    => $application->id,
                'status'            => $application->order,
                'step'              => $application->step_type,
                'approved_by'       => $application->approver_id,
                'comment'           => $request->skip_comment,
                'skiped_by'         => $loginUser->id, // only applicant can skip
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ];
            DB::table('history_approval')->insert($historyData);

            // commit db
            DB::commit();

            // send notice mail to approver who was skipped
            $this->sendSkippedApplicationMail($request, $application, $newApplication);

            return Common::redirectBackWithAlertSuccess(__('msg.save_success'));
        } catch (Exception $ex) {

            // rollback db
            DB::rollBack();

            // log stacktrace
            report($ex);

            // get msg error to show to client
            if ($ex instanceof NotFoundFlowSettingException) {
                $msgErr = $ex->getMessage();
            } else {
                $msgErr = __('msg.save_fail');
            }
            return Common::redirectBackWithAlertFail($msgErr)->withInput();
        }

        return back();
    }

    private function getSqlIndex($conditions, $sortable, $fillZero)
    {
        return "select
                 a.id             as application_id
                ,a.application_no
                ,a.current_step
                ,a.status
                ,a.subsequent
                ,a.created_at     as apply_date
                ,a.form_id
                ,s.group_id
                ,s.id             as step_id
                ,s.approver_id
                ,s.step_type
                ,s.approver_type
                ,u.name           as approver_name
                ,us.name          as applicant_name
                ,fo.name          as application_type
                ,case when s.approver_type = :approver_type_to_1
                    then (
                        select  us.name
                        from    steps
                                inner join users us
                                    on us.id = approver_id
                        where   flow_id = s.flow_id
                        and     approver_type = s.approver_type
                        and     (
                                    (step_type = s.step_type and s.order <> :completed1 and select_order = s.order)
                                    or
                                    (s.order = :completed2 and (step_type = (s.step_type + 1) and select_order = 0))
                                )
                    ) else (
                        select  us.name
                        from    steps
                                inner join users us
                                    on us.id = steps.approver_id
                        where	steps.group_id = a.group_id
                        and 	steps.select_order = a.status
                        and 	steps.step_type = a.current_step
                        and 	steps.approver_type = 0
                        and 	a.status between 0 and 98
                    ) END  as next_approver
            from    applications a
                    inner join forms fo 
                        on a.form_id = fo.id " . $conditions['form_id'] . "
                    inner join groups g
                        on a.group_id = g.id
                    inner join steps s
                        on s.group_id = g.id 
                        and ((s.approver_type = :approver_type_to_2 and a.status = s.select_order) or (s.approver_type = :approver_type_cc))
                        and s.step_type = a.current_step
                    inner join users u
                        on u.id = s.approver_id
                        and s.approver_id = :userId
                    inner join users us
                        on us.id = a.created_by
            where   a.status between 0 and 98 " . $conditions['keyword'] . "
            order by " . $sortable->order_by;
    }

    /**
     * Get list of approver (include TO & CC)
     * @param int $applicationId : Appplication ID
     * @param int $currentStep : Current step in approval flow of application
     * @return \Illuminate\Support\Collection
     */
    private function getAvailableApprovers($applicationId, $currentStep)
    {
        $approvers = DB::table('steps')
            ->select(
                [
                    'steps.approver_id',
                    'steps.approver_type',
                ]
            )
            ->where('steps.flow_id', function ($query) use ($applicationId) {
                $query->select('steps.flow_id')
                    ->from('applications')
                    ->join('steps', 'steps.group_id', 'applications.group_id')
                    ->where('applications.id', $applicationId)
                    ->where('applications.deleted_at', '=', null)
                    ->limit(1);
            })
            ->where('steps.step_type', $currentStep)
            ->get()
            ->toArray();

        return $approvers;
    }

    /**
     * Detect if current logged user is approver CC
     * @param array $approvers List of available approvers (including TO & CC)
     * @return boolean True if is CC
     */
    private function isApproverCC($approvers)
    {

        $approverCCs = array_filter((array)$approvers, function ($element) {
            return $element->approver_type == config('const.approver_type.cc');
        });

        $flgUserCC = in_array(Auth::user()->id, Arr::pluck($approverCCs, 'approver_id'));

        return $flgUserCC;
    }

    /**
     * Determine current logged user is TO or CC approve_type
     * @param object $application Application
     * @param array $approvers List of approvers (include TO & CC)
     * @param boolean $flgUserTO Reference flg TO
     * @param boolean $flgUserCC Reference flg CC
     */
    private function determineApproverType($application, $approvers, &$flgUserTO = false, &$flgUserCC = false)
    {
        if (
            $application->status >= config('const.application.status.applying')
            && $application->status < config('const.application.status.completed')
            && $application->created_by !== Auth::user()->id
        ) {
            // available TO approver for this step
            if ($application->approver_id === Auth::user()->id) {
                $flgUserTO = true;
            }
            // available CC approver for this step
            else {
                $flgUserCC = $this->isApproverCC($approvers);
            }
        }
    }

    private function sendApprovalApplicationMail($request, $currentApplication, $newApplication)
    {
        if (isset($request->approve) && isset($request->reject) && isset($request->declined)) {
            return;
        }

        if (isset($request->approve)) {

            // application is completed
            if ($newApplication['status'] == config('const.application.status.completed')) {
                // notify to applicant
                $status = 'Final approved';
                $formType = trans("label.form.{$currentApplication->form_id}", [], 'en');
                $title = sprintf('Your %s Application has been %s !', $formType, Str::lower($status));
                $msgParams = [
                    'form_type' => $formType,
                    'status' => $status,
                    'approver' => Auth::user()->name,
                    'comment' => $newApplication['comment'],
                    'url' => Common::getRouteEditApplication($currentApplication->id, $currentApplication->form_id),
                ];
                Common::sendApplicationNoticeMail(
                    'mails.application_approve_to_applicant',
                    $title,
                    $currentApplication->applicant_mail,
                    [],
                    $msgParams
                );
            }
            // still in progress of approval
            else {
                $nextGroupId = $newApplication['group_id'] ?? $currentApplication->group_id;
                // next group_id was changed mean is application go to next step
                // when next step need send mail to first TO && all CC of next step
                if ($nextGroupId != $currentApplication->group_id) {

                    $nextApprovers = Step::getApproversByGroupId($nextGroupId);

                    foreach ($nextApprovers as $item) {
                        // just get next first approver(TO)
                        if ($item->approver_type == config('const.approver_type.to')) {
                            if (isset($firstToFlg)) {
                                continue;
                            }
                            $firstToFlg = true;
                        }
                        // multi CCs
                        $formType = trans("label.form.{$currentApplication->form_id}", [], 'en');
                        $stepName = trans("label.step_type.{$newApplication['current_step']}", [], 'en');
                        $title = "New {$formType} Application has been applied for {$stepName} Step!";
                        $msgParams = [
                            'approver_type' => $item->approver_type,
                            'form_type' => $formType,
                            'step_name' => $stepName,
                            'applicant_name' => $currentApplication->applicant_name,
                            'applicant_location' => $currentApplication->applicant_location,
                            'department_name' => $currentApplication->applicant_department_name,
                            'url' => route('user.approval.show', $currentApplication->id),
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
                // application still on current step
                // so only to send mail to next approver TO
                else {
                    // next approver of step
                    $nextApprover = Step::getNextApproverCurrentStep($currentApplication->group_id, $currentApplication->order);
                    if (!empty($nextApprover)) {
                        $formType = trans("label.form.{$currentApplication->form_id}", [], 'en');
                        $title = "New {$formType} Application has been applied !";
                        $msgParams = [
                            'form_type' => $formType,
                            'applicant_name' => $currentApplication->applicant_name,
                            'applicant_location' => $currentApplication->applicant_location,
                            'department_name' => $currentApplication->applicant_department_name,
                            'url' => route('user.approval.show', $currentApplication->id),
                        ];
                        Common::sendApplicationNoticeMail(
                            'mails.application_approve_next_to',
                            $title,
                            $nextApprover->approver_mail,
                            [],
                            $msgParams
                        );
                    }
                }
            }
        } elseif (isset($request->reject) || isset($request->declined)) {
            // notify to applicant
            if (isset($request->reject)) {
                $status = 'Rejected';
            }
            if (isset($request->declined)) {
                $status = 'Declined';
            }
            $formType = trans("label.form.{$currentApplication->form_id}", [], 'en');
            $title = sprintf('Your %s Application has been %s !', $formType, Str::lower($status));
            $msgParams = [
                'form_type' => $formType,
                'status' => $status,
                'approver' => Auth::user()->name,
                'comment' => $newApplication['comment'],
                'url' => Common::getRouteEditApplication($currentApplication->id, $currentApplication->form_id),
            ];
            Common::sendApplicationNoticeMail(
                'mails.application_approve_to_applicant',
                $title,
                $currentApplication->applicant_mail,
                [],
                $msgParams
            );
        }
    }

    private function sendSkippedApplicationMail($request, $currentApplication, $newApplication)
    {
        $formType = trans("label.form.{$currentApplication->form_id}", [], 'en');
        $title = "{$formType} Application has been skipped your approval !";
        $msgParams = [
            'form_type' => $formType,
            'skipped_by' => Auth::user()->name,
            'comment' => $request->skip_comment,
            'url' => route('user.approval.show', $currentApplication->id),
        ];
        Common::sendApplicationNoticeMail(
            'mails.application_skip_approver',
            $title,
            $currentApplication->approver_mail,
            [],
            $msgParams
        );

        // send notice mail to next approver TO (after skipped)
        $nextGroupId = $newApplication['group_id'] ?? $currentApplication->group_id;

        // group_id changed mean is next step
        if ($nextGroupId != $currentApplication->group_id) {
            $nextApprovers = Step::getApproversByGroupId($nextGroupId);
            foreach ($nextApprovers as $item) {
                // just get next first approver(TO)
                if ($item->approver_type == config('const.approver_type.to')) {
                    if (isset($firstToFlg)) {
                        continue;
                    }
                    $firstToFlg = true;
                }
                // multi CCs
                $formType = trans("label.form.{$currentApplication->form_id}", [], 'en');
                $stepName = trans("label.step_type.{$newApplication['current_step']}", [], 'en');
                $title = "New {$formType} Application has been applied for {$stepName} Step!";
                $msgParams = [
                    'approver_type' => $item->approver_type,
                    'form_type' => $formType,
                    'step_name' => $stepName,
                    'applicant_name' => $currentApplication->applicant_name,
                    'applicant_location' => $currentApplication->applicant_location,
                    'department_name' => $currentApplication->applicant_department_name,
                    'url' => route('user.approval.show', $currentApplication->id),
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
        // application still on current step
        // so only to send mail to next approver TO
        else {
            $nextOrder = $newApplication['status'] ?? $currentApplication->order;
            $nextApprover = Step::getNextApproverCurrentStep($nextGroupId, $nextOrder);
            if (!empty($nextApprover)) {
                $formType = trans("label.form.{$currentApplication->form_id}", [], 'en');
                $title = "New {$formType} Application has been applied !";
                $msgParams = [
                    'form_type' => $formType,
                    'applicant_name' => $currentApplication->applicant_name,
                    'applicant_location' => $currentApplication->applicant_location,
                    'department_name' => $currentApplication->applicant_department_name,
                    'url' => route('user.approval.show', $currentApplication->id),
                ];
                Common::sendApplicationNoticeMail(
                    'mails.application_approve_next_to',
                    $title,
                    $nextApprover->approver_mail,
                    [],
                    $msgParams
                );
            }
        }
    }

    private function redirectError($msg)
    {
        return Common::redirectRouteWithAlertFail('user.approval.index', $msg);
    }
}
