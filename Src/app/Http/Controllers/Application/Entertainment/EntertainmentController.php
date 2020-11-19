<?php

namespace App\Http\Controllers\Application\Entertainment;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Libs\Common;
use App\Models\Budget;
use App\Models\Company;
use App\Models\Application;
use Illuminate\Support\Arr;
use App\Models\Entertaiment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Entertainment\NotFoundFlowSettingException;

class EntertainmentController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        // get companies
        $companies = Company::all('name');
        $companies = Arr::pluck($companies->toArray(), 'name');

        return view('application.entertainment.input', compact('companies'));
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

        // get business application
        // $model = Entertaiment::where('application_id', $id)->first();

        // get companies
        $companies = Company::all('name');
        $companies = Arr::pluck($companies->toArray(), 'name');

        return view('application.entertainment.input', compact('application', 'companies'));
    }

    public function update(Request $request, $id)
    {
        // check owner
        $application = Application::findOrFail($id);
        if (Auth::user()->id !== $application->created_by) {
            abort('403');
        }

        // get inputs
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
                $rules['entertainment_dt']          = 'required';
                $rules['place']                     = 'required';
                $rules['during_trip']               = 'required_select';
                $rules['check_row']                 = 'required_select';
                $rules['has_entertainment_times']   = 'required_select';
                $rules['existence_projects']        = 'required_select';
                $rules['includes_family']           = 'required_select';
                $rules['entertainment_person']      = 'required|numeric';
                $rules['est_amount']                = 'required|numeric';
                $rules['infos.*.cp_name']           = 'required';
                $rules['infos.*.title']             = 'required';
                $rules['infos.*.name_attendants']   = 'required';
                $rules['infos.*.details_dutles']    = 'required';

                if ($inputs['has_entertainment_times'] == true) {
                    $rules['entertainment_times'] = 'required|numeric';
                }
            }
            $customAttributes = [
                'entertainment_dt'          => __('label.entertainment.entertainment_dt'),
                'place'                     => __('label.entertainment.place'),
                'during_trip'               => __('label.entertainment.during_trip'),
                'check_row'                 => __('label.entertainment.check_row'),
                'has_entertainment_times'   => __('label.entertainment.entertainment_times'),
                'entertainment_times'       => __('label.entertainment.entertainment_times'),
                'existence_projects'        => __('label.entertainment.existence_projects'),
                'includes_family'           => __('label.entertainment.includes_family'),
                'entertainment_person'      => __('label.entertainment.entertainment_person'),
                'est_amount'                => __('label.entertainment.est_amount'),
                'infos.*.cp_name'           => __('label.entertainment.cp_name'),
                'infos.*.title'             => __('label.entertainment.title'),
                'infos.*.name_attendants'   => __('label.entertainment.name_attendants'),
                'infos.*.details_dutles'    => __('label.entertainment.details_dutles'),
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

            // get current step
            $currentStep = config('const.budget.step_type.application'); // [entertainment form] default = 1
            // get budget type
            $budgetType = config('const.budget.budget_type.entertainment');
            // get position
            $position = config('const.budget.position.po'); // set temp -> change here
            // get budget comparation type
            $budget = Budget::where([
                'budget_type' => $budgetType,
                'step_type' => $currentStep,
                'position' => $position,
            ])->first();

            // not found available flow setting
            if (empty($budget)) {
                throw new NotFoundFlowSettingException();
            }

            // get budget comparation type
            if ($inputs['est_amount'] <= $budget->amount) {
                $budgetTypeCompare = config('const.budget.budeget_type_compare.less_equal');
            } else {
                $budgetTypeCompare = config('const.budget.budeget_type_compare.greater_than');
            }

            // get [entertainment form] id
            $formId = config('const.form.entertainment');

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
                    function ($join) use ($currentStep, $budgetType, $position) {
                        $join->on('groups.budget_id', '=', 'budgets.id')
                            ->where('budgets.budget_type', '=', $budgetType)
                            ->where('budgets.step_type', '=', $currentStep)
                            ->where('budgets.position', '=', $position) // set temp, change here
                            ->where('budgets.deleted_at', '=', null);
                    }
                )
                ->where([
                    'groups.budget_type_compare' => $budgetTypeCompare,
                    'groups.deleted_at' => null,

                ])
                ->first();

            // not found available flow setting
            if (empty($group)) {
                throw new NotFoundFlowSettingException();
            }

            $application['form_id']         = $formId;
            $application['group_id']        = $group->id;
            $application['current_step']    = $currentStep;

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
                $application['created_by']      = $user->id;
                $application['created_at']      = Carbon::now();

                $applicationId = DB::table('applications')->insertGetId($application);
            } else {
                DB::table('applications')->where('id', $request->id)->update($application);
            }

            /**-------------------------
             * create [Entertainment Application] detail
             *-------------------------*/
            // if ($request->id) {
            //     $entertainment = Entertaiment::where('application_id', $request->id)->first();
            // }

            // prepare entertainment data
            $etData = [
                'entertainment_dt'          => $inputs['entertainment_dt'],
                'place'                     => $inputs['place'],
                'during_trip'               => $inputs['during_trip'],
                'check_row'                 => $inputs['check_row'],
                'has_entertainment_times'   => $inputs['has_entertainment_times'],
                'entertainment_times'       => $inputs['entertainment_times'],
                'existence_projects'        => $inputs['existence_projects'],
                'includes_family'           => $inputs['includes_family'],
                'project_name'              => $inputs['project_name'],
                'entertainment_reason'      => $inputs['entertainment_reason'],
                'entertainment_person'      => $inputs['entertainment_person'],
                'est_amount'                => $inputs['est_amount'],
                'reason_budget_over'        => $inputs['reason_budget_over'],
                'file_path'                 => isset($filePath) ? $filePath : null,
                'updated_by'                => $user->id,
                'updated_at'                => Carbon::now(),
            ];

            // save entertainment application
            if (empty($app)) {
                $etData['application_id'] = $applicationId;
                $etData['created_by'] = $user->id;
                $etData['created_at'] = Carbon::now();

                $etId = DB::table('entertaiments')->insertGetId($etData);
            } else {
                DB::table('entertaiments')->where('id', $app->entertainment->id)->update($etData);
                DB::table('entertaiment_infos')->where('entertaiment_id', $app->entertainment->id)->delete();
            }
            // save transportations
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
                $msgErr = $ex->getMessage();
            }
        }

        return $msgErr;
    }

    public function getInputs($request)
    {
        $inputs = $request->all();

        if (!isset($inputs['during_trip'])) {
            $inputs['during_trip'] = null;
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

        return $inputs;
    }

    public function doRedirect($inputs)
    {
        // continue create new application after save success
        if (isset($inputs['subsequent'])) {
            return Common::redirectRouteWithAlertSuccess('user.entertainment.create');
        }
        // back to list application
        return Common::redirectRouteWithAlertSuccess('user.form.index');
    }

    public function pdf($request, $inputs, $mApplication = null)
    {
        // get logged user
        $user = Auth::user();

        // PDF::setOptions(['defaultFont' => 'Roboto-Black']);
        $pdf = PDF::loadView('application.entertainment.pdf', compact('user', 'inputs'));

        // preview pdf
        return $pdf->stream('Business_Application.pdf');
        // download
        // return $pdf->download('Leave_Application.pdf');
    }
}
