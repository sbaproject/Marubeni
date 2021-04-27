<?php

namespace App\Http\Controllers\Application\Entertainment;

use Carbon\Carbon;
use App\Models\Budget;
use App\Models\Company;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\NotFoundFlowSettingException;
use App\Http\Controllers\Application\ApplicationController;

class EntertainmentController extends ApplicationController
{
    public function __construct()
    {
        $this->formType = config('const.form.entertainment');

        parent::__construct();
    }

    protected function create()
    {
        parent::create();

        return $this->showInputView();
    }

    protected function show($id)
    {
        parent::show($id);

        return $this->showInputView();
    }

    protected function checkEmptyApplication($application)
    {
        if (empty($application->entertainment)) {
            abort(404);
        }
    }

    public function makeValidate($request, &$inputs)
    {
        $rules = [];

        // attached file
        if ($request->file('input_file')) {
            $rules['input_file'] = config('const.rules.attached_file');
        }

        if (isset($inputs['apply'])) {

            $rules['entertainment_dt']          = 'required';
            $rules['place']                     = 'required';
            $rules['during_trip']               = 'required_select';
            $rules['budget_position']           = 'required_select';

            if ($inputs['budget_position'] == config('const.budget.position.po')){
                $rules['check_row']                 = 'required_select';
                $rules['has_entertainment_times']   = 'required_select';
                $rules['existence_projects']        = 'required_select';
                $rules['includes_family']           = 'required_select';
                $rules['project_name']              = 'required';
            }

            $rules['entertainment_reason']      = 'required_select';
            $rules['entertainment_person']      = 'required|numeric';
            $rules['est_amount']                = 'required|numeric';
            $rules['infos.*.cp_name']           = 'required';
            $rules['infos.*.title']             = 'required';
            $rules['infos.*.name_attendants']   = 'required';
            $rules['infos.*.details_dutles']    = 'required';

            if ($inputs['has_entertainment_times'] == true) {
                $rules['entertainment_times'] = 'required|numeric';
            }
            
            if (!empty($inputs['entertainment_reason']) && $inputs['entertainment_reason'] != 'empty') {
                $rules['entertainment_reason_other'] = 'required';
            }

            if (!empty($inputs['est_amount']) && floatval($inputs['est_amount']) > 4000000){
                $rules['reason_budget_over'] = 'required';
            }

            if ($inputs['subsequent']){
                $rules['subsequent_reason'] = 'required';
            }
        }
        $customAttributes = [
            'entertainment_dt'              => __('label.entertainment_entertainment_dt'),
            'place'                         => __('label.entertainment_place'),
            'during_trip'                   => __('label.entertainment_during_trip'),
            'budget_position'               => __('label.entertainment_budget_position'),
            'check_row'                     => __('label.entertainment_check_row'),
            'has_entertainment_times'       => __('label.entertainment_entertainment_times'),
            'entertainment_reason'          => __('label.entertainment_entertainment_reason'),
            'entertainment_reason_other'    => __('label.entertainment_entertainment_reason_other'),
            'entertainment_times'           => __('label.entertainment_entertainment_times'),
            'existence_projects'            => __('label.entertainment_existence_projects'),
            'includes_family'               => __('label.entertainment_includes_family'),
            'project_name'                  => __('label.entertainment_project_name'),
            'reason_budget_over'            => __('label.entertainment_describe'),
            'entertainment_person'          => __('label.entertainment_entertainment_person'),
            'est_amount'                    => __('label.entertainment_est_amount'),
            'subsequent_reason'             => __('label.application_subsequent_reason'),
            'infos.*.cp_name'               => __('label.entertainment_cp_name'),
            'infos.*.title'                 => __('label.entertainment_title'),
            'infos.*.name_attendants'       => __('label.entertainment_name_attendants'),
            'infos.*.details_dutles'        => __('label.entertainment_details_dutles'),
        ];

        return Validator::make($inputs, $rules, [], $customAttributes);
    }

