<?php

namespace App\Http\Controllers\User;

use Exception;
use Carbon\Carbon;
use App\Libs\Common;
use App\Models\User;
use App\Models\Leave;
use Illuminate\Support\Arr;
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
            $likeApplicationNo = "concat(concat(fo.prefix,'-'),lpad(a.id, " . $fillZero . ", '0')) like :applicationNo";

            $conditions['keyword'] = " and (" . $likeApplicationNo . " or " . $likeApplicantName . ")";
            $params['applicantName'] = '%' . $request->keyword . '%';
            $params['applicationNo'] = '%' . $request->keyword . '%';
        }

        // sorting columns
        $sortColNames = [
            'application_no'    => __('label.application_no'),
            'application_type'  => __('label.status.application_type'),
            'apply_date'        => __('label.status.apply_date'),
            'next_approver'     => __('label.status.next_approver'),
            'step_type'         => __('label.step'),
        ];
        $sortable = Common::getSortable($request, $sortColNames, 2, 1, true);

        // get data
        $sql = $this->getSqlIndex($conditions, $sortable, $fillZero);
        $data = DB::select($sql, $params);

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
        return view('approval.index', compact($compacts));
    }

    public function show(Request $request, $id)
    {
        // get detail of application
        $application = DB::table('applications')
            ->select(
                'applications.*',
                DB::raw("concat(concat(forms.prefix,'-'),lpad(applications.id, " . config('const.num_fillzero') . ", '0')) as application_no"),
                'steps.flow_id',
                'steps.approver_id',
                'steps.approver_type',
                'steps.order',
                'steps.select_order',
                DB::raw('us.name as applicant'),
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
            ->join('users as us', 'us.id', '=', 'applications.created_by')
            ->where('applications.id', '=', $id)
            ->where('applications.status', '<>', config('const.application.status.draft'))
            ->where('applications.deleted_at', '=', null)
            ->first();

        if (empty($application)) {
            return $this->redirectError(__('msg.application.error.404'));
        }

        // get list of approver (include TO & CC)
        $approvers = $this->getAvailableApprovers($id, $application->current_step);

        // check logged user has permission to access
        $approverIds = Arr::pluck($approvers, 'approver_id');
        if (!in_array(Auth::user()->id, $approverIds) && $application->created_by !== Auth::user()->id) {
            return $this->redirectError(__('msg.application.error.403'));
        }

        // detect current logged user is TO or CC approve_type
        $this->detectApproverType($application, $approvers, $flgUserTO, $flgUserCC);

        // get comments of application
        $comments = HistoryApproval::getApplicationComments($id);

        $compacts = [
            'application',
            'approvers',
            'flgUserTO',
            'flgUserCC',
            'comments',
        ];
        return view('approval.show', compact($compacts));
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
            return $this->redirectError(__('msg.application.error.403'));
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
            'groups.applicant_id        as group_applicant_id',
            'groups.budget_type_compare',
            'users.role                 as approver_role',
            'users.location             as approver_location',
            'users.department_id        as approver_department',
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
            ->join('users', 'users.id', '=', 'steps.approver_id')
            ->where('applications.id', '=', $id)
            ->where('applications.deleted_at', '=', null)
            ->first();

        // not found application
        if (empty($application)) {
            return $this->redirectError(__('msg.application.error.404'));
        }
        // check available status application
        if ($application->status < 0 || $application->status > 98) {
            return $this->redirectError(__('msg.application.error.unvalid_action'));
        }
        // check available approval for current user
        // if logged user is approver TO (able to Approve|Decline|Reject)
        if ($application->approver_id != $user->id) {
            // approver CC able to DECLINE only.
            if (isset($request->declined)){
                $approvers = $this->getAvailableApprovers($id, $application->current_step);
                $flgApproverCC = $this->isApproverCC($approvers);
            }
            if(!$flgApproverCC){
                return $this->redirectError(__('msg.application.error.403'));
            }
        }
        // check application has modified before approve
        if ($application->updated_at !== $inputs['last_updated_at']) {
            return $this->redirectError(__('msg.application.error.review_before_approve'));
        }

        DB::beginTransaction();
        try {
            if (isset($request->approve)) {
                $data['status'] = $application->order;
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

                            $data['current_step']   = $nextStep;
                            $data['group_id']       = $group->id;
                            $data['status']         = config('const.application.status.applying');
                        }
                    } elseif ($application->form_id == config('const.form.leave')) {
                        // for leave application
                        $leave = Leave::where('application_id', $application->id)->first();
                        if (!empty($leave)) {
                            // if leave_code is AL or SL (with paid_type = AL)
                            if (
                                $leave->code_leave == config('const.code_leave.AL')
                                || ($leave->code_leave == config('const.code_leave.SL') && $leave->paid_type == config('const.paid_type.AL'))
                            ) {
                                $applicant = User::find($application->created_by);
                                if (!empty($applicant)) {

                                    //--------------------------------------------------
                                    // calculating total annual remaining time of applicant (only for annual leave)
                                    //--------------------------------------------------
                                    $dayUse = empty($leave->days_use) ? 0 : $leave->days_use;
                                    $timeUse = empty($leave->times_use) ? 0 : $leave->times_use;
                                    // working hours per day
                                    $workingHourPerDay = config('const.working_hours_per_day');
                                    // get total remaining time (by hours)
                                    $remainingHours = ($applicant->leave_remaining_days * $workingHourPerDay) + $applicant->leave_remaining_time;
                                    // total hours take this time
                                    $totalHourUse = $remainingHours - (($dayUse * $workingHourPerDay) + $timeUse);
                                    // update annual leave remaining time of applicant
                                    $applicant->leave_remaining_days = intval($totalHourUse / $workingHourPerDay);
                                    $applicant->leave_remaining_time = (($totalHourUse % $workingHourPerDay) / $workingHourPerDay) * $workingHourPerDay;

                                    $applicant->updated_by = $user->id;
                                    $applicant->save();
                                }
                            }
                        }
                    }
                }
            } elseif (isset($request->reject)) {
                $data['status'] = config('const.application.status.reject');
                $application->order = $data['status'];
            } elseif (isset($request->declined)) {
                $data['status'] = config('const.application.status.declined');
                $application->order = $data['status'];
            }

            $data['comment']    = $inputs['comment'];
            $data['updated_by'] = $user->id;
            $data['updated_at'] = Carbon::now();

            DB::table('applications')->where('id', $id)->update($data);

            // create approval history
            $historyData = [
                'approved_by'       => $user->id,
                'application_id'    => $application->id,
                'status'            => $application->order,
                'step'              => $application->step_type,
                'comment'           => $data['comment'],
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ];
            DB::table('history_approval')->insert($historyData);

            // commit db
            DB::commit();

            return Common::redirectRouteWithAlertSuccess('user.approval.index', __('msg.application.success.approve_ok'));
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

    private function getSqlIndex($conditions, $sortable, $fillZero)
    {
        return "select
                 concat(concat(fo.prefix,'-'),lpad(a.id, " . $fillZero . ", '0')) as application_no
                ,a.id             as application_id
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
    private function getAvailableApprovers($applicationId, $currentStep){
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
    private function isApproverCC($approvers){

        $approverCCs = array_filter((array)$approvers, function ($element) {
            return $element->approver_type == config('const.approver_type.cc');
        });

        $flgUserCC = in_array(Auth::user()->id, Arr::pluck($approverCCs, 'approver_id'));

        return $flgUserCC;
    }

    /**
     * Detect current logged user is TO or CC approve_type
     * @param object $application Application
     * @param array $approvers List of approvers (include TO & CC)
     * @param boolean $flgUserTO Reference flg TO
     * @param boolean $flgUserCC Reference flg CC
     */
    private function detectApproverType($application, $approvers, &$flgUserTO = false, &$flgUserCC = false){
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

    private function redirectError($msg)
    {
        return Common::redirectRouteWithAlertFail('user.approval.index', $msg);
    }
}
