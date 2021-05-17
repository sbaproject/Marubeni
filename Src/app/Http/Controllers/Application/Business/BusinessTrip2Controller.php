<?php

namespace App\Http\Controllers\Application\Business;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Libs\Common;
use App\Models\TripFee;
use App\Models\Department;
use App\Models\Application;
use App\Models\Businesstrip;
use Illuminate\Http\Request;
use App\Models\Businesstrip2;
use App\Models\Transportation;
use App\Models\HistoryApproval;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

/**
 * BusinessTrip Settlement (Step 2)
 */
class BusinessTrip2Controller extends Controller
{
    public function index()
    {
    }

    public function show(Request $request, $applicationId)
    {
        $departments = Department::where('role', 1)->get();

        $application = Application::findOrFail($applicationId);

        // must be business application
        if ($application->form_id != config('const.form.biz_trip')) {
            abort(404);
        }

        // check valid permission accessing to application
        if (Auth::user()->id !== $application->created_by) {
            if (Gate::denies('admin-gate')) {
                abort(403);
            } else {
                $showWithAdminFlg = true;
            }
        }
        // not allows editing
        $previewFlg = !$this->checkEditableApplication($application) || isset($showWithAdminFlg) || Common::detectMobile();

        $modFlg = !empty($application->business2);

        return view('application_business_settlement_input', compact('departments', 'application', 'previewFlg', 'modFlg'));
    }

