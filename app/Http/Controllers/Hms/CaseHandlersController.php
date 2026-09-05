<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CaseHandler;
use App\Models\PatientCase;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaseHandlersController extends Controller
{
    public function index(Request $request): View
    {
        $query = CaseHandler::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('handler_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }
        
        $caseHandlers = $query->orderBy('first_name')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => CaseHandler::count(),
            'active' => CaseHandler::where('is_active', true)->count(),
            'inactive' => CaseHandler::where('is_active', false)->count(),
        ];
        
        return view('hms.case-handlers.index', compact('caseHandlers', 'stats'));
    }

    public function create(): View
    {
        return view('hms.case-handlers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:case_handlers,email',
            'phone' => 'required|string',
            'specialization' => 'required|string',
            'qualifications' => 'required|string',
            'address' => 'required|string',
            'joining_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $data['handler_id'] = 'CH-' . date('Y') . '-' . str_pad(CaseHandler::count() + 1, 4, '0', STR_PAD_LEFT);

        CaseHandler::create($data);
        return redirect()->route('hms.case-handlers.index')->with('status', 'Case handler registered');
    }

    public function cases(Request $request): View
    {
        $query = PatientCase::with(['patient', 'caseHandler']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($patientQuery) use ($search) {
                      $patientQuery->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by case type
        if ($request->filled('case_type')) {
            $query->where('case_type', $request->case_type);
        }
        
        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $cases = $query->latest('opened_date')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => PatientCase::count(),
            'open' => PatientCase::where('status', 'open')->count(),
            'in_progress' => PatientCase::where('status', 'in_progress')->count(),
            'urgent' => PatientCase::where('priority', 'urgent')->count(),
        ];
        
        return view('hms.case-handlers.cases', compact('cases', 'stats'));
    }

    public function createCase(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $caseHandlers = CaseHandler::where('is_active', true)->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.case-handlers.create-case', compact('patients', 'caseHandlers'));
    }

    public function storeCase(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'case_handler_id' => 'required|exists:case_handlers,id',
            'case_type' => 'required|in:medical,social,financial,legal',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'opened_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $data['case_number'] = 'CASE-' . date('Y') . '-' . str_pad(PatientCase::count() + 1, 6, '0', STR_PAD_LEFT);

        PatientCase::create($data);
        return redirect()->route('hms.case-handlers.cases')->with('status', 'Case created');
    }

    public function show(CaseHandler $handler): View
    {
        return view('hms.case-handlers.show', compact('handler'));
    }

    public function edit(CaseHandler $handler): View
    {
        return view('hms.case-handlers.edit', compact('handler'));
    }

    public function update(Request $request, CaseHandler $handler): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:case_handlers,email,' . $handler->id,
            'phone' => 'required|string',
            'specialization' => 'required|string',
            'qualifications' => 'required|string',
            'address' => 'required|string',
            'joining_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $handler->update($data);
        return redirect()->route('hms.case-handlers.index')->with('status', 'Case handler updated');
    }

    public function destroy(CaseHandler $handler): RedirectResponse
    {
        $handler->delete();
        return redirect()->route('hms.case-handlers.index')->with('status', 'Case handler deleted');
    }

    public function showCase(PatientCase $case): View
    {
        $case->load(['patient', 'caseHandler']);
        return view('hms.case-handlers.show-case', compact('case'));
    }

    public function editCase(PatientCase $case): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $caseHandlers = CaseHandler::where('is_active', true)->orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.case-handlers.edit-case', compact('case', 'patients', 'caseHandlers'));
    }

    public function updateCase(Request $request, PatientCase $case): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'case_handler_id' => 'required|exists:case_handlers,id',
            'case_type' => 'required|in:medical,social,financial,legal',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'opened_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $case->update($data);
        return redirect()->route('hms.case-handlers.cases')->with('status', 'Case updated');
    }

    public function destroyCase(PatientCase $case): RedirectResponse
    {
        $case->delete();
        return redirect()->route('hms.case-handlers.cases')->with('status', 'Case deleted');
    }
}
