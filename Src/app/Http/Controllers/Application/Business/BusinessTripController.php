<?php

namespace App\Http\Controllers\Application\Business;

use Carbon\Carbon;
use App\Libs\Common;
use App\Models\BusinessTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessTripController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        $method = [1, 2];
        return view('application.business.input', compact('method'));
    }

    public function store(Request $request)
    {
        // get input data
        $inputs = $request->all();

        // export pdf
        if (isset($inputs['pdf'])) {
            // return $this->pdf($request, $inputs);
        }
        // validate
        $this->doValidate($request, $inputs);

        // save
        $this->doSaveData($request, $inputs);

        // continue create new application after save success
        if (isset($inputs['subsequent'])) {
            return Common::redirectRouteWithAlertSuccess('user.business.create');
        }
        // back to list application
        return Common::redirectRouteWithAlertSuccess('user.form.index');
    }

    public function show()
    {
    }

    public function update()
    {
    }

    public function doValidate($request, $inputs)
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
            }
            $validator = Validator::make($inputs, $rules);
            $validator->validate();
        }
    }

    public function doSaveData($request, $inputs, $mApplication = null)
    {
        DB::transaction(function () use ($request, $inputs, $mApplication) {
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
                $currentStep = 1; // [business form] default = 1

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
                    ->where('groups.budget_id', '<>', null)
                    ->where('groups.deleted_at', '=', null)
                    ->first();

                $application['form_id'] = $formId;
                $application['group_id'] = $group->id;
                $application['current_step'] = $currentStep;
                $application['created_by'] = $user->id;
                $application['created_at'] = Carbon::now();
            }

            // add
            if (!$request->id) {
                $applicationId = DB::table('applications')->insertGetId($application);
            }
            // update
            else {
                DB::table('applications')->where('id', $request->id)->update($application);
            }

            /**-------------------------
             * create [Business Application] detail
             *-------------------------*/
            // delete old file
            if ($request->id) {
                $leave = BusinessTrip::where('application_id', $mApplication->id)->first();
                $filePath = $leave->file_path;
                // attchached file was changed
                if ($inputs['file_path'] != $filePath) {
                    if (!empty($leave->file_path)) {
                        if (Storage::exists($leave->file_path)) {
                            Storage::delete($leave->file_path);
                        }
                    }
                    $filePath = null;
                }
            }
            // upload new attached file
            if ($request->file('input_file')) {
                // $extension = '.' . $request->file('input_file')->extension();
                $fileName = time() . $user->id . '_' . $request->file('input_file')->getClientOriginalName();
                $filePath = $request->file('input_file')->storeAs('uploads/application/', $fileName);
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
                'file_path' => isset($filePath) ? $filePath : null,
                'updated_by' => $user->id,
                'updated_at' => Carbon::now(),
            ];
            // for new
            if (!$request->id) {
                $bizData['application_id'] = $applicationId;
                $bizData['created_by'] = $user->id;
                $bizData['created_at'] = Carbon::now();
            }

            DB::table('businesstrips')->updateOrInsert(['application_id' => $request->id], $bizData);
        });
    }
}
