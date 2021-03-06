<?php

namespace App\Http\Controllers\Application\Business;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Application\ApplicationController;

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

    public function makeValidate($request, &$inputs)
    {
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
            //$rules['borne_by']          = 'required';
            $rules['trip_dt_from']      = 'required';
            $rules['trip_dt_to']        = 'required';
            $rules['trans.*.departure'] = 'required';
            $rules['trans.*.arrive']    = 'required';
            $rules['trans.*.method']    = 'required';


            $today = strtotime(date("Y-m-d"));
            $form_date = strtotime($inputs['trip_dt_from']);
            if ($form_date < $today) {
                $rules['cb_subsequent'] = 'required';
            }

            if ($inputs['subsequent']) {
                $rules['subsequent_reason'] = 'required';
            }
        }
        $customAttributes = [
            'budget_position'   => __('label.business_budget_position'),
            'destinations'      => __('label.business_trip_destination'),
            'accommodation'     => __('label.business_accommodation'),
            'accompany'         => __('label.business_accompany'),
            //'borne_by'          => __('label.business_borne_by'),
            'subsequent_reason' => __('label.application_subsequent_reason'),
            'trans.*.departure' => __('label.business_departure'),
            'trans.*.arrive'    => __('label.business_arrival'),
            'trans.*.method'    => __('label.business_method'),
        ];

        return Validator::make($inputs, $rules, [], $customAttributes);
    }

    public function saveApplicationDetail($request, &$inputs, $application, $applicationId, $loginUser)
    {
        /////////////////////////////////////////////
        // Businesstrips table
        /////////////////////////////////////////////

        $bizData = [
            'destinations'  => $inputs['destinations'],
            'trip_dt_from'  => $inputs['trip_dt_from'],
            'trip_dt_to'    => $inputs['trip_dt_to'],
            'accommodation' => $inputs['accommodation'],
            'accompany'     => $inputs['accompany'],
            //'borne_by'      => $inputs['borne_by'],
            'comment'       => $inputs['comment'],
            'updated_by'    => $loginUser->id,
            'updated_at'    => Carbon::now(),
        ];

        if (empty($application)) {
            $bizData['application_id']  = $applicationId;
            $bizData['created_by']      = $loginUser->id;
            $bizData['created_at']      = Carbon::now();

            $bizId = DB::table('businesstrips')->insertGetId($bizData);
        } else {
            DB::table('businesstrips')->where('id', $application->business->id)->update($bizData);
            DB::table('transportations')->where('businesstrip_id', $application->business->id)->delete();
        }

        /////////////////////////////////////////////
        // Transportations table
        /////////////////////////////////////////////

        if (!isset($bizId)) {
            $bizId = $application->business->id;
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
