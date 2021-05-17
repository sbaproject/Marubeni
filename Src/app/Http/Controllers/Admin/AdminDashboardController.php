<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libs\Common;
use App\Models\Department;
use App\Models\Form;
use App\Models\Step;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->input();

        $locations = config('const.location');
        $departments = Department::where('role', 0)->get();
        $forms = Form::all();

        $str_date = null;
        $end_date = null;

        //When Search by Time
        if (isset($data['dataDateFrom']) && isset($data['dataDateTo'])) {
            $str_date = $data['dataDateFrom'] . ' 00:00:00';
            $end_date = $data['dataDateTo'] . ' 23:59:59';
        } else {
            $str_date = config('const.init_time_search.from');
            $end_date = config('const.init_time_search.to');
        }

        if (isset($data['status']) && $data['status'] != '' && $data['typeApply'] == '') {
            $data['typeApply'] = $data['status'];
        }

        if (!isset($data['typeApply'])) {
            $sta = -2;
            $end = 99;
            $stepStr = 1;
            $stepEnd = 2;

            // Type Application
            $intstatus = config('const.application.status.all');
        } else {

            //Set case in Status is Approvel
            if (intval($data['typeApply']) == config('const.application.status.applying')) {
                $sta = 0;
                $end = 98;
                $stepStr = 1;
                $stepEnd = 1;
            } else if (intval($data['typeApply']) == config('const.application.status.approvel')) {
                $sta = 0;
                $end = 98;
                $stepStr = 2;
                $stepEnd = 2;
            } else {
                $sta = intval($data['typeApply']);
                $end = intval($data['typeApply']);
                $stepStr = 1;
                $stepEnd = 2;
            }

            // Type Application
            $intstatus = $data['typeApply'];
        }

        // sorting columns
        $sortColNames = [
            'application_no'    => __('label.dashboard_application_no'),
            'form_name'  => __('label.dashboard_application_name'),
            'status'        => __('label.dashboard_status'),
            'created_at'     => __('label.dashboard_apply_date'),
        ];
        $sortable = Common::getSortable($request, $sortColNames, 0, 0, true);
        $form_id = '';
        if (!empty($data['form'])) {
            $form_id = $data['form'];
        }

        $location_id = '';
        if (!empty($data['location'])) {
            $location_id = $data['location'];
        }

        $department_id = '';
        if (!empty($data['department'])) {
            $department_id = $data['department'];
        }        

        //Get Applications By Condition
        $list_application = $this->list_application($sta, $end, $stepStr, $stepEnd, $str_date, $end_date, $sortable, $form_id, $location_id, $department_id);
        $list_application = $list_application->paginate(config('const.paginator.items'));

        //Count Applications By Condition
        $count_applying  = $this->list_application_count(0, 98, 1, 1, $str_date, $end_date, $form_id, $location_id, $department_id)->count();

        $count_approval  = $this->list_application_count(0, 98, 2, 2, $str_date, $end_date, $form_id, $location_id, $department_id)->count();

        $count_declined  = $this->list_application_count(-1, -1, 1, 2, $str_date, $end_date, $form_id, $location_id, $department_id)->count();

        $count_reject  = $this->list_application_count(-2, -2, 1, 2, $str_date, $end_date, $form_id, $location_id, $department_id)->count();

        $count_completed  = $this->list_application_count(99, 99, 1, 2, $str_date, $end_date, $form_id, $location_id, $department_id)->count();

        $conditions = $data;

        return view('admin_dashboard_index', compact('list_application', 'count_applying', 'count_approval', 'count_declined', 'count_reject', 'count_completed', 'str_date', 'end_date', 'intstatus', 'sortable', 'locations', 'departments', 'forms', 'conditions'));
    }

    //Get List Application by Condition
    private function list_application($sta, $end, $stepStr, $stepEnd, $str_date, $end_date, $sortable, $form_id, $location_id, $department_id)
    {

        //List
        $list_application =  DB::table('applications')
            ->select(
                'users.name as user_name',
                'applications.application_no',
                'forms.name As form_name',
                'applications.created_at As created_at',
                DB::raw('(CASE WHEN (applications.status >= 0 AND applications.status <= 98 AND applications.current_step = 1) THEN "' . __('label.dashboard_applying') . '" ELSE (CASE WHEN (applications.status = "' . config('const.application.status.declined') . '") THEN "' . __('label.dashboard_declined') . '" ELSE (CASE WHEN (applications.status = "' . config('const.application.status.reject') . '") THEN "' . __('label.dashboard_reject') . '" ELSE (CASE WHEN (applications.status = "' . config('const.application.status.completed') . '") THEN "' . __('label.dashboard_completed') . '" ELSE ("' . __('label.dashboard_approval') . '") END ) END) END) END) AS status'),
                'applications.status As status_css',
                'applications.current_step As current_step',
                'applications.form_id As form_id',
                'applications.group_id As group_id',
                'applications.id As id',
                DB::raw('CASE WHEN applications.subsequent = 0 THEN "No" ELSE "Yes" END as subsequent'),
                'departments.name as departments_name',
                DB::raw('CASE WHEN users.location = 0 THEN "'.__('label.hn').'" ELSE "'.__('label.hcm').'" END as location_name')
            )

            //Join
            ->join('forms', 'applications.form_id', '=', 'forms.id')
            ->leftJoin('users', 'applications.created_by', '=', 'users.id')
            ->leftJoin('departments', 'departments.id', '=', 'users.department_id')
            //Where
            ->where('applications.status', '>=', $sta)
            ->where('applications.status', '<=', $end)
            ->where('applications.current_step', '>=', $stepStr)
            ->where('applications.current_step', '<=', $stepEnd)
            ->where('applications.created_at', '>=', $str_date)
            ->where('applications.created_at', '<=', $end_date)
            ->whereNull('applications.deleted_at');

        if (!empty($form_id)){
            $list_application->where('applications.form_id', '=', $form_id);
        }

        if (!empty($location_id)){
            $list_application->where('users.location', '=', $location_id);
        }
        if (!empty($department_id)){
            $list_application->where('users.department_id', '=', $department_id);
        }

        $list_application = $list_application->orderBy($sortable->s, $sortable->d);

        return  $list_application;
    }

    //Get Count
    private function list_application_count($sta, $end, $stepStr, $stepEnd, $str_date, $end_date, $form_id, $location_id, $department_id)
    {

        //List
        $list_application =  DB::table('applications')
            ->select(
                'application_no',
                'forms.name As form_name',
                'applications.created_at As created_at',
                'applications.status As status',
                'applications.current_step As current_step',
                'applications.form_id As form_id',
                'applications.id As id'
            )

            //Join
            ->join('forms', 'applications.form_id', '=', 'forms.id')

            //Where
            ->where('applications.status', '>=', $sta)
            ->where('applications.status', '<=', $end)
            ->where('applications.current_step', '>=', $stepStr)
            ->where('applications.current_step', '<=', $stepEnd)
            ->where('applications.created_at', '>=', $str_date)
            ->where('applications.created_at', '<=', $end_date)

            ->whereNull('applications.deleted_at');

        if (!empty($form_id)){
            $list_application->where('applications.form_id', '=', $form_id);
        } 

        if (!empty($location_id) || !empty($department_id)){
            $list_application->leftJoin('users', 'applications.created_by', '=', 'users.id');
            if (!empty($location_id)){
                $list_application->where('users.location', '=', $location_id);
            }
            if (!empty($department_id)){
                $list_application->where('users.department_id', '=', $department_id);
            }            
        }
        $list_application = $list_application->get();     

        return  $list_application;
    }

    public function exportExcel(Request $request){
        $data = $request->input();

        $str_date = null;
        $end_date = null;

        //When Search by Time
        if (isset($data['dataDateFrom']) && isset($data['dataDateTo'])) {
            $str_date = $data['dataDateFrom'] . ' 00:00:00';
            $end_date = $data['dataDateTo'] . ' 23:59:59';
        } else {
            $str_date = config('const.init_time_search.from');
            $end_date = config('const.init_time_search.to');
        }

        if (isset($data['status']) && $data['status'] != '') {
            $data['typeApply'] = $data['status'];
        }

        if (!isset($data['typeApply'])) {
            $sta = -2;
            $end = 99;
            $stepStr = 1;
            $stepEnd = 2;

            // Type Application
            $intstatus = config('const.application.status.all');
        } else {

            //Set case in Status is Approvel
            if (intval($data['typeApply']) == config('const.application.status.applying')) {
                $sta = 0;
                $end = 98;
                $stepStr = 1;
                $stepEnd = 1;
            } else if (intval($data['typeApply']) == config('const.application.status.approvel')) {
                $sta = 0;
                $end = 98;
                $stepStr = 2;
                $stepEnd = 2;
            } else {
                $sta = intval($data['typeApply']);
                $end = intval($data['typeApply']);
                $stepStr = 1;
                $stepEnd = 2;
            }

            // Type Application
            $intstatus = $data['typeApply'];
        }

        // sorting columns
        $sortColNames = [
            'application_no'    => __('label.dashboard_application_no'),
            'form_name'  => __('label.dashboard_application_name'),
            'status'        => __('label.dashboard_status'),
            'created_at'     => __('label.dashboard_apply_date'),
        ];
        $sortable = Common::getSortable($request, $sortColNames, 0, 0, true);
        $form_id = '';
        $form_name = 'All';
        if (!empty($data['form'])) {
            $form_id = $data['form'];            
            $form = Form::where('id', '=' , $form_id)->first();
            if(!empty($form)){
                $form_name = $form->name;
            }
        }

        $location_id = '';
        $location_name = 'All';
        if (!empty($data['location'])) {
            $location_id = $data['location'];
            if ($location_id == 0){
                $location_name = __('label.hn');
            }else{
                $location_name = __('label.hcm');
            }
        }

        $department_id = '';
        $department_name = 'All';
        if (!empty($data['department'])) {
            $department_id = $data['department'];
            $departments = Department::where('id', '=' , $department_id)->first();
        }

        $status_name = 'All';        
        if ($data['status'] != '')
        {
            if (intval($data['status']) == 0){
                $status_name = __('label.application_status_applying');
            }elseif (intval($data['status']) == 1){
                $status_name = __('label.application_status_approval');
            }elseif (intval($data['status']) == -1){
                $status_name = __('label.application_status_decline');
            }elseif (intval($data['status']) == -2){
                $status_name = __('label.application_status_reject');
            }elseif ($intval($data['status']) == 99){
                $status_name = __('label.application_status_complete');
            } 
        }
     

        //Get Applications By Condition
        $list_application = $this->list_application($sta, $end, $stepStr, $stepEnd, $str_date, $end_date, $sortable, $form_id, $location_id, $department_id);

        $list_application = $list_application->get();

        $font_family = 'Times New Roman';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth('16');
        $sheet->getColumnDimension('B')->setWidth('20');
        $sheet->getColumnDimension('C')->setWidth('20');
        $sheet->getColumnDimension('D')->setWidth('15');
        $sheet->getColumnDimension('E')->setWidth('20');
        $sheet->getColumnDimension('F')->setWidth('22');
        $sheet->getColumnDimension('G')->setWidth('20');
        $sheet->getColumnDimension('H')->setWidth('22');
        $sheet->getColumnDimension('I')->setWidth('22');
        $sheet->getColumnDimension('J')->setWidth('12');

        $sheet->setCellValue('A1', 'Marubeni Application Form Total Management');
        $sheet->mergeCells("A1:J1");
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A1")->getFont()->setName($font_family)->setSize(18)->setBold(true);

        $sheet->setCellValue('A2', 'Date');
        $sheet->setCellValue('A3', 'Location');
        $sheet->setCellValue('A4', 'Department');
        $sheet->setCellValue('A5', 'Application Type');
        $sheet->setCellValue('A6', 'Status');

        $sheet->getStyle('A2:A6')->getAlignment()->setHorizontal('left');
        $sheet->getStyle("A2:A6")->getFont()->setName($font_family)->setSize(12);

        $fromTo = $data['dataDateFrom'].' ~ '. $data['dataDateTo'];
        $sheet->setCellValue('B2', $fromTo);
        $sheet->mergeCells("B2:C2");        

        $sheet->setCellValue('B3', $location_name);
        $sheet->setCellValue('B4', $department_name);
        $sheet->setCellValue('B5', $form_name);
        $sheet->setCellValue('B6', $status_name);

        $sheet->getStyle('B2:B6')->getAlignment()->setHorizontal('left');
        $sheet->getStyle("B2:B6")->getFont()->setName($font_family)->setSize(12);        

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

        $sheet->setCellValue('A8', 'No');
        $sheet->setCellValue('B8', 'Application No');
        $sheet->setCellValue('C8', 'Application Type');
        $sheet->setCellValue('D8', 'Status');
        $sheet->setCellValue('E8', 'Location');
        $sheet->setCellValue('F8', 'Applicant Department');
        $sheet->setCellValue('G8', 'Applicant Name');
        $sheet->setCellValue('H8', 'Final Approver');
        $sheet->setCellValue('I8', 'Application Date');
        $sheet->setCellValue('J8', 'Subsequent');

        $sheet->getStyle('A8:J8')->getAlignment()->setHorizontal('left');
        $sheet->getStyle("A8:J8")->getFont()->setName($font_family)->setSize(12)->setBold(true);
        $sheet->getStyle('A8:J8')->getBorders()->getAllBorders()->applyFromArray($styleArray1);
        $row = 9;
        $index = 1;
        foreach($list_application as $application){

            $approver =  Step::select('users.name')
            ->join('users', 'users.id', 'steps.approver_id')
            ->where('steps.group_id', $application->group_id)
            ->where('steps.order', 99)
            ->orderBy('steps.step_type', 'DESC')           
            ->first();
            $approver_name = '';
            if (!empty($approver)){
                $approver_name = $approver->name;
            }

            $sheet->setCellValue('A'.$row, $index);
            $sheet->setCellValue('B'.$row, $application->application_no);
            $sheet->setCellValue('C'.$row, $application->form_name);
            $sheet->setCellValue('D'.$row, $application->status);
            $sheet->setCellValue('E'.$row, $application->location_name);
            $sheet->setCellValue('F'.$row, $application->departments_name);
            $sheet->setCellValue('G'.$row, $application->user_name);
            $sheet->setCellValue('H'.$row, $approver_name);
            $sheet->setCellValue('I'.$row, $application->created_at);
            $sheet->setCellValue('J'.$row, $application->subsequent);

            $sheet->getStyle('A'.$row.':J'.$row)->getAlignment()->setHorizontal('left');
            $sheet->getStyle('A'.$row.':J'.$row)->getFont()->setName($font_family)->setSize(12);
            $sheet->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->applyFromArray($styleArray1);

            $index++;
            $row++;
        }


        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        $fileName = 'ApplicationTotal_'.date('Ymd').'.xlsx';
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
