<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\User;
use App\Models\Flow;
use Illuminate\Support\Facades\DB;
use App\Libs\Common;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminFlowSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.flow.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $flow = DB::table('flows')->orderBy('id', 'desc')->whereNull('deleted_at')->first();
        $flowNo = 1;
        if (!empty($flow)){
            $flowNo = $flow->id;
        }
        $forms =  Form::all();
        $users =  DB::table('users')->where('approval', 1)->whereNull('deleted_at')->get();
        $applicants = DB::table('applicants')
            ->join('departments', 'applicants.department_id', '=', 'departments.id')         
            ->select('applicants.id' ,'applicants.location', 'applicants.role', 'departments.name')
            ->orderBy('applicants.id', 'asc')
            ->whereNull('applicants.deleted_at')
            ->get();

        $budgets = DB::table('budgets')->whereNull('deleted_at')->get();
        $budgetPO = DB::table('budgets')->where('position', 'PO')->whereNull('deleted_at')->first()->amount;
        $budgetNotPO = DB::table('budgets')->where('position', 'Not PO')->whereNull('deleted_at')->first()->amount;

        $locations = config('const.location');
        $roles = config('const.role');

        $applicantRoles = array();
        foreach ($applicants as $applicant) {
            $data = array();
            $data['id'] = $applicant->id;
            $data['name'] = strtoupper(array_search($applicant->location, $locations)) . ' - ' . $applicant->name . ' - ' . array_search($applicant->role, $roles);
            $applicantRoles[] = $data;
        }
        return view('admin.flow.create', compact('flowNo' ,'forms', 'users', 'applicantRoles', 'budgets', 'budgetPO', 'budgetNotPO'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // get data inputs
        $data = $request->input();
        DB::transaction(function() use ($data) {
            $user = Auth::user();
            $flowName = $data['approval_flow_name'];
            $formId = (int)$data['application_form'];
            $applicantId = $data['applicant'];
            $budgetTypePo = $data['budget_type_po'];
            $budgetTypeNotPo = $data['budget_type_not_po'];
            $groupId = 0;
            $budgetId =0;
            // Form Leave
            if ($formId === 1){
                $group = DB::table('groups')->where('applicant_id', $applicantId)->first();
                if (empty($group)){
                   $groupId = DB::table('groups')->insertGetId(['applicant_id' => $applicantId, 'created_by' => $user->id, 'created_at' => Carbon::now()]);
                }else{
                   $groupId = $group->id;
                }
            // Form Trip
            }else if ($formId === 2){
                $item = $data['trip'];
                $budgetId = $data['budget_form_'.$item.'_step_1'];
                $group = DB::table('groups')->where([['applicant_id', '=' , $applicantId], ['budget_id', '=' , $budgetId]])->first();
                if (empty($group)){
                   $groupId = DB::table('groups')->insertGetId(['applicant_id' => $applicantId, 'budget_id' => $budgetId, 'created_by' => $user->id, 'created_at' => Carbon::now()]);
                }else{
                   $groupId = $group->id;
                }
            // Form Business
            }else if ($formId === 3){
                $item = $data['PO'];
                $budgetTypeCompare = 0;
                if ($item === 'PO'){
                    $budgetTypeCompare = $budgetTypePo;
                }else{
                    $budgetTypeCompare = $budgetTypeNotPo;
                }
                $budgetId = $data['budget_form_'.$item.'_step_1'];
                $group = DB::table('groups')->where([['applicant_id', '=' , $applicantId], ['budget_id', '=' , $budgetId], ['budget_type_compare', '=' , $budgetTypeCompare]])->first();
                if (empty($group)){
                   $groupId = DB::table('groups')->insertGetId(['applicant_id' => $applicantId, 'budget_id' => $budgetId, 'budget_type_compare' => $budgetTypeCompare, 'created_by' => $user->id, 'created_at' => Carbon::now()]);
                }else{
                   $groupId = $group->id;
                }
            }

            $dataFlow = array();
            $dataFlow['flow_name']  = $flowName;
            $dataFlow['form_id']    = $formId;
            $dataFlow['group_id']   = $groupId;
            $dataFlow['created_by'] = $user->id;
            $dataFlow['created_at'] = Carbon::now();
            // save new flow
            $flowId = DB::table('flows')->insertGetId($dataFlow);

            $destinationList = $data['destination'];
            $approverList = $data['approver'];
            $dataStepList = array();
            foreach ($destinationList as $key => $destinationSteps) {
                $stepType = $key;
                if ($formId === 1){
                    $stepType = 2;
                }
                $order = 0;
                $selectOrder = -1;
                foreach ($destinationSteps as $k => $destination) {
                    $approverType = (int)$destination;
                    $approverId = $approverList[$key][$k];
                    $order = $order + 1;
                    if ($approverType === 0){
                        $selectOrder =  $selectOrder + 1;
                    }
                    if ($order === count($destinationSteps) && $approverType === 0){
                        $order = 99;
                    }

                    $dataStep = array();
                    $dataStep['flow_id']        = $flowId;
                    $dataStep['approver_id']    = $approverId;
                    $dataStep['approver_type']  = $approverType;
                    $dataStep['step_type']      = $stepType;
                    $dataStep['order']          = $order;
                    $dataStep['select_order']   = $selectOrder;
                    $dataStep['created_by']     = $user->id;
                    $dataStep['created_at']     = Carbon::now();
                    $dataStepList[] = $dataStep;
                }
            }
            DB::table('steps')->insert($dataStepList);
        });

       return Common::redirectRouteWithAlertSuccess('admin.flow.list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return view('admin.flow.create');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Check exists the applicant role of application form
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request)
    {
        $data = $request->all();

        $formId = (int)$data['application_form'];
        $applicantId = $data['applicant'];
        $budgetTypePo = $data['budget_type_po'];
        $budgetTypeNotPo = $data['budget_type_not_po'];
        $groupId = 0;
        $budgetId =0;

        // Form Leave
        if ($formId === 1){
            $group = DB::table('groups')->where('applicant_id', $applicantId)->first();
            if (!empty($group)){
              $groupId = $group->id;
            }
        // Form Trip
        }else if ($formId === 2){
            $item = $data['trip'];
            $budgetId = $data['budget_form_'.$item.'_step_1'];
            $group = DB::table('groups')->where([['applicant_id', '=' , $applicantId], ['budget_id', '=' , $budgetId]])->first();
            if (!empty($group)){
               $groupId = $group->id;
            }
        // Form Business
        }else if ($formId === 3){
            $item = $data['PO'];
            $budgetTypeCompare = 0;
            if ($item === 'PO'){
                $budgetTypeCompare = $budgetTypePo;
            }else{
                $budgetTypeCompare = $budgetTypeNotPo;
            }             
            $budgetId = $data['budget_form_'.$item.'_step_1'];
            $group = DB::table('groups')->where([['applicant_id', '=' , $applicantId], ['budget_id', '=' , $budgetId], ['budget_type_compare', '=' , $budgetTypeCompare]])->first();
            if (!empty($group)){
               $groupId = $group->id;
            }
        }

        $status = 1;
        if ($groupId  > 0){
            $flow = DB::table('flows')->where([['form_id', '=' , $formId], ['group_id', '=' , $groupId]])->first();
            if (!empty($group)){
                $status = 0; // form and group is existed.
            }
        }
        return response()->json(['status'=>$status]);
}
