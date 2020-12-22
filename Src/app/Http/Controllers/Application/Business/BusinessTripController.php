<?php

namespace App\Http\Controllers\Application\Business;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Libs\Common;
use App\Models\Application;
use Illuminate\Support\Arr;
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
        $previewFlg = false;
        $inProgressFlg = false;

        return view('application.business.input', compact('previewFlg', 'inProgressFlg'));
    }

    public function store(Request $request)
    {
        // get input data
        $inputs = $this->getInputs($request);

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

        if (empty($application->business)) {
            abort(404);
        }

        // if application is in approval progress => NOT ALLOWS EDIT
        $previewFlg = ($application->status != config('const.application.status.draft')
            && $application->status != config('const.application.status.applying')
            && $application->status != config('const.application.status.declined'))
            || ($application->current_step > config('const.application.step_type.application')
                && $application->status != config('const.application.status.declined'));

        // disabled draft button if application was applied.
        $inProgressFlg = $application->status != config('const.application.status.draft');
        // if(!$previewFlg){
        //     $inProgressFlg = DB::table('history_approval')->where('application', $id)->exists();
        // }

        // get business application
        // $model = Businesstrip::where('application_id', $id)->first();

        return view('application.business.input', compact('application', 'previewFlg', 'inProgressFlg'));
    }

    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        // get inputs
        $inputs = $this->getInputs($request);

        // check post method
        if (isset($inputs['apply']) || isset($inputs['draft']) || isset($inputs['pdf'])) {
            // export pdf
            if (isset($inputs['pdf'])) {
                return $this->pdf($request, $inputs, $application);
            }
            if (Auth::user()->id !== $application->created_by) {
                abort('403');
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

            // get type form of application
            $formId = config('const.form.biz_trip');
            // get status
            if (isset($inputs['apply'])) {
                $status = config('const.application.status.applying');
            } else if (isset($inputs['draft'])) {
                $status = config('const.application.status.draft');
            }
            // get current step
            if (!empty($app)) {
                $currentStep = $app->current_step;
            } else {
                $currentStep = config('const.budget.step_type.application');
            }
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
                $extension = '.' . $request->file('input_file')->extension();
                // $fileName = time() . $user->id . '_' . $request->file('input_file')->getClientOriginalName();
                $fileName = time() . $user->id . $extension;
                $filePath = $request->file('input_file')->storeAs('uploads/application/', $fileName);
            }

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
                $application['created_by']      = $user->id;
                $application['created_at']      = Carbon::now();

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
                // 'file_path' => isset($filePath) ? $filePath : null,
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

    public function doRedirect($inputs)
    {
        // continue create new application after save success
        // if (isset($inputs['subsequent']) && $inputs['subsequent'] == true) {
        //     return Common::redirectRouteWithAlertSuccess('user.business.create');
        // }
        // back to list application
        if (isset($inputs['apply'])) {
            $msg = __('msg.application.success.apply_ok');
        } elseif (isset($inputs['draft'])) {
            $msg = __('msg.application.success.draft_ok');
        }
        return Common::redirectRouteWithAlertSuccess('user.form.index');
    }

    public function pdf($request, $inputs, $application = null)
    {
        if ($application != null) {

            $loggedUser = Auth::user();
            // get list of approver (include TO & CC)
            $approvers = DB::table('steps')
                ->select('steps.approver_id')
                ->where('steps.flow_id', function ($query) use ($request, $loggedUser) {
                    $query->select('steps.flow_id')
                        ->from('applications')
                        ->join(
                            'steps',
                            'steps.group_id',
                            'applications.group_id'
                        )
                        ->where('steps.approver_id', '=', $loggedUser->id)
                        ->where('applications.id', $request->id)
                        ->where('applications.deleted_at', '=', null)
                        ->limit(1);
                })
                ->where('steps.step_type', $application->current_step)
                ->first();

            // check logged user has permission to access
            // if logged user is not owner of application and also not approval user(TO or CC).
            if (empty($approvers) && $application->created_by !== $loggedUser->id) {
                abort(403);
            }
        }

        if (empty($application)) {
            $inputs['applicant'] = Auth::user();
        } else {
            $inputs['applicant'] = $application->applicant;
        }

        // PDF::setOptions(['defaultFont' => 'Roboto-Black']);
        $pdf = PDF::loadView('application.business.pdf', compact('application', 'inputs'));

        // preview pdf
        return $pdf->stream('Business_Application.pdf');
        // download
        // return $pdf->download('Leave_Application.pdf');
    }

    public function preview(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $previewFlg = true;

        if (empty($application->business)) {
            abort(404);
        }

        $loggedUser = Auth::user();

        // get list of approver (include TO & CC)
        $approvers = DB::table('steps')
            ->select('steps.approver_id')
            ->where('steps.flow_id', function ($query) use ($id, $loggedUser) {
                $query->select('steps.flow_id')
                    ->from('applications')
                    ->join('steps', 'steps.group_id', 'applications.group_id')
                    ->where('steps.approver_id', '=', $loggedUser->id)
                    ->where('applications.id', $id)
                    ->where('applications.deleted_at', '=', null)
                    ->limit(1);
            })
            ->where('steps.step_type', $application->current_step)
            ->first();

        // check logged user has permission to access
        // if logged user is not owner of application and also not approval user(TO or CC).
        if (empty($approvers) && $application->created_by !== $loggedUser->id) {
            abort(403);
        }

        return view('application.business.input', compact('application', 'previewFlg'));
    }

    public function previewPdf(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $inputs = $request->input();

        if (isset($inputs['pdf'])) {
            return $this->pdf($request, $inputs, $application);
        } else {
            abort(404);
        }
    }

    public function getInputs($request)
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
