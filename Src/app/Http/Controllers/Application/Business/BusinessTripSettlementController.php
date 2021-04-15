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
use App\Models\BusinesstripSettlement;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BusinessTripSettlementController extends Controller
{
    public function index()
    {
    }

    public function create(Request $request, $applicationId)
    {
        $departments = Department::all();

        $application = Application::find($applicationId);

        if (empty($application)) {
            abort(404);
        }

        $previewFlg = false;

        // dd($itineraries, $application->business->transportations);

        return view('application_business_settlement_input', compact('departments', 'application', 'previewFlg'));
    }

    public function store(Request $request, $applicationId)
    {
        // dd($applicationId, $request->input());

        $application = Application::findOrFail($applicationId);

        $inputs = $request->input();

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
        if (isset($inputs['apply']) || isset($inputs['draft'])) {

            $rules = [];

            // attached file
            if ($request->file('input_file')) {
                $rules['input_file'] = config('const.rules.attached_file');
            }

            if (isset($inputs['apply'])) {
                $rules['charged_to']            = 'required_select';
                $rules['destinations']          = 'required';
                $rules['number_of_days']        = 'required|numeric';

                // total daily allowance
                $rules['total_daily_allowance'] = 'required|numeric';
                $rules['total_daily_unit']      = 'required';
                if (!empty($inputs['total_daily_allowance'])) {
                    if (!empty($inputs['total_daily_unit']) && $inputs['total_daily_unit'] != 'VND') {
                        $rules['total_daily_rate'] = 'required|numeric';
                    }
                }

                // daily allowance
                $rules['daily_allowance']       = 'required|numeric';
                $rules['daily_unit']            = 'required';
                if (!empty($inputs['daily_allowance'])) {
                    if (!empty($inputs['daily_unit']) && $inputs['daily_unit'] != 'VND') {
                        $rules['daily_rate'] = 'required|numeric';
                    }
                }

                // other fees
                if (!empty($inputs['other_fees'])) {
                    if (!empty($inputs['other_fees_unit']) && $inputs['other_fees_unit'] != 'VND') {
                        $rules['other_fees_rate'] = 'required|numeric';
                    }
                    $rules['other_fees_unit'] = 'required_select';
                }

                // itineraries
                $rules['itineraries.*.departure']   = 'required';
                $rules['itineraries.*.arrive']      = 'required';
                $rules['itineraries.*.trans_date']  = 'required';

                // transportations
                $rules['transportations.*.method']  = 'required_select';
                $rules['transportations.*.unit']    = 'required_select';
                $rules['transportations.*.amount']  = 'required|numeric';
                $rules['transportations.*.exchange_rate'] = 'nullable|numeric';

                // communications
                $rules['communications.*.method']   = 'required_select';
                $rules['communications.*.unit']     = 'required_select';
                $rules['communications.*.amount']   = 'required|numeric';
                $rules['communications.*.exchange_rate'] = 'nullable|numeric';

                // accomodations
                $rules['accomodations.*.method']    = 'required_select';
                $rules['accomodations.*.unit']      = 'required_select';
                $rules['accomodations.*.amount']    = 'required|numeric';
                $rules['accomodations.*.exchange_rate'] = 'nullable|numeric';
            }

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

                'itineraries.*.departure'   => __('label.business_departure'),
                'itineraries.*.arrive'      => __('label.business_arrival'),
                'itineraries.*.trans_date'  => __('label.date'),

                'transportations.*.method'  => __('label.type'),
                'transportations.*.unit'    => __('label.unit'),
                'transportations.*.amount'  => __('label.amount'),
                'transportations.*.exchange_rate'  => __('label.rate'),

                'communications.*.method'   => __('label.type'),
                'communications.*.unit'     => __('label.unit'),
                'communications.*.amount'   => __('label.amount'),
                'communications.*.exchange_rate'  => __('label.rate'),

                'accomodations.*.method'    => __('label.type'),
                'accomodations.*.unit'      => __('label.unit'),
                'accomodations.*.amount'    => __('label.amount'),
                'accomodations.*.exchange_rate'  => __('label.rate'),
            ];

            $validator = Validator::make($inputs, $rules, [], $customAttributes);

            if ($validator->fails()) {
                unset($inputs['input_file']);
                // dd($validator);
                return redirect()->back()->with('inputs', $inputs)->withErrors($validator);
            }
        }

        // dd($applicationId, $request->input());

        DB::beginTransaction();

        try {
            // get logged user
            $loginUser = Auth::user();

            //=======================================
            // Insert business2
            //=======================================
            $biz2 = new BusinesstripSettlement();
            $biz2->application_id = $applicationId;
            $biz2->created_by = $loginUser->id;
            $biz2->updated_by = $loginUser->id;
            // fill data
            $biz2->fill($request->input());
            // save
            $biz2->save();
            // get last insert id
            $newBiz2Id = $biz2->id;

            //=======================================
            // Insert Itineraries
            //=======================================
            $itineraries = [];
            if (isset($inputs['itineraries'])) {
                foreach ($inputs['itineraries'] as $item) {
                    $item['businesstrip2_id'] = $newBiz2Id;
                    $item['created_at'] = Carbon::now();
                    $item['updated_at'] = Carbon::now();

                    $itineraries[] = $item;
                }
            }
            Transportation::insert($itineraries);

            // dd($biz2, $request->input());

            // commit DB
            DB::commit();
        } catch (Exception $ex) {

            DB::rollBack();
            unset($inputs['input_file']);

            // log stacktrace
            report($ex);

            $msgErr = __('msg.save_fail');

            return Common::redirectBackWithAlertFail($msgErr)->with('inputs', $inputs);
        }
    }

    public function show(Request $request, $applicationId)
    {
        $departments = Department::all();

        return view('application_business_settlement_input', compact('departments'));
    }
}
