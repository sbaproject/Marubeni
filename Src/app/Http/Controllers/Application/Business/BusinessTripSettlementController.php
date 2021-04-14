<?php

namespace App\Http\Controllers\Application\Business;

use Exception;
use App\Models\Department;
use App\Models\Application;
use App\Models\Businesstrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        dd($applicationId, $request->input());
        
        $departments = Department::all();

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

                $rules['total_daily_allowance'] = 'required|numeric';
                $rules['total_daily_unit']      = 'required';
                // $rules['total_daily_rate']      = 'required|numeric';

                $rules['daily_allowance']       = 'required';
                $rules['daily_unit']            = 'required';
                // $rules['daily_rate']            = 'required';

                $rules['itineraries.*.departure']   = 'required';
                $rules['itineraries.*.arrive']      = 'required';
                $rules['itineraries.*.trans_date']  = 'required';

                if (!empty($inputs['total_daily_allowance'])) {
                    if(!empty($inputs['total_daily_unit']) && $inputs['total_daily_unit'] != 'VND') {
                        $rules['total_daily_rate'] = 'required|numeric';
                    }
                }

                if (!empty($inputs['daily_allowance'])) {
                    if (!empty($inputs['daily_unit']) && $inputs['daily_unit'] != 'VND') {
                        $rules['daily_rate'] = 'required|numeric';
                    }
                }

                if (!empty($inputs['other_fees'])) {
                    if (!empty($inputs['other_fees_unit']) && $inputs['other_fees_unit'] != 'VND') {
                        $rules['other_fees_rate'] = 'required|numeric';
                    }
                    $rules['other_fees_unit'] = 'required_select';
                }
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
            ];

            $validator = Validator::make($inputs, $rules, [], $customAttributes);

            if ($validator->fails()) {
                unset($inputs['input_file']);
                return redirect()->back()->with('inputs', $inputs)->withErrors($validator);
            }
        }

        // dd($applicationId, $request->input());

        DB::beginTransaction();

        try {
            // get logged user
            $loginUser = Auth::user();

            // Applications table
            $newApplication = $this->saveApplicationMaster($request, $inputs, $application, $loginUser);

            // Application Detail table
            $this->saveApplicationDetail($request, $inputs, $application, $newApplication['id'], $loginUser);

            // commit DB
            DB::commit();

            // send mail to first approver (TO) & CC of each step
            $this->sendNoticeMail($inputs, $newApplication);
        } catch (Exception $ex) {

            DB::rollBack();
            unset($inputs['input_file']);

            // log stacktrace
            report($ex);

            if ($ex instanceof NotFoundFlowSettingException) {
                $msgErr = $ex->getMessage();
            } else {
                $msgErr = __('msg.save_fail');
            }
        }

        return view('application_business_settlement_input', compact('departments'));
    }

    public function show(Request $request, $applicationId)
    {
        $departments = Department::all();

        return view('application_business_settlement_input', compact('departments'));
    }
}