    public function saveApplicationDetail($request, &$inputs, $application, $applicationId, $loginUser)
    {
        /////////////////////////////////////////////
        // Entertainments table
        /////////////////////////////////////////////

        $etData = [
            'entertainment_dt'              => $inputs['entertainment_dt'],
            'place'                         => $inputs['place'],
            'during_trip'                   => $inputs['during_trip'],
            'check_row'                     => $inputs['check_row'],
            'has_entertainment_times'       => $inputs['has_entertainment_times'],
            'entertainment_times'           => $inputs['has_entertainment_times'] == config('const.check.off') ? null : $inputs['entertainment_times'],
            'existence_projects'            => $inputs['existence_projects'],
            'includes_family'               => $inputs['includes_family'],
            'project_name'                  => $inputs['project_name'],
            'entertainment_reason'          => $inputs['entertainment_reason'] != 'empty' ? $inputs['entertainment_reason'] : null,
            'entertainment_reason_other'    => $inputs['entertainment_reason'] != 'empty' ? $inputs['entertainment_reason_other'] : null,
            'entertainment_person'          => $inputs['entertainment_person'],
            'est_amount'                    => $inputs['est_amount'],
            'reason_budget_over'            => $inputs['reason_budget_over'],
            'updated_by'                    => $loginUser->id,
            'updated_at'                    => Carbon::now(),
        ];

        if (empty($application)) {
            $etData['application_id'] = $applicationId;
            $etData['created_by'] = $loginUser->id;
            $etData['created_at'] = Carbon::now();

            $etId = DB::table('entertaiments')->insertGetId($etData);
        } else {
            DB::table('entertaiments')->where('id', $application->entertainment->id)->update($etData);
            DB::table('entertaiment_infos')->where('entertaiment_id', $application->entertainment->id)->delete();
        }

        /////////////////////////////////////////////
        // Entertaiment_infos table
        /////////////////////////////////////////////

        if (!isset($etId)) {
            $etId = $application->entertainment->id;
        }
        $entertaimentInfos = [];
        foreach ($inputs['infos'] as $value) {
            $item['entertaiment_id']    = $etId;
            $item['cp_name']            = $value['cp_name'];
            $item['title']              = $value['title'];
            $item['name_attendants']    = $value['name_attendants'];
            $item['details_dutles']     = $value['details_dutles'];
            $item['created_at']         = Carbon::now();
            $item['updated_at']         = Carbon::now();

            $entertaimentInfos[] = $item;
        }

        DB::table('entertaiment_infos')->insert($entertaimentInfos);
    }

    protected function preview(Request $request, $id)
    {
        parent::preview($request, $id);

        $companies = [];

        $this->currentCompatData = array_merge($this->currentCompatData, compact('companies'));

        return view($this->viewInputName, $this->currentCompatData);
    }

    protected function getInputs($request)
    {
        $inputs = $request->all();

        if (!isset($inputs['during_trip'])) {
            $inputs['during_trip'] = null;
        }
        if (!isset($inputs['budget_postion'])) {
            $inputs['budget_postion'] = null;
        }
        if (!isset($inputs['check_row'])) {
            $inputs['check_row'] = null;
        }
        if (!isset($inputs['has_entertainment_times'])) {
            $inputs['has_entertainment_times'] = null;
        }
        if (!isset($inputs['existence_projects'])) {
            $inputs['existence_projects'] = null;
        }
        if (!isset($inputs['includes_family'])) {
            $inputs['includes_family'] = null;
        }
        if (!isset($inputs['subsequent'])) {
            $inputs['subsequent'] = null;
        }

        return $inputs;
    }

    public function getBudgetTypeCompare($inputs, $budgetType, $currentStep, $budgetPosition)
    {
        // get budget comparation type
        $budget = Budget::where([
            'budget_type' => $budgetType,
            'step_type' => $currentStep,
            'position' => $budgetPosition,
        ])->first();
        // not found available flow setting
        if (empty($budget) && isset($inputs['apply'])) {
            throw new NotFoundFlowSettingException();
        }

        // get budget comparation type
        $budgetTypeCompare = null;
        if (isset($inputs['est_amount']) && !empty($budget)) {
            if ($inputs['est_amount'] <= $budget->amount) {
                $budgetTypeCompare = config('const.budget.budeget_type_compare.less_equal');
            } else {
                $budgetTypeCompare = config('const.budget.budeget_type_compare.greater_than');
            }
        }

        return $budgetTypeCompare;
    }

    private function getListCompanyName()
    {
        $companies = Company::all('name');
        $companies = Arr::pluck($companies->toArray(), 'name');

        return $companies;
    }

    private function showInputView()
    {
        // get companies
        $companies = $this->getListCompanyName();

        $this->currentCompatData = array_merge($this->currentCompatData, compact('companies'));

        return view($this->viewInputName, $this->currentCompatData);
    }
}
