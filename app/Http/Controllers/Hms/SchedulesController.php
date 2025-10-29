<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchedulesController extends Controller
{
    public function index(Request $request): View
    {
        $query = Schedule::with('employee');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('employee', function($empQuery) use ($search) {
                    $empQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhere('notes', 'like', "%{$search}%");
            });
        }
        
        // Filter by shift type
        if ($request->filled('shift_type')) {
            $query->where('shift_type', $request->shift_type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }
        
        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('schedule_date', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('schedule_date', '<=', $request->to_date);
        }
        
        $schedules = $query->orderBy('schedule_date', 'desc')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => Schedule::count(),
            'today' => Schedule::whereDate('schedule_date', today())->count(),
            'this_week' => Schedule::whereBetween('schedule_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'approved' => Schedule::where('is_approved', true)->count(),
            'pending' => Schedule::where('is_approved', false)->count(),
        ];
        
        return view('hms.hr.schedules.index', compact('schedules', 'stats'));
    }

    public function create(): View
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.hr.schedules.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'schedule_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'shift_type' => 'required|in:morning,afternoon,night,on_call',
            'notes' => 'nullable|string',
        ]);

        Schedule::create($data);
        return redirect()->route('hms.hr.schedules.index')->with('status', 'Schedule created');
    }
}
