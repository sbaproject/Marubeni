<?php

namespace App\Http\Controllers\Application\Business;

use Exception;
use Carbon\Carbon;
use App\Libs\Common;
use App\Models\Department;
use App\Models\Application;
use App\Models\Businesstrip;
use Illuminate\Http\Request;
use App\Models\Transportation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Businesstrip2;
use App\Models\TripFee;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Businesstrip2Controller extends Controller
{
    public function index()
    {
    }

    public function show(Request $request, $applicationId)
    {
        $departments = Department::all();

        $application = Application::findOrFail($applicationId);

        // must be business application
        if ($application->form_id != config('const.form.biz_trip')) {
            abort(404);
        }

        $previewFlg = false;

        $modFlg = !empty($application->business2);

        return view('application_business_settlement_input', compact('departments', 'application', 'previewFlg', 'modFlg'));
    }

    public function update(Request $request, $applicationId)
    {
        // get master application
        $application = Application::findOrFail($applicationId);

        // get request inputs
        $inputs = $request->input();

        // check post action
        if (isset($inputs['apply']) || isset($inputs['draft']) || isset($inputs['pdf'])) {
            // export pdf
            if (isset($inputs['pdf'])) {
                return $this->pdf($request, $inputs, $application);
            }
            // only owner able to edit (edit mode)
            if (!empty($application) && Auth::user()->id !== $application->created_by) {
                abort(403);
            }
        } else {
            abort(404);
        }

        // validate
        $validator = $this->doValidate($inputs);
        if (!empty($validator)) {
            Common::setAlertFail(__('msg.validation_fail'));
            return redirect()->back()->with('inputs', $inputs)->withErrors($validator);
        }

        // begin save data
        DB::beginTransaction();

        try {
            // get logged user
            $loginUser = Auth::user();

            //=======================================
            // Insert business2
            //=======================================
            $biz2 = $application->business2;
            // new biz2
            if (empty($biz2)) {
                $biz2 = new Businesstrip2();
                $biz2->application_id = $applicationId;
                $biz2->created_by = $loginUser->id;
            }
            $biz2->updated_by = $loginUser->id;
            // fill data
            $biz2->fill($request->input());
            // save
            $biz2->save();

            //=======================================
            // Insert Itineraries
            //=======================================
            // remove old itineraries
            Transportation::where('businesstrip2_id', $biz2->id)->delete();
            // add new itineraries
            $itineraries = [];
            if (isset($inputs['itineraries'])) {
                foreach ($inputs['itineraries'] as $item) {
                    $item['businesstrip2_id']   = $biz2->id;
                    $item['created_at']         = Carbon::now();
                    $item['updated_at']         = Carbon::now();

                    $itineraries[] = $item;
                }
            }
            Transportation::insert($itineraries);

            //=======================================
            // Insert TripFees - Transportations
            //=======================================
            // remove old TripFees
            TripFee::where('businesstrip_id', $biz2->id)->delete();

            // add new TripFees - Transportations
            $transportations = [];
            if (isset($inputs['transportations'])) {
                foreach ($inputs['transportations'] as $index => $item) {
                    $item['businesstrip_id']    = $biz2->id;
                    $item['type_trip']          = config('const.trip_fee_type.transportation');
                    $item['trip_no']            = $item['type_trip'] . ($index + 1);
                    $item['created_by']         = $loginUser->id;
                    $item['updated_by']         = $loginUser->id;
                    $item['created_at']         = Carbon::now();
                    $item['updated_at']         = Carbon::now();

                    $transportations[] = $item;
                }
            }
            TripFee::insert($transportations);

            //=======================================
            // Insert TripFees - Accommodation
            //=======================================
            $accomodations = [];
            if (isset($inputs['accomodations'])) {
                foreach ($inputs['accomodations'] as $index => $item) {
                    $item['businesstrip_id']    = $biz2->id;
                    $item['type_trip']          = config('const.trip_fee_type.accomodation');
                    $item['trip_no']            = $item['type_trip'] . ($index + 1);
                    $item['created_by']         = $loginUser->id;
                    $item['updated_by']         = $loginUser->id;
                    $item['created_at']         = Carbon::now();
                    $item['updated_at']         = Carbon::now();

                    $accomodations[] = $item;
                }
            }
            TripFee::insert($accomodations);

            //=======================================
            // Insert TripFees - Communication
            //=======================================
            $communications = [];
            if (isset($inputs['communications'])) {
                foreach ($inputs['communications'] as $index => $item) {
                    $item['businesstrip_id']    = $biz2->id;
                    $item['type_trip']          = config('const.trip_fee_type.communication');
                    $item['trip_no']            = $item['type_trip'] . ($index + 1);
                    $item['created_by']         = $loginUser->id;
                    $item['updated_by']         = $loginUser->id;
                    $item['created_at']         = Carbon::now();
                    $item['updated_at']         = Carbon::now();

                    $communications[] = $item;
                }
            }
            TripFee::insert($communications);

            // commit DB
            DB::commit();

            Common::setAlertSuccess();
            return redirect()->route('user.business2.show', $application->id);
        } catch (Exception $ex) {

            DB::rollBack();
            unset($inputs['input_file']);

            // log stacktrace
            report($ex);

            $msgErr = __('msg.save_fail');

            return Common::redirectBackWithAlertFail($msgErr)->with('inputs', $inputs);
        }
    }

    private function doValidate($inputs)
    {
        if (isset($inputs['apply'])) {

            // validate messages
            $customAttributes = [
                'charged_to'                => __('label.business_charged_to'),
                'destinations'              => __('label.business_trip_destination'),
                'number_of_days'            => __('label.business_number_of_days'),

                'total_daily_allowance'     => __('label.amount'),
                'total_daily_unit'          => __('label.unit'),
                'total_daily_rate'          => __('label.rate'),

                'daily_allowance'           => __('label.amount'),
                'daily_unit'                => __('label.unit'),
                'daily_rate'                => __('label.rate'),

                'other_fees'                => __('label.amount'),
                'other_fees_unit'           => __('label.unit'),
                'other_fees_rate'           => __('label.rate'),

                'itineraries.*.departure'   => __('label.business_departure'),
                'itineraries.*.arrive'      => __('label.business_arrival'),
                'itineraries.*.trans_date'  => __('label.date'),

                'transportations.*.method'  => __('label.type'),
                'transportations.*.unit'    => __('label.unit'),
                'transportations.*.amount'  => __('label.amount'),

                'communications.*.method'   => __('label.type'),
                'communications.*.unit'     => __('label.unit'),
                'communications.*.amount'   => __('label.amount'),

                'accomodations.*.method'    => __('label.type'),
                'accomodations.*.unit'      => __('label.unit'),
                'accomodations.*.amount'    => __('label.amount'),
            ];

            // validate rules
            $rules = [];

            // attached file
            // if ($request->file('input_file')) {
            //     $rules['input_file'] = config('const.rules.attached_file');
            // }

            $rules['charged_to']            = 'required_select';
            $rules['destinations']          = 'required';
            $rules['number_of_days']        = 'required|numeric';

            // total daily allowance
            if (
                !empty($inputs['total_daily_allowance'])
                || !empty($inputs['total_daily_unit'])
                || !empty($inputs['total_daily_rate'])
            ) {

                $rules['total_daily_allowance'] = 'required|numeric';
                $rules['total_daily_unit']      = 'required_select';
                $rules['total_daily_rate']      = 'nullable|numeric';

                if (!empty($inputs['total_daily_unit']) && $inputs['total_daily_unit'] != 'VND') {
                    $rules['total_daily_rate'] = 'required|numeric';
                }
            }

            // daily allowance
            if (
                !empty($inputs['daily_allowance'])
                || !empty($inputs['daily_unit'])
                || !empty($inputs['daily_rate'])
            ) {

                $rules['daily_allowance']   = 'required|numeric';
                $rules['daily_unit']        = 'required_select';
                $rules['daily_rate']        = 'nullable|numeric';

                if (!empty($inputs['daily_unit']) && $inputs['daily_unit'] != 'VND') {
                    $rules['daily_rate'] = 'required|numeric';
                }
            }

            // other fees
            if (
                !empty($inputs['other_fees'])
                || !empty($inputs['other_fees_unit'])
                || !empty($inputs['other_fees_rate'])
            ) {

                $rules['other_fees']        = 'required|numeric';
                $rules['other_fees_unit']   = 'required_select';
                $rules['other_fees_rate']   = 'nullable|numeric';

                if (!empty($inputs['other_fees_unit']) && $inputs['other_fees_unit'] != 'VND') {
                    $rules['other_fees_rate'] = 'required|numeric';
                }
            }

            // itineraries
            $rules['itineraries.*.departure']   = 'required';
            $rules['itineraries.*.arrive']      = 'required';
            $rules['itineraries.*.trans_date']  = 'required';

            // transportations
            $rules['transportations.*.method']  = 'required_select';
            $rules['transportations.*.unit']    = 'required_select';
            $rules['transportations.*.amount']  = 'required|numeric';
            if (isset($inputs['transportations'])) {
                foreach ($inputs['transportations'] as $key => $item) {
                    // exchange rate must be require if currency is not VND
                    if (!empty($item['unit']) && $item['unit'] != 'VND') {
                        $rules["transportations.{$key}.exchange_rate"] = 'required|numeric';
                        $customAttributes["transportations.{$key}.exchange_rate"] = __('label.rate');
                    }
                }
            }

            // communications
            $rules['communications.*.method']        = 'required_select';
            $rules['communications.*.unit']          = 'required_select';
            $rules['communications.*.amount']        = 'required|numeric';
            if (isset($inputs['communications'])) {
                foreach ($inputs['communications'] as $key => $item) {
                    // exchange rate must be require if currency is not VND
                    if (!empty($item['unit']) && $item['unit'] != 'VND') {
                        $rules["communications.{$key}.exchange_rate"] = 'required|numeric';
                        $customAttributes["communications.{$key}.exchange_rate"] = __('label.rate');
                    }
                }
            }

            // accomodations
            $rules['accomodations.*.method']        = 'required_select';
            $rules['accomodations.*.unit']          = 'required_select';
            $rules['accomodations.*.amount']        = 'required|numeric';
            if (isset($inputs['accomodations'])) {
                foreach ($inputs['accomodations'] as $key => $item) {
                    // exchange rate must be require if currency is not VND
                    if (!empty($item['unit']) && $item['unit'] != 'VND') {
                        $rules["accomodations.{$key}.exchange_rate"] = 'required|numeric';
                        $customAttributes["accomodations.{$key}.exchange_rate"] = __('label.rate');
                    }
                }
            }

            $validator = Validator::make($inputs, $rules, [], $customAttributes);
            if ($validator->fails()) {
                unset($inputs['input_file']);
                return $validator;
            }
        }
    }
}
