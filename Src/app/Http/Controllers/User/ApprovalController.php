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
            'approver_type' => config('const.approver_type.to'),
        ];
        if (isset($request->application_type)) {
            $formIdCondition = " AND a.`form_id` = :formId";
            $params['formId'] = $request->application_type;
        }
        if (isset($request->keyword)) {
            $applicantNameCondition = " us.`name` LIKE :applicantName";
            $appNoCondition = "CONCAT(CONCAT(fo.prefix,'-'),LPAD(a.`id`, " . $fillZero . ", '0')) LIKE :applicationNo";
            $keywordCondition = " AND (" . $appNoCondition . " OR " . $applicantNameCondition . ")";
            $params['applicantName'] = '%' . $request->keyword . '%';
            $params['applicationNo'] = '%' . $request->keyword . '%';
        }

        // sorting by column
        $sortColNames = [
            'application_no' => __('label.application_no')
        ];
        $sort = Common::getSortColumnHeader($request, $sortColNames, 0, 0, true);

        // get data
        $sql = "SELECT
                 CONCAT(CONCAT(fo.prefix,'-'),LPAD(a.`id`, " . $fillZero . ", '0')) AS application_no
                ,a.`id`             AS application_id
                ,a.`current_step`
                ,a.`status`
                ,a.`created_at`     AS apply_date
                ,f.`form_id`
                ,f.`group_id`
                ,s.`id` AS step_id
                ,s.`approver_id`
                ,s.`step_type`
                ,s.`approver_type`
                ,u.`name`           AS approver_name
                ,us.`name`          AS applicant_name
                ,f.id               AS flow_id
                ,fo.name            AS application_type
                ,(
                    SELECT  us.name
                    FROM    steps
                            INNER JOIN users us ON us.id = approver_id
                    WHERE   `flow_id` = s.`flow_id` 
                    AND     `approver_type` = s.`approver_type`
                    AND     (
                                (`step_type` = s.`step_type` AND s.`order` <> :completed1 AND `select_order` = s.`order`)
                                OR
                                (s.`order` = :completed2 AND (`step_type` = (s.`step_type` + 1) AND `select_order` = 0))
                            )
                ) AS next_approver
            FROM    applications a
                    INNER JOIN forms fo ON a.`form_id` = fo.`id` " . $formIdCondition . "
                    INNER JOIN flows f ON f.`form_id` = a.`form_id` AND f.`group_id` = a.`group_id`
                    INNER JOIN steps s ON s.`flow_id` = f.`id` AND s.`approver_type` = :approver_type AND a.`status` = s.`select_order` AND s.`step_type` = a.`current_step`
                    INNER JOIN users u ON u.`id` = s.`approver_id` AND s.`approver_id` = :userId
                    INNER JOIN users us ON us.`id` = a.`created_by`
            WHERE   a.`status` BETWEEN 0 AND 98 " . $keywordCondition ."
            ORDER BY " . $sort->order_by;

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

        return view('approval.index', compact('data', 'inputs', 'sort'));
    }

    public function show(Request $request, $id)
    {
        // get detail of application
        $app = DB::table('applications')
            ->select(
                'applications.*',
                DB::raw("CONCAT(CONCAT(forms.prefix,'-'),LPAD(applications.`id`, " . config('const.num_fillzero') . ", '0')) AS application_no"),
                'steps.flow_id',
                'steps.approver_id',
                'steps.approver_type',
                'steps.order',
                'steps.select_order',
                DB::raw('us.name AS applicant'),
                DB::raw('(SELECT MAX(`step_type`) FROM steps WHERE flow_id = flows.id) AS step_count')
            )
            ->join('flows', function ($join) {
                $join->on('flows.form_id', '=', 'applications.form_id')
                    ->whereRaw('flows.group_id = applications.group_id');
            })
            ->leftJoin('steps', function ($join) {
                $join->on('steps.flow_id', '=', 'flows.id')
                    ->whereRaw('steps.select_order = applications.status')
                    ->whereRaw('steps.step_type = applications.current_step');
            })
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
        $approvers = DB::table('applications')
            ->select('steps.approver_id')
            ->join('flows', function ($join) {
                $join->on('flows.form_id', '=', 'applications.form_id')
                    ->whereRaw('flows.group_id = applications.group_id');
            })
            ->join('steps', 'steps.flow_id', '=', 'flows.id')
            ->where('applications.id', '=', $id)
            ->where('applications.deleted_at', '=', null)
            ->get()
            ->toArray();

        $approvers = Arr::pluck($approvers, 'approver_id');
        // check logged user has permission to access
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

            $appDetail = DB::table('applications')
                ->select(
                    'applications.*',
                    'steps.flow_id',
                    'steps.approver_id',
                    'steps.approver_type',
                    'steps.order',
                    'steps.select_order',
                    DB::raw('(SELECT MAX(`step_type`) FROM steps WHERE flow_id = flows.id) AS step_count')
                )
                ->join('flows', function ($join) {
                    $join->on('flows.form_id', '=', 'applications.form_id')
                        ->whereRaw('flows.group_id = applications.group_id');
                })
                ->join('steps', function ($join) {
                    $join->on('steps.flow_id', '=', 'flows.id')
                        ->whereRaw('steps.select_order = applications.status')
                        ->whereRaw('steps.step_type = applications.current_step');
                })
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
                            if ($appDetail->current_step < $appDetail->step_count) {
                                $data['current_step'] = $appDetail->current_step + 1;
                                $data['status'] = config('const.application.status.applying');
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
                                        $dayUse = empty($leave->days_use) ? 0 : $leave->days_use;
                                        $timeUse = empty($leave->times_use) ? 0 : $leave->times_use;
                                        $applicant->leave_remaining_days = $applicant->leave_remaining_days - $dayUse;
                                        $applicant->leave_remaining_time = $applicant->leave_remaining_time - $timeUse;
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

                $data['comment'] = $inputs['comment'];
                $data['updated_by'] = $user->id;
                $data['updated_at'] = Carbon::now();

                DB::table('applications')->where('id', $id)->update($data);

                DB::commit();

                return Common::redirectBackWithAlertSuccess();
            } catch (Exception $ex) {
                DB::rollBack();
                return Common::redirectBackWithAlertFail();
            }
        } else {
            abort(404);
        }
    }
}
