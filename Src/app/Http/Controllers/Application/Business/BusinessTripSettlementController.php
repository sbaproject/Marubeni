<?php

namespace App\Http\Controllers\Application\Business;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BusinessTripSettlementController extends Controller
{
    public function index()
    {

    }

    public function create(Request $request, $applicationId)
    {
        $departments = Department::all();

        return view('application_business_settlement_input', compact('departments', 'applicationId'));
    }

    public function store(Request $request, $applicationId)
    {
        $departments = Department::all();

        dd($applicationId, $request->input());

        return view('application_business_settlement_input', compact('departments'));
    }

    public function show(Request $request, $applicationId)
    {
        $departments = Department::all();
        
        return view('application_business_settlement_input', compact('departments'));
    }
}
