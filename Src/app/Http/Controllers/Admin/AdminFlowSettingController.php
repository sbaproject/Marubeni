<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminFlowSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.flow.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $forms =  Form::all();
        $users =  User::all();
        $applicants = DB::table('applicants')
            ->join('departments', 'applicants.department_id', '=', 'departments.id')         
            ->select('applicants.id' ,'applicants.location', 'applicants.role', 'departments.name')
            ->orderBy('applicants.id', 'asc')
            ->get();

        $locations = config('const.location');
        $roles = config('const.role');

        $applicantRoles = array();
        foreach ($applicants as $applicant) {
            $data = array();
            $data['id'] = $applicant->id;
            $data['name'] = strtoupper(array_search($applicant->location, $locations)) . ' - ' . $applicant->name . ' - ' . array_search($applicant->role, $roles);
            $applicantRoles[] = $data;
        }
        return view('admin.flow.create', compact('forms', 'users', 'applicantRoles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return view('admin.flow.create');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
