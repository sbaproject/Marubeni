<?php

namespace App\Http\Controllers\User;

use Exception;
use Carbon\Carbon;
use App\Libs\Common;
use App\Models\User;
use App\Models\Leave;
use App\Models\Application;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exceptions\Entertainment\NotFoundFlowSettingException;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        $fillZero = config('const.num_fillzero');

        // search conditions
        $formIdCondition = "";
        $keywordCondition = "";
        $params = [
            'userId' => Auth::user()->id,
            'completed1' => config('const.application.status.completed'),
            'completed2' => config('const.application.status.completed'),
            'approver_type_to_1' => config('const.approver_type.to'),
            'approver_type_to_2' => config('const.approver_type.to'),
            'approver_type_cc' => config('const.approver_type.cc'),
        ];
        if (isset($request->application_type)) {
            $formIdCondition = " AND a.form_id = :formId";
            $params['formId'] = $request->application_type;
        }
        if (isset($request->keyword)) {
            $applicantNameCondition = " us.name LIKE :applicantName";
            $appNoCondition = "CONCAT(CONCAT(fo.prefix,'-'),LPAD(a.id, " . $fillZero . ", '0')) LIKE :applicationNo";
            $keywordCondition = " AND (" . $appNoCondition . " OR " . $applicantNameCondition . ")";
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
        $sql = "SELECT
                 CONCAT(CONCAT(fo.prefix,'-'),LPAD(a.id, " . $fillZero . ", '0')) AS application_no
                ,a.id             AS application_id
                ,a.current_step
                ,a.status
                ,a.subsequent
                ,a.created_at     AS apply_date
                ,a.form_id
                ,s.group_id
                ,s.id             AS step_id
                ,s.approver_id
                ,s.step_type
                ,s.approver_type
                ,u.name           AS approver_name
                ,us.name          AS applicant_name
                ,fo.name          AS application_type
                ,CASE WHEN s.approver_type = :approver_type_to_1
                    THEN (
                        SELECT  us.name
                        FROM    steps
                                INNER JOIN users us
                                    ON us.id = approver_id
                        WHERE   flow_id = s.flow_id
                        AND     approver_type = s.approver_type
                        AND     (
                                    (step_type = s.step_type AND s.order <> :completed1 AND select_order = s.order)
                                    OR
                                    (s.order = :completed2 AND (step_type = (s.step_type + 1) AND select_order = 0))
                                )
                    ) ELSE (
                        SELECT  us.name
                        FROM    steps
                                INNER JOIN users us
                                    ON us.id = steps.approver_id
                        WHERE	steps.group_id = a.group_id
                        AND 	steps.select_order = a.status
                        AND 	steps.step_type = a.current_step
                        AND 	steps.approver_type = 0
                        AND 	a.status BETWEEN 0 AND 98
                    ) END  AS next_approver
            FROM    applications a
                    INNER JOIN forms fo 
                        ON a.form_id = fo.id " . $formIdCondition . "
                    INNER JOIN groups g
                        ON a.group_id = g.id
                    INNER JOIN steps s
                        ON s.group_id = g.id 
                        AND ((s.approver_type = :approver_type_to_2 AND a.status = s.select_order) OR (s.approver_type = :approver_type_cc))
                        AND s.step_type = a.current_step
                    INNER JOIN users u
                        ON u.id = s.approver_id
                        AND s.approver_id = :userId
                    INNER JOIN users us
                        ON us.id = a.created_by
            WHERE   a.status BETWEEN 0 AND 98 " . $keywordCondition . "
            ORDER BY " . $sortable->order_by;

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

        return view('approval.index', compact('data', 'inputs', 'sortable'));
    }

    public function show(Request $request, $id)
    {
        // get detail of application
        $app = DB::table('applications')
            ->select(
                'applications.*',
                DB::raw("CONCAT(CONCAT(forms.prefix,'-'),LPAD(applications.id, " . config('const.num_fillzero') . ", '0')) AS application_no"),
                'steps.flow_id',
                'steps.approver_id',
                'steps.approver_type',
                'steps.order',
                'steps.select_order',
                DB::raw('us.name AS applicant'),
                DB::raw('(SELECT MAX(step_type) FROM steps WHERE flow_id = flows.id) AS step_count')
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
            ->join('users AS us', 'us.id', '=', 'applications.created_by')
            ->where('applications.id', '=', $id)
            ->where('applications.status', '<>', config('const.application.status.draft'))
            ->where('applications.deleted_at', '=', null)
            ->first();

        if (empty($app)) {
            abort(404);
        }

        // get list of approver (include TO & CC)
        $approvers = DB::table('steps')
            ->select('steps.approver_id')
            ->where('steps.flow_id', function ($query) use ($id) {
                $query->select('steps.flow_id')
                    ->from('applications')
                    ->join('steps', 'steps.group_id', 'applications.group_id')
                    ->where('applications.id', $id)
                    ->where('applications.deleted_at', '=', null)
                    ->limit(1);
            })
            ->where('steps.step_type', $app->current_step)
            ->get()
            ->toArray();

        // check logged user has permission to access
        $approvers = Arr::pluck($approvers, 'approver_id');
        if (!in_array(Auth::user()->id, $approvers) && $app->created_by !== Auth::user()->id) {
            abort(403);
        }

        return view('approval.show', compact('app'));
    }

    public function update(Request $request, $id)
    {
        if (isset($request->approve) || isset($request->reject) || isset($request->declined)) {

            $user = Auth::user();
            $inputs = $request->input();

            //check logged user has approval permission
            if (!$user->approval) {
                abort(403);
            }

            // get application detail
            $cols = [
                'applications.*',
                'steps.flow_id',
                'steps.approver_id',
                'steps.approver_type',
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
            $appDetail = DB::table('applications')
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
                // ->whereRaw('applications.status between 0 and 98')
                ->first();

            // not found application
            if (empty($appDetail)) {
                abort(404);
            }
            // check available status application
            if ($appDetail->status < 0 || $appDetail->status > 98) {
                // return Common::redirectBackWithAlertFail('Thao tac khong hop le doi voi don nay.')->with('inputs', $inputs);
                abort(404);
            }
            // check available next approval
            if ($appDetail->approver_id != $user->id) {
                // return Common::redirectBackWithAlertFail('Ban khong co quyen de thao tac')->with('inputs', $inputs);
                abort(403);
            }

            DB::beginTransaction();
            try {
                if (isset($request->approve)) {
                    $data['status'] = $appDetail->order;
                    // make application next to step of approval flow (with Leave Application just have one step)
                    if ($appDetail->order == config('const.application.status.completed')) {
                        if ($appDetail->form_id == config('const.form.biz_trip') || $appDetail->form_id == config('const.form.entertainment')) {
                            // setup for next step ortherwise application is completed
                            if ($appDetail->current_step < $appDetail->step_count) {
                                // get next step of approval flow
                                $nextStep = $appDetail->current_step + 1;
                                // get group for next step
                                $group = DB::table('groups')
                                    ->select('groups.*')
                                    ->join('budgets', function ($join) use ($nextStep, $appDetail) {
                                        $join->on('groups.budget_id', '=', 'budgets.id')
                                            ->where('budgets.budget_type', '=', $appDetail->budget_type)
                                            ->where('budgets.step_type', '=', $nextStep)
                                            ->where('budgets.position', '=', $appDetail->budget_position)
                                            ->where('budgets.deleted_at', '=', null);
                                    })
                                    ->where([
                                        'groups.applicant_id' => $appDetail->group_applicant_id,
                                        'groups.budget_type_compare' => $appDetail->budget_type_compare,
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
                        } elseif ($appDetail->form_id == config('const.form.leave')) {
                            // for leave application
                            $leave = Leave::where('application_id', $appDetail->id)->first();
                            if (!empty($leave)) {
                                // if leave_code is AL or SL (with paid_type = AL)
                                if (
                                    $leave->code_leave == config('const.code_leave.AL')
                                    || ($leave->code_leave == config('const.code_leave.SL') && $leave->paid_type == config('const.paid_type.AL'))
                                ) {
                                    $applicant = User::find($appDetail->created_by);
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
                } elseif (isset($request->declined)) {
                    $data['status'] = config('const.application.status.declined');
                }

                $data['comment']    = $inputs['comment'];
                $data['updated_by'] = $user->id;
                $data['updated_at'] = Carbon::now();

                DB::table('applications')->where('id', $id)->update($data);

                // create approval history
                $historyData = [
                    'approved_by'       => $user->id,
                    'application_id'    => $user->id,
                    'status'            => $data['status'],
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                ];
                DB::table('history_approval')->insert($historyData);

                DB::commit();

                return Common::redirectRouteWithAlertSuccess('user.approval.index');
            } catch (Exception $ex) {
                DB::rollBack();
                $msgErr = null;
                if ($ex instanceof NotFoundFlowSettingException) {
                    $msgErr = $ex->getMessage();
                }
                return Common::redirectBackWithAlertFail($msgErr);
            }
        } else {
            abort(404);
        }
    }
}
