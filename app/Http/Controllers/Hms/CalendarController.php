<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    /**
     * Display the calendar view for appointments
     */
    public function index(Request $request): View
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        
        // Get all appointments for the calendar view
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('scheduled_at', $date)
            ->orderBy('scheduled_at')
            ->get();
        
        // Get month statistics
        $monthStats = [
            'total' => Appointment::whereMonth('scheduled_at', now()->month)
                ->whereYear('scheduled_at', now()->year)
                ->count(),
            'today' => Appointment::whereDate('scheduled_at', today())->count(),
            'this_week' => Appointment::whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Appointment::whereMonth('scheduled_at', now()->month)
                ->whereYear('scheduled_at', now()->year)
                ->count(),
        ];
        
        return view('hms.appointments.calendar', compact('appointments', 'date', 'monthStats'));
    }
}
