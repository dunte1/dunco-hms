<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Services\PatientFlowService;
use Illuminate\View\View;

class ClinicianWorklistController extends Controller
{
    public function index(PatientFlowService $flow): View
    {
        $worklist = $flow->doctorWorklist(auth()->user());

        return view('hms.dashboard.worklist', compact('worklist'));
    }
}
