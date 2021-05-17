<?php

namespace App\Http\Controllers\Application\Entertainment;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Libs\Common;
use App\Models\Company;
use App\Models\TripFee;
use App\Models\Department;
use App\Models\Application;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\Businesstrip2;
use App\Models\Entertainment2;
use App\Models\Transportation;
use App\Models\HistoryApproval;
use App\Models\EntertainmentInfos;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

/**
 * Entertainment Settlement (Step 2)
 */
class Entertainment2Controller extends Controller
{
    public function index()
    {
    }

    public function show(Request $request, $applicationId)
    {
        $departments = Department::where('role', 1)->get();

        // get companies
        $companies = $this->getListCompanyName();

        $application = Application::findOrFail($applicationId);

        // must be entertainment application
        if ($application->form_id != config('const.form.entertainment')) {
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

        $modFlg = !empty($application->entertainment2);

        return view('application_entertainment2_input', compact('departments', 'companies', 'application', 'previewFlg', 'modFlg'));
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
                // session()->flash('pdf_url', route('user.entertainment2.pdf', $application->id));
                // session()->put('inputs', $inputs);
                // return redirect()->route('user.entertainment2.show', $application->id);
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
        // $this->makeCorrectNumeralFromInput($inputs);

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
            // Insert entertainment2
            //=======================================
            $entertainment2 = $application->entertainment2;
            // new biz2
            if (empty($entertainment2)) {
                $entertainment2 = new Entertainment2();
                $entertainment2->application_id = $applicationId;
                $entertainment2->created_by = $loginUser->id;
            }
            $entertainment2->chargedbys = $inputs['chargedbys'];
            $entertainment2->updated_by = $loginUser->id;
            // fill data
            $entertainment2->fill($inputs);
            // save
            $entertainment2->save();

            //=======================================
            // Insert Entertainment Infos
            //=======================================
            // remove old
            EntertainmentInfos::where('entertainment2_id', $entertainment2->id)->delete();
            // add new
            $entertainmentinfos = [];
            if (isset($inputs['entertainmentinfos'])) {
                foreach ($inputs['entertainmentinfos'] as $item) {
                    $item['entertainment2_id']  = $entertainment2->id;
                    $item['created_at']         = Carbon::now();
                    $item['updated_at']         = Carbon::now();

                    $entertainmentinfos[] = $item;
                }
            }
            EntertainmentInfos::insert($entertainmentinfos);

            // commit DB
            DB::commit();

            // success alert
            Common::setAlertSuccess();

            // save success then will open pdf in new tab
            session()->flash('pdf_url', route('user.entertainment2.pdf', $application->id));

            return redirect()->route('user.entertainment2.show', $application->id);
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
                'entertainment_dt'          => __('label.entertainment_date'),
                'est_amount'                => __('label.entertainment_est_amount'),
                'entertainment_person'      => __('label.entertainment_entertainment_person'),
                'pay_info'                  => __('label.entertainment_payment_info'),

                'total_percent'             => __('label.total_percentage'),

                'chargedbys.*.department'   => __('label.business_department'),
                'chargedbys.*.percent'      => __('label.percent'),

                'entertainmentinfos.*.cp_name'           => __('label.entertainment_cp_name'),
                'entertainmentinfos.*.title'             => __('label.entertainment_title'),
                'entertainmentinfos.*.name_attendants'   => __('label.entertainment_name_attendants'),

                'reason_budget_over'            => __('label.entertainment_describe'),
            ];

            // validate rules
            $rules = [];

            $rules['entertainment_dt']          = 'required';
            $rules['pay_info']                  = 'required';
            $rules['entertainment_person']      = 'required';
            $rules['est_amount']                = 'required|numeric';
            // entertainment infos
            $rules['entertainmentinfos.*.cp_name']           = 'required';
            $rules['entertainmentinfos.*.title']             = 'required';
            $rules['entertainmentinfos.*.name_attendants']   = 'required';
            // charged bys
            $rules['chargedbys.*.department']   = 'required_select';
            $rules['chargedbys.*.percent']      = 'required|numeric';

            if(isset($inputs['chargedbys'])) {
                $totalPercent = 0;
                $checkTotal = true;
                foreach ($inputs['chargedbys'] as $item) {
                    if(empty($item['percent'])){
                        $checkTotal = false;
                        continue;
                    }
                    $totalPercent += $item['percent'];
                }
                if($checkTotal){
                    $inputs['total_percent'] = $totalPercent;
                    $rules['total_percent'] = 'equal:100,%';
                }
            }

            if (!empty($inputs['est_amount']) && floatval($inputs['est_amount']) > 4000000){
                $rules['reason_budget_over'] = 'required';
            }

            $validator = Validator::make($inputs, $rules, [], $customAttributes);
            if ($validator->fails()) {
                unset($inputs['input_file']);
                return $validator;
            }
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
            $inputs = $application->entertainment2->toArray();
            $inputs['entertainmentinfos'] = $application->entertainment2->entertainmentinfos;
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

        // $this->makeCorrectNumeralFromInput($inputs);

        // PDF::setOptions(['defaultFont' => 'Roboto-Black']);
        // PDF::setOptions(['enable-javascript' => true]);
        $pdf = PDF::loadView("application_entertainment2_pdf", compact('application', 'inputs'));

        // preview pdf
        $fileName = "entertainment_settlement.pdf";
        return $pdf->stream($fileName);
        // download
        // return $pdf->download($fileName);
    }

    private function getListCompanyName()
    {
        $companies = Company::all('name');
        $companies = Arr::pluck($companies->toArray(), 'name');

        return $companies;
    }
}
