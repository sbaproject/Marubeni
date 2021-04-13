<?php

namespace App\Http\Controllers\Application\Business;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Businesstrip;

class BusinessTripSettlementController extends Controller
{
    public function index()
    {

    }

    public function create(Request $request, $applicationId)
    {
        $departments = Department::all();

        $application = Application::find($applicationId);

        if (empty($application)){
            abort(404);
        }

        $previewFlg = false;

        // get Itinerary of bussiness application on step 1
        $itineraries = $application->business->transportations;

        // dd($itineraries, $application->business->transportations);



        return view('application_business_settlement_input', compact('departments', 'application', 'itineraries', 'previewFlg'));
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
