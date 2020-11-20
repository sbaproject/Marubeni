<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Libs\Common;
use App\Models\User;
use App\Models\Leave;
use App\Models\Application;
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
                ,f.id AS flow_id
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
            WHERE   a.`status` BETWEEN 0 AND 98 " . $keywordCondition;

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

        return view('approval.index', compact('data', 'inputs'));
    }

    public function show(Request $request, Application $app)
    {
        return view('approval.show', compact('app'));
    }

    public function update(Request $request, Application $app)
    {
        if (isset($request->approve) || isset($request->reject) || isset($request->declined)) {

            $user = Auth::user();

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
                    DB::raw('(SELECT MAX(`step_type`) FROM steps WHERE flow_id = 5) AS step_count')
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
                ->where('applications.id', '=', $app->id)
                ->where('applications.deleted_at', '=', null)
                // ->whereRaw('applications.status between 0 and 98')
                ->first();

                dd($appDetail);

            // not found application
            if (empty($appDetail)) {
                abort('Khong the thao tac voi don nay');
            }
            // check available status application
            if ($appDetail->status < 0 || $appDetail->status > 98) {
                return Common::redirectBackWithAlertFail('Thao tac khong hop le doi voi don nay.');
            }
            // check available next approval
            if ($appDetail->approver_id != $user->id) {
                return Common::redirectBackWithAlertFail('Ban khong co quyen de thao tac');
            }

            DB::beginTransaction();
            try {
                // for leave application
                if ($appDetail->form_id == config('const.form.leave')) {
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

                // update status


                DB::commit();

                return Common::redirectBackWithAlertSuccess();
            } catch (Exception $ex) {

                DB::rollBack();
                dd($ex);
            }





            if (isset($request->approve)) {
            } elseif (isset($request->reject)) {
            } elseif (isset($request->declined)) {
            }
        } else {
            abort(404);
        }
    }
}
