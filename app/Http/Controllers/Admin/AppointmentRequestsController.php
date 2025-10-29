<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentRequest;
use Illuminate\View\View;

class AppointmentRequestsController extends Controller
{
    public function index(): View
    {
        $requests = AppointmentRequest::latest()->paginate(20);
        
        $totalRequests = AppointmentRequest::count();
        $todayRequests = AppointmentRequest::whereDate('created_at', today())->count();
        $existingPatients = AppointmentRequest::where('is_existing_patient', true)->count();
        
        return view('admin.appointments.requests', compact('requests', 'totalRequests', 'todayRequests', 'existingPatients'));
    }
}


