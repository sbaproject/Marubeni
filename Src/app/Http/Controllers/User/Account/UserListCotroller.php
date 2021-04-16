<?php

namespace App\Http\Controllers\User\Account;

use Carbon\Carbon;
use App\Libs\Common;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserListCotroller extends Controller
{
    public function index(Request $request)
    {
        // dropdownlist items
        $locations = config('const.location');
        $departments = Department::all();

        // get parameter query string
        $conditions = $request->only(['location','user_no', 'department', 'name', 'show_del_user']);

        // sorting columns
        $sortableCols = [
            'user_no'           => __('label._no_'),
            'department_name'   => __('validation.attributes.department'),
            'user_name'         => __('validation.attributes.user.name'),
        ];
        $sortable = Common::getSortable($request, $sortableCols, 0, 0, true);

        // selection columns
        $selectCols = [
            'users.id           as user_id',
            'users.user_no',
            'users.name         as user_name',
            'departments.name   as department_name',
        ];

        // get results
        $users = User::join('departments', function ($join) {
            $join->on('users.department_id', '=', 'departments.id');
        })
        ->select($selectCols)
        ->where('users.super_admin_flg', config('const.check.off')) // not show super admin account
        ->when(isset($conditions['location']), function ($q) use ($conditions) {
            return $q->where('users.location', '=', $conditions['location']);
        })
        ->when(isset($conditions['department']), function ($q) use ($conditions) {
            return $q->where('users.department_id', '=', $conditions['department']);
        })
        ->when(isset($conditions['name']), function ($q) use ($conditions) {
            return $q->where('users.name', 'LIKE', '%' . trim($conditions['name']) . '%');
        })
        ->when(isset($conditions['user_no']), function ($q) use ($conditions) {
            return $q->where("users.user_no", "LIKE",  '%' . $conditions['user_no'] . '%');
        })
        ->when(isset($conditions['show_del_user']) && $conditions['show_del_user'] == "on", function ($q) {
            return $q->where('users.deleted_at', "<>", NULL);
        })
        ->orderByRaw($sortable->order_by);

        // allow trashed user
        if (isset($conditions['show_del_user']) && $conditions['show_del_user'] == "on") {
            $users = $users->withTrashed();
        }

        $users = $users->paginate(config('const.paginator.items'));

        return view('account_index', compact('conditions', 'locations', 'departments', 'users', 'sortable'));
    }

    public function delete(User $user)
    {
        $loggedUser = Auth::user();

        // do not delete your self
        if ($user->id === $loggedUser->id) {
            return Common::redirectBackWithAlertFail(__('msg.delete_fail'));
        }

        // super admin account do not delete
        if ($user->super_admin_flg == config('const.check.on')) {
            return Common::redirectBackWithAlertFail(__('msg.delete_fail'));
        }

        $user = User::find($user->id);
        $user->updated_by = $loggedUser->id;
        $user->deleted_at = Carbon::now();
        $user->save();

        return Common::redirectBackWithAlertSuccess(__('msg.delete_success'));
    }

    public function restore($id)
    {
        $restoreUser = User::where('id', $id)->withTrashed()->first();

        // not found user
        if (empty($restoreUser)) {
            abort(404);
        }

        // restore
        User::withTrashed()
            ->where('id', $id)
            ->update(
                [
                    'deleted_at' => null,
                    'updated_by' => Auth::user()->id
                ]
            );

        return Common::redirectBackWithAlertSuccess(__('msg.restore_success'));
    }


    public function exportExcel(Request $request){      

        $font_family = 'Times New Roman';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth('14');
        $sheet->getColumnDimension('B')->setWidth('18');
        $sheet->getColumnDimension('C')->setWidth('17');
        $sheet->getColumnDimension('D')->setWidth('18');
        $sheet->getColumnDimension('E')->setWidth('20');
        $sheet->getColumnDimension('F')->setWidth('18');
        $sheet->getColumnDimension('G')->setWidth('15');
        $sheet->getColumnDimension('H')->setWidth('15');
        $sheet->getColumnDimension('I')->setWidth('18');
        $sheet->getColumnDimension('J')->setWidth('18');

        $sheet->setCellValue('A1', 'Marubeni Staff Leave Management Data');
        $sheet->mergeCells("A1:J1");
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A1")->getFont()->setName($font_family)->setSize(18)->setBold(true);

        $sheet->setCellValue('A2', 'Export Date');
        $sheet->setCellValue('A3', 'Location');
        $sheet->setCellValue('A4', 'Department');

        $sheet->getStyle('A2:A4')->getAlignment()->setHorizontal('left');
        $sheet->getStyle("A2:A4")->getFont()->setName($font_family)->setSize(12);

        // get parameter query string
        $conditions = $request->only(['location','user_no', 'department', 'name', 'show_del_user']);

        $location_id = '';
        $location_name = 'All';
        if (!empty($conditions['location'])) {
            $location_id = $conditions['location'];
            if ($location_id == 0){
                $location_name = __('label.hn');
            }else{
                $location_name = __('label.hcm');
            }
        }

        $department_id = '';
        $department_name = 'All';
        if (!empty($conditions['department'])) {
            $department_id = $conditions['department'];
            $departments = Department::where('id', '=' , $department_id)->first();
        }

        $sheet->setCellValue('B2', date('Y/m/d'));
        $sheet->setCellValue('B3', $location_name);
        $sheet->setCellValue('B4', $department_name);

        $sheet->getStyle('B2:B4')->getAlignment()->setHorizontal('left');
        $sheet->getStyle("B2:B4")->getFont()->setName($font_family)->setSize(12);        

        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
            'font'  => array(
                'size'  => 12,
                'name'  => $font_family
            )
        );


        $styleArray1 = array(
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('argb' => '000000'),

        );

        $sheet->setCellValue('A7', 'No');
        $sheet->setCellValue('B7', 'Employee No');
        $sheet->setCellValue('C7', 'Location');
        $sheet->setCellValue('D7', 'Department');
        $sheet->setCellValue('E7', 'Name');
        $sheet->setCellValue('F7', 'Entitled this year');
        $sheet->setCellValue('G7', 'Used days');
        $sheet->setCellValue('H7', 'Used hours');
        $sheet->setCellValue('I7', 'Remaining days');
        $sheet->setCellValue('J7', 'Remaining hours');

        $sheet->getStyle('A7:J7')->getAlignment()->setHorizontal('left');
        $sheet->getStyle("A7:J7")->getFont()->setName($font_family)->setSize(12)->setBold(true);
        $sheet->getStyle('A7:J7')->getBorders()->getAllBorders()->applyFromArray($styleArray1);


        // sorting columns
        $sortableCols = [
            'user_no'           => __('label._no_'),
            'department_name'   => __('validation.attributes.department'),
            'user_name'         => __('validation.attributes.user.name'),
        ];
        $sortable = Common::getSortable($request, $sortableCols, 0, 0, true);

        $monthCurr = date('n');
        $monthYear = date('Y');
         $monthCurr =  1;

        $startDate = date('Y-04-01 00:00:00');
        $endDare = date('Y-m-d 23:59:59');

        if (in_array($monthCurr, array(1,2,3))){
            $y =  $monthYear - 1;
            $startDate = date($y.'-04-01 00:00:00');
        }

        // selection columns
        $selectCols = [
            'users.id           as user_id',
            'users.user_no',
            'users.name         as user_name',
            'departments.name   as department_name',
            'users.leave_days',
            'users.leave_remaining_days',
            'users.leave_remaining_time',
            'users.location',
            DB::raw('(SELECT SUM(leaves.days_use) as days_use FROM applications LEFT JOIN leaves ON applications.id = leaves.application_id WHERE users.id = applications.created_by AND applications.status = 99 AND applications.form_id =  1 AND  applications.created_at BETWEEN "'.$startDate.'" AND "'.$endDare.'"  GROUP BY applications.created_by) as use_days'),
            DB::raw('(SELECT SUM(leaves.times_use) as times_use FROM applications LEFT JOIN leaves ON applications.id = leaves.application_id WHERE users.id = applications.created_by AND applications.status = 99 AND applications.form_id =  1 AND  applications.created_at BETWEEN "'.$startDate.'" AND "'.$endDare.'"  GROUP BY applications.created_by) as use_hours')
        ];

        // get results
        $users = User::join('departments', function ($join) {
            $join->on('users.department_id', '=', 'departments.id');
        })
        ->select($selectCols)
        ->where('users.super_admin_flg', config('const.check.off')) // not show super admin account
        ->when(isset($conditions['location']), function ($q) use ($conditions) {
            return $q->where('users.location', '=', $conditions['location']);
        })
        ->when(isset($conditions['department']), function ($q) use ($conditions) {
            return $q->where('users.department_id', '=', $conditions['department']);
        })
        ->when(isset($conditions['name']), function ($q) use ($conditions) {
            return $q->where('users.name', 'LIKE', '%' . trim($conditions['name']) . '%');
        })
        ->when(isset($conditions['user_no']), function ($q) use ($conditions) {
            return $q->where("users.user_no", "LIKE",  '%' . $conditions['user_no'] . '%');
        })
        ->when(isset($conditions['show_del_user']) && $conditions['show_del_user'] == "on", function ($q) {
            return $q->where('users.deleted_at', "<>", NULL);
        })
        ->orderByRaw($sortable->order_by);

        // allow trashed user
        if (isset($conditions['show_del_user']) && $conditions['show_del_user'] == "on") {
            $users = $users->withTrashed();
        }

        $users = $users->get();

        $row = 8;
        $index = 1;

        foreach($users as $user){

            $sheet->setCellValue('A'.$row, $index);
            $sheet->setCellValue('B'.$row, $user->user_no);
            $sheet->setCellValue('C'.$row, $user->location == 0 ? __('label.hn') : __('label.hcm'));
            $sheet->setCellValue('D'.$row, $user->department_name);
            $sheet->setCellValue('E'.$row, $user->user_name);
            $sheet->setCellValue('F'.$row, $user->leave_days);
            $sheet->setCellValue('G'.$row, empty($user->use_days) ? 0 :  $user->use_days);
            $sheet->setCellValue('H'.$row, empty($user->use_hours) ? 0 :  $user->use_hours);
            $sheet->setCellValue('I'.$row, $user->leave_remaining_days);
            $sheet->setCellValue('J'.$row, $user->leave_remaining_time);


            $sheet->getStyle('B'.$row.':E'.$row)->getAlignment()->setHorizontal('left');
            $sheet->getStyle('F'.$row.':J'.$row)->getAlignment()->setHorizontal('right');
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal('right');
            $sheet->getStyle('A'.$row.':J'.$row)->getFont()->setName($font_family)->setSize(12);
            $sheet->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->applyFromArray($styleArray1);

            $index++;
            $row++;
        }


        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        $fileName = 'LeaveManagement_'.date('Ymd').'.xlsx';
        $writer->save($fileName);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileName));
        readfile($fileName);
        unlink($fileName);
        exit();
    }
}
