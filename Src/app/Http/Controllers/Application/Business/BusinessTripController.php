<?php

namespace App\Http\Controllers\Application\Business;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Libs\Common;
use App\Models\Application;
use App\Models\Businesstrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Entertainment\NotFoundFlowSettingException;

class BusinesstripController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        return view('application.business.input');
    }

    public function store(Request $request)
    {
        // get input data
        $inputs = $request->all();

        // check post method
        if (isset($inputs['apply']) || isset($inputs['draft']) || isset($inputs['pdf'])) {
            // export pdf
            if (isset($inputs['pdf'])) {
                return $this->pdf($request, $inputs);
            }
        } else {
            abort(404);
        }

        // validate
        $validator = $this->doValidate($request, $inputs);
        if (!empty($validator)) {
            return redirect()->back()->with('inputs', $inputs)->withErrors($validator);
        }

        // save
        $msgErr = $this->doSaveData($request, $inputs);
        if (!empty($msgErr)) {
            return Common::redirectBackWithAlertFail($msgErr)->with('inputs', $inputs);
        }

        // redirect atfer save
        return $this->doRedirect($inputs);
    }

    public function show($id)
    {
        // check owner
        $application = Application::findOrFail($id);
        if (Auth::user()->id !== $application->created_by) {
            abort('403');
        }

        // get business application
        // $model = Businesstrip::where('application_id', $id)->first();

        // dd($application);


        return view('application.business.input', compact('application'));
    }

    public function update(Request $request, $id)
    {
        // check owner
        $application = Application::findOrFail($id);
        if (Auth::user()->id !== $application->created_by) {
            abort('403');
        }

        // get inputs
        $inputs = $request->all();

        // check post method
        if (isset($inputs['apply']) || isset($inputs['draft']) || isset($inputs['pdf'])) {
            // export pdf
            if (isset($inputs['pdf'])) {
                return $this->pdf($request, $inputs);
            }
        } else {
            abort(404);
        }

        // validate
        $validator = $this->doValidate($request, $inputs);
        if (!empty($validator)) {
            return redirect()->back()->with('inputs', $inputs)->withErrors($validator);
        }

        // save
        $msgErr = $this->doSaveData($request, $inputs, $application);
        if (!empty($msgErr)) {
            return Common::redirectBackWithAlertFail($msgErr)->with('inputs', $inputs);
        }

        // redirect atfer save
        return $this->doRedirect($inputs);
    }

    public function doValidate($request, &$inputs)
    {
        if (isset($inputs['apply']) || isset($inputs['draft'])) {
            $rules = [];
            // attached file
            if ($request->file('input_file')) {
                $rules['input_file'] = 'mimes:txt,pdf,jpg,jpeg,png|max:200';
            }
            if (isset($inputs['apply'])) {
                $rules['trip_dt_from'] = 'required';
                $rules['trip_dt_to'] = 'required';
                $rules['trans.*.departure'] = 'required';
                $rules['trans.*.arrive'] = 'required';
                $rules['trans.*.method'] = 'required';
            }
            $customAttributes = [
                'trans.*.departure' => __('label.business.departure'),
                'trans.*.arrive' => __('label.business.arrival'),
                'trans.*.method' => __('label.business.method'),
            ];
            $validator = Validator::make($inputs, $rules, [], $customAttributes);
            if ($validator->fails()) {
                unset($inputs['input_file']);
                return $validator;
            }
        }
    }

    public function doSaveData($request, &$inputs, $app = null)
    {
        $msgErr = '';

        DB::beginTransaction();

        try {
            // get user
            $user = Auth::user();

            /**-------------------------
             * create application
             *-------------------------*/

            // get status
            if (isset($inputs['apply'])) {
                $status = config('const.application.status.applying');
            } else if (isset($inputs['draft'])) {
                $status = config('const.application.status.draft');
            }

            // prepare data
            $application = [
                'status' => $status,
                'updated_by' => $user->id,
                'updated_at' => Carbon::now(),
            ];

            // for new
            if (!$request->id) {

                // get current step
                $currentStep = config('const.budget.step_type.application'); // [business form] default = 1

                // get [business form] id
                $formId = config('const.form.biz_trip');

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
                    ->join('budgets', function ($join) use ($currentStep) {
                        $join->on('groups.budget_id', '=', 'budgets.id')
                            ->where('budgets.budget_type', '=', config('const.budget.budget_type.business'))
                            ->where('budgets.step_type', '=', $currentStep)
                            ->where('budgets.position', '=', config('const.budget.position.business')) // set temp, change here
                            ->where('budgets.deleted_at', '=', null);
                    })
                    ->where('groups.deleted_at', '=', null)
                    ->first();

                if (empty($group)) {
                    throw new NotFoundFlowSettingException();
                }

                $application['form_id'] = $formId;
                $application['group_id'] = $group->id;
                $application['current_step'] = $currentStep;
                $application['created_by'] = $user->id;
                $application['created_at'] = Carbon::now();
            }

            // delete old file
            if (!empty($app)) {
                $filePath = $app->file_path;
                // attchached file was changed
                if ($inputs['file_path'] != $filePath) {
                    if (!empty($app->file_path)) {
                        if (Storage::exists($app->file_path)) {
                            Storage::delete($app->file_path);
                        }
                    }
                    $filePath = null;
                }
            }
            // upload new attached file
            if ($request->file('input_file')) {
                $fileName = time() . $user->id . '_' . $request->file('input_file')->getClientOriginalName();
                $filePath = $request->file('input_file')->storeAs('uploads/application/', $fileName);
            }

            $application['file_path'] = isset($filePath) ? $filePath : null;

            // save applications
            if (!$request->id) {
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
                'destinations' => $inputs['destinations'],
                'trip_dt_from' => $inputs['trip_dt_from'],
                'trip_dt_to' => $inputs['trip_dt_to'],
                'accommodation' => $inputs['accommodation'],
                'accompany' => $inputs['accompany'],
                'borne_by' => $inputs['borne_by'],
                'comment' => $inputs['comment'],
                // 'file_path' => isset($filePath) ? $filePath : null,
                'updated_by' => $user->id,
                'updated_at' => Carbon::now(),
            ];
            // for new
            if (!$request->id) {
                $bizData['application_id'] = $applicationId;
                $bizData['created_by'] = $user->id;
                $bizData['created_at'] = Carbon::now();
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
                $item['businesstrip_id'] = $bizId;
                $item['departure'] = $value['departure'];
                $item['arrive'] = $value['arrive'];
                $item['method'] = $value['method'];
                $item['created_at'] = Carbon::now();
                $item['updated_at'] = Carbon::now();

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
                $msgErr = $ex->getMessage();
            }
        }

        return $msgErr;
    }

    public function doRedirect($inputs)
    {
        // continue create new application after save success
        if (isset($inputs['subsequent'])) {
            return Common::redirectRouteWithAlertSuccess('user.business.create');
        }
        // back to list application
        return Common::redirectRouteWithAlertSuccess('user.form.index');
    }

    public function pdf($request, $inputs, $mApplication = null)
    {
        // get logged user
        $user = Auth::user();

        // PDF::setOptions(['defaultFont' => 'Roboto-Black']);
        $pdf = PDF::loadView('application.business.pdf', compact('user', 'inputs'));

        // preview pdf
        return $pdf->stream('Business_Application.pdf');
        // download
        // return $pdf->download('Leave_Application.pdf');
    }
}
