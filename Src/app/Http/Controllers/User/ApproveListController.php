<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class ApproveListController extends Controller
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
            ['path' => route('user.approve.list')]
        );

        return view('user.approvelist', compact('data', 'inputs'));
    }
}
