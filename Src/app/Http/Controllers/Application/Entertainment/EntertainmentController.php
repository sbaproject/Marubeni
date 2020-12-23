<?php

namespace App\Http\Controllers\Application\Entertainment;

use Exception;
use Carbon\Carbon;
use App\Models\Budget;
use App\Models\Company;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

    protected function doValidate($request, &$inputs)
    {
        if (isset($inputs['apply']) || isset($inputs['draft'])) {
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
                $rules['check_row']                 = 'required_select';
                $rules['has_entertainment_times']   = 'required_select';
                $rules['existence_projects']        = 'required_select';
                $rules['includes_family']           = 'required_select';
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
                // entertainment reason is [Other = 10] option
                if ($inputs['entertainment_reason'] == config('const.entertainment.reason.other')) {
                    $rules['entertainment_reason_other'] = 'required';
                }
            }
            $customAttributes = [
                'entertainment_dt'              => __('label.entertainment.entertainment_dt'),
                'place'                         => __('label.entertainment.place'),
                'during_trip'                   => __('label.entertainment.during_trip'),
                'budget_position'               => __('label.entertainment.budget_position'),
                'check_row'                     => __('label.entertainment.check_row'),
                'has_entertainment_times'       => __('label.entertainment.entertainment_times'),
                'entertainment_reason'          => __('label.entertainment.entertainment_reason'),
                'entertainment_reason_other'    => __('label.entertainment.entertainment_reason_other'),
                'entertainment_times'           => __('label.entertainment.entertainment_times'),
                'existence_projects'            => __('label.entertainment.existence_projects'),
                'includes_family'               => __('label.entertainment.includes_family'),
                'entertainment_person'          => __('label.entertainment.entertainment_person'),
                'est_amount'                    => __('label.entertainment.est_amount'),
                'infos.*.cp_name'               => __('label.entertainment.cp_name'),
                'infos.*.title'                 => __('label.entertainment.title'),
                'infos.*.name_attendants'       => __('label.entertainment.name_attendants'),
                'infos.*.details_dutles'        => __('label.entertainment.details_dutles'),
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
            // get logged user
            $user = Auth::user();

            /////////////////////////////////////////////
            // Applications table
            /////////////////////////////////////////////
            $applicationId = $this->saveApplicationMaster($request, $inputs, $app, $user);

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
                'entertainment_reason_other'    => $inputs['entertainment_reason'] == config('const.entertainment.reason.other') ? $inputs['entertainment_reason_other'] : null,
                'entertainment_person'          => $inputs['entertainment_person'],
                'est_amount'                    => $inputs['est_amount'],
                'reason_budget_over'            => $inputs['reason_budget_over'],
                'file_path'                     => isset($filePath) ? $filePath : null,
                'updated_by'                    => $user->id,
                'updated_at'                    => Carbon::now(),
            ];

            if (empty($app)) {
                $etData['application_id'] = $applicationId;
                $etData['created_by'] = $user->id;
                $etData['created_at'] = Carbon::now();

                $etId = DB::table('entertaiments')->insertGetId($etData);
            } else {
                DB::table('entertaiments')->where('id', $app->entertainment->id)->update($etData);
                DB::table('entertaiment_infos')->where('entertaiment_id', $app->entertainment->id)->delete();
            }

            /////////////////////////////////////////////
            // Entertaiment_infos table
            /////////////////////////////////////////////
            if (!isset($etId)) {
                $etId = $app->entertainment->id;
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

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            unset($inputs['input_file']);
            if ($ex instanceof NotFoundFlowSettingException) {
                $msgErr = $ex->getMessage();
            } else {
                $msgErr = $ex->getMessage();//__('msg.save_fail');
            }
        }

        return $msgErr;
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