    private function checkEditableApplication($application)
    {
        return ($application->status == config('const.application.status.draft')
            ||  ($application->status == config('const.application.status.applying'))
            ||  $application->status == config('const.application.status.declined'));
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
                session()->flash('pdf_url', route('user.business2.pdf', $application->id));
                session()->put('inputs', $inputs);
                return redirect()->route('user.business2.show', $application->id);
                // return $this->openPdf($request, $application, $inputs);
            }
            // only owner able to edit (edit mode)
            if (!empty($application) && Auth::user()->id !== $application->created_by) {
                abort(403);
            }
        } else {
            abort(404);
        }

        // make correct numeral inputs
        $this->makeCorrectNumeralFromInput($inputs);

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
            $biz2->chargedbys = $inputs['chargedbys'];
            $biz2->updated_by = $loginUser->id;
            // fill data
            $biz2->fill($inputs);
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

            //=======================================
            // Insert TripFees - OtherFees
            //=======================================
            $otherfees = [];
            if (isset($inputs['otherfees'])) {
                foreach ($inputs['otherfees'] as $index => $item) {
                    $item['businesstrip_id']    = $biz2->id;
                    $item['type_trip']          = config('const.trip_fee_type.otherfees');
                    $item['trip_no']            = $item['type_trip'] . ($index + 1);
                    $item['created_by']         = $loginUser->id;
                    $item['updated_by']         = $loginUser->id;
                    $item['created_at']         = Carbon::now();
                    $item['updated_at']         = Carbon::now();

                    $otherfees[] = $item;
                }
            }
            TripFee::insert($otherfees);

            // commit DB
            DB::commit();

            // success alert
            Common::setAlertSuccess();

            // save success then will open pdf in new tab
            session()->flash('pdf_url', route('user.business2.pdf', $application->id));

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
                'destinations'              => __('label.business_trip_destination'),
                'number_of_days'            => __('label.business_number_of_days'),

                'daily1_amount'             => __('label.amount_per_day'),
                'daily1_days'               => __('label.days'),

                'daily2_amount'             => __('label.amount_per_day'),
                'daily2_rate'               => __('label.rate'),
                'daily2_days'               => __('label.days'),

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

                'otherfees.*.method'    => __('label.type'),
                'otherfees.*.unit'      => __('label.unit'),
                'otherfees.*.amount'    => __('label.amount'),

                'chargedbys.*.department'   => __('label.business_department'),
                'chargedbys.*.percent'      => __('label.percent'),
                'total_percent'             => __('label.total_percentage'),
            ];

            // validate rules
            $rules = [];

            // attached file
            // if ($request->file('input_file')) {
            //     $rules['input_file'] = config('const.rules.attached_file');
            // }

            $rules['destinations']      = 'required';
            $rules['number_of_days']    = 'required|numeric';

            $rules['daily1_amount']     = 'nullable|numeric';
            $rules['daily1_days']       = 'nullable|numeric';

            $rules['daily2_amount']     = 'nullable|numeric';
            $rules['daily2_rate']       = 'nullable|numeric';
            $rules['daily2_days']       = 'nullable|numeric';

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

            // otherfees
            $rules['otherfees.*.method']        = 'required_select';
            $rules['otherfees.*.unit']          = 'required_select';
            $rules['otherfees.*.amount']        = 'required|numeric';
            if (isset($inputs['otherfees'])) {
                foreach ($inputs['otherfees'] as $key => $item) {
                    // exchange rate must be require if currency is not VND
                    if (
                        !empty($item['unit']) && $item['unit'] != 'VND'
                    ) {
                        $rules["otherfees.{$key}.exchange_rate"] = 'required|numeric';
                        $customAttributes["otherfees.{$key}.exchange_rate"] = __('label.rate');
                    }
                }
            }

            // charged bys
            $rules['chargedbys.*.department']   = 'required_select';
            $rules['chargedbys.*.percent']      = 'required|numeric';
            if (isset($inputs['chargedbys'])) {
                $totalPercent = 0;
                $checkTotal = true;
                foreach ($inputs['chargedbys'] as $item) {
                    if (empty($item['percent'])) {
                        $checkTotal = false;
                        continue;
                    }
                    $totalPercent += $item['percent'];
                }
                if ($checkTotal) {
                    $inputs['total_percent'] = $totalPercent;
                    $rules['total_percent'] = 'equal:100,%';
                }
            }

            $validator = Validator::make($inputs, $rules, [], $customAttributes);
            if ($validator->fails()) {
                unset($inputs['input_file']);
                return $validator;
            }
        }
    }

    private function makeCorrectNumeralFromInput(&$inputs)
    {
        // tripfees - transportations
        if (!empty($inputs['transportations'])) {
            $newArr = [];
            foreach ($inputs['transportations'] as $item) {
                // amount
                if (!empty($item['amount'])) {
                    $item['amount'] = Common::getRawNumeric($item['amount']);
                }

                // rate
                if (!empty($item['exchange_rate'])) {
                    $item['exchange_rate'] = Common::getRawNumeric($item['exchange_rate']);
                }
                $newArr[] = $item;
            }
            $inputs['transportations'] = $newArr;
        }
        // tripfees - accomodations
        if (!empty($inputs['accomodations'])) {
            $newArr = [];
            foreach ($inputs['accomodations'] as $item) {
                // amount
                if (!empty($item['amount'])) {
                    $item['amount'] = Common::getRawNumeric($item['amount']);
                }
                // rate
                if (!empty($item['exchange_rate'])) {
                    $item['exchange_rate'] = Common::getRawNumeric($item['exchange_rate']);
                }
                $newArr[] = $item;
            }
            $inputs['accomodations'] = $newArr;
        }
        // tripfees - communications
        if (!empty($inputs['communications'])) {
            $newArr = [];
            foreach ($inputs['communications'] as $item) {
                // amount
                if (!empty($item['amount'])) {
                    $item['amount'] = Common::getRawNumeric($item['amount']);
                }
                // rate
                if (!empty($item['exchange_rate'])) {
                    $item['exchange_rate'] = Common::getRawNumeric($item['exchange_rate']);
                }
                $newArr[] = $item;
            }
            $inputs['communications'] = $newArr;
        }
        // tripfees - otherfees
        if (!empty($inputs['otherfees'])) {
            $newArr = [];
            foreach ($inputs['otherfees'] as $item) {
                // amount
                if (!empty($item['amount'])) {
                    $item['amount'] = Common::getRawNumeric($item['amount']);
                }
                // rate
                if (!empty($item['exchange_rate'])) {
                    $item['exchange_rate'] = Common::getRawNumeric($item['exchange_rate']);
                }
                $newArr[] = $item;
            }
            $inputs['otherfees'] = $newArr;
        }

        // daily 1
        // amount
        if (!empty($inputs['daily1_amount'])) {
            $inputs['daily1_amount'] = Common::getRawNumeric($inputs['daily1_amount']);
        }

        // daily 2
        // amount
        if (!empty($inputs['daily2_amount'])) {
            $inputs['daily2_amount'] = Common::getRawNumeric($inputs['daily2_amount']);
        }
        // rate
        if (!empty($inputs['daily2_rate'])) {
            $inputs['daily2_rate'] = Common::getRawNumeric($inputs['daily2_rate']);
        }
    }

    public function pdf(Request $request, $applicationId)
    {
        // use ajax to prepair inputs on current form, if it ok then will open new tab (avoid popup-blocker case)
        if ($request->ajax()) {
            session()->forget('pdf_inputs');
            session()->put('pdf_inputs', $request->input());
            return response()->json('ok');
        }

        $application = Application::findOrFail($applicationId);

        // get directly data from inputs on form screen
        if ($request->has('m')) {
            if (!session()->has('pdf_inputs')) {
                abort(404, 'Your PDF file has expired !');
            }
            $inputs = session()->get('pdf_inputs');
            session()->forget('pdf_inputs');
        }
        // get data from db
        else {
            $inputs = $application->business2->toArray();
            $inputs['itineraries'] = $application->business2->itineraries;
        }

        return $this->openPdf($request, $application, $inputs);
    }

    private function openPdf(Request $request, $application, $inputs)
    {
        if (!empty($application)) {
            $loginUser = Auth::user();

            // get applicant info
            $inputs['applicant'] = $application->applicant;

            // get last approval of complete application
            if ($application->status == config('const.application.status.completed')) {
                $conditions = [
                    'application_id' => $application->id,
                    'step' => $application->current_step,
                    'status' => config('const.application.status.completed'),
                ];
                $lastApproval = HistoryApproval::getHistory($conditions)->first();
                if (!empty($lastApproval)) {
                    $inputs['lastApproval'] = $lastApproval;
                }
            }
        } else {
            $inputs['applicant'] = Auth::user();
        }

        $this->makeCorrectNumeralFromInput($inputs);

        // PDF::setOptions(['defaultFont' => 'Roboto-Black']);
        // PDF::setOptions(['enable-javascript' => true]);
        $pdf = PDF::loadView("application_business2_pdf", compact('application', 'inputs'));

        // preview pdf
        $fileName = "business_settlement.pdf";
        return $pdf->stream($fileName);
        // download
        // return $pdf->download($fileName);
    }
}
