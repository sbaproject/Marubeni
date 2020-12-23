<?php

namespace App\Http\Controllers\Application\Business;

use Exception;
use Carbon\Carbon;
use App\Models\Businesstrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Entertainment\NotFoundFlowSettingException;
use App\Http\Controllers\ApplicationController;

class BusinesstripController extends ApplicationController
{
    public function __construct()
    {
        $this->formType = config('const.form.biz_trip');

        parent::__construct();
    }

    protected function create()
    {
        parent::create();

        return view($this->viewInputName, $this->currentCompatData);
    }

    protected function show($id)
    {
        parent::show($id);

        return view($this->viewInputName, $this->currentCompatData);
    }

    protected function checkEmptyApplication($application)
    {
        if (empty($application->business)) {
            abort(404);
        }
    }

    protected function doValidate($request, &$inputs)
    {
        if (isset($inputs['apply']) || isset($inputs['draft'])) {
            $rules = [];
            // attached file
            if ($request->file('input_file')) {
                $rules['input_file'] = config('const.rules.attached_file');
            }
            if (isset($inputs['apply'])) {
                $rules['budget_position']   = 'required_select';
                $rules['destinations']      = 'required';
                $rules['accommodation']     = 'required';
                $rules['accompany']         = 'required';
                $rules['borne_by']          = 'required';
                $rules['trip_dt_from']      = 'required';
                $rules['trip_dt_to']        = 'required';
                $rules['trans.*.departure'] = 'required';
                $rules['trans.*.arrive']    = 'required';
                $rules['trans.*.method']    = 'required';
            }
            $customAttributes = [
                'budget_position'   => __('label.business.budget_position'),
                'destinations'      => __('label.business.trip_destination'),
                'accommodation'     => __('label.business.accommodation'),
                'accompany'         => __('label.business.accompany'),
                'borne_by'          => __('label.business.borne_by'),
                'trans.*.departure' => __('label.business.departure'),
                'trans.*.arrive'    => __('label.business.arrival'),
                'trans.*.method'    => __('label.business.method'),
            ];
            $validator = Validator::make($inputs, $rules, [], $customAttributes);
            if ($validator->fails()) {
                unset($inputs['input_file']);
                return $validator;
            }
        }
    }

    protected function doSaveData($request, &$inputs, $app = null)
    {
        $msgErr = '';

        DB::beginTransaction();

        try {
            // get user
            $user = Auth::user();

            /**-------------------------
             * create application
             *-------------------------*/

            // get type form of application
            $formId = config('const.form.biz_trip');
            // get status
            $status = $this->getActionType($inputs);
            // get current step
            $currentStep = $this->getCurrentStep($app);
            // get budget position
            $budgetPosition = $inputs['budget_position'];
            // get group
            $group = DB::table('groups')
                ->select('groups.*')
                ->join('applicants', function ($join) use ($user) {
                    $join->on('groups.applicant_id', '=', 'applicants.id')
                        ->where('applicants.role', '=', $user->role)
                        ->where('applicants.location', '=', $user->location)
                        ->where('applicants.department_id', '=', $user->department_id)
                        ->where('applicants.deleted_at', '=', null);
                })
                ->join(
                    'budgets',
                    function ($join) use ($currentStep, $budgetPosition) {
                        $join->on('groups.budget_id', '=', 'budgets.id')
                            ->where('budgets.budget_type', '=', config('const.budget.budget_type.business'))
                            ->where('budgets.step_type', '=', $currentStep)
                            ->where('budgets.position', '=', $budgetPosition)
                            ->where('budgets.deleted_at', '=', null);
                    }
                )
                ->where('groups.deleted_at', '=', null)
                ->first();

            if (empty($group) && isset($inputs['apply'])) {
                throw new NotFoundFlowSettingException();
            }

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
            if (!$request->id) {
                $application['created_by']  = $user->id;
                $application['created_at']  = Carbon::now();

                $applicationId = DB::table('applications')->insertGetId($application);
            } else {
                DB::table('applications')->where('id', $request->id)->update($application);
            }

            /**-------------------------
             * create [Business Application] detail
             *-------------------------*/
            if ($request->id) {
                $biz = Businesstrip::where('application_id', $request->id)->first();
            }

            // prepare leave data
            $bizData = [
                'destinations'  => $inputs['destinations'],
                'trip_dt_from'  => $inputs['trip_dt_from'],
                'trip_dt_to'    => $inputs['trip_dt_to'],
                'accommodation' => $inputs['accommodation'],
                'accompany'     => $inputs['accompany'],
                'borne_by'      => $inputs['borne_by'],
                'comment'       => $inputs['comment'],
                'updated_by'    => $user->id,
                'updated_at'    => Carbon::now(),
            ];
            // for new
            if (!$request->id) {
                $bizData['application_id']  = $applicationId;
                $bizData['created_by']      = $user->id;
                $bizData['created_at']      = Carbon::now();
            }

            // DB::table('businesstrips')->updateOrInsert(['application_id' => $request->id], $bizData);
            // save business application
            if (!$request->id) {
                $bizId = DB::table('businesstrips')->insertGetId($bizData);
            } else {
                DB::table('businesstrips')->where('id', $biz->id)->update($bizData);
                DB::table('transportations')->where('businesstrip_id', $biz->id)->delete();
            }
            // save transportations
            if (!isset($bizId)) {
                $bizId = $biz->id;
            }
            $transportations = [];
            foreach ($inputs['trans'] as $value) {
                $item['businesstrip_id']    = $bizId;
                $item['departure']          = $value['departure'];
                $item['arrive']             = $value['arrive'];
                $item['method']             = $value['method'];
                $item['created_at']         = Carbon::now();
                $item['updated_at']         = Carbon::now();

                $transportations[] = $item;
            }
            DB::table('transportations')->insert($transportations);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            unset($inputs['input_file']);
            if ($ex instanceof NotFoundFlowSettingException) {
                $msgErr = $ex->getMessage();
            } else {
                $msgErr = __('msg.save_fail');
            }
        }

        return $msgErr;
    }

    protected function preview(Request $request, $id)
    {
        parent::preview($request, $id);

        return view($this->viewInputName, $this->currentCompatData);
    }

    protected function getInputs($request)
    {
        $inputs = $request->all();

        if (!isset($inputs['budget_postion'])) {
            $inputs['budget_postion'] = null;
        }
        if (!isset($inputs['subsequent'])) {
            $inputs['subsequent'] = null;
        }

        return $inputs;
    }
}
