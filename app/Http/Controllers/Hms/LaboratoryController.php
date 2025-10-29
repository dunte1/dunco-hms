<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\LabRequest;
use App\Models\LabRequestItem;
use App\Models\LabCategory;
use App\Models\LabTechnician;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class LaboratoryController extends Controller
{
    public function index(): View
    {
        // Calculate real laboratory statistics
        $stats = [
            ['label' => 'Total Tests', 'value' => LabTest::where('is_active', true)->count()],
            ['label' => 'Pending Requests', 'value' => LabRequest::where('status', 'pending')->count()],
            ['label' => 'Completed Today', 'value' => LabRequest::whereDate('request_date', today())->where('status', 'completed')->count()],
            ['label' => 'Technicians', 'value' => LabTechnician::where('is_active', true)->count()],
        ];

        // Get recent lab requests
        $recentRequests = LabRequest::with(['patient', 'doctor', 'items.labTest'])
            ->latest()
            ->take(10)
            ->get();

        // Get pending requests
        $pendingRequests = LabRequest::with(['patient', 'doctor'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Get popular tests
        $popularTests = LabTest::withCount('requestItems')
            ->orderBy('request_items_count', 'desc')
            ->take(5)
            ->get();

        return view('hms.laboratory.index', compact('stats', 'recentRequests', 'pendingRequests', 'popularTests'));
    }

    // Test Management
    public function tests(): View
    {
        $tests = LabTest::with('category')
            ->latest()
            ->paginate(20);
        
        $categories = LabCategory::all();
        
        return view('hms.laboratory.tests.index', compact('tests', 'categories'));
    }

    public function createTest(): View
    {
        $categories = LabCategory::all();
        return view('hms.laboratory.tests.create', compact('categories'));
    }

    public function storeTest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'test_name' => 'required|string|max:255',
            'category_id' => 'required|exists:lab_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'normal_range' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        LabTest::create($validated);

        return redirect()->route('hms.laboratory.tests')
            ->with('success', 'Lab test added successfully.');
    }

    public function editTest(LabTest $test): View
    {
        $categories = LabCategory::all();
        return view('hms.laboratory.tests.edit', compact('test', 'categories'));
    }

    public function updateTest(Request $request, LabTest $test): RedirectResponse
    {
        $validated = $request->validate([
            'test_name' => 'required|string|max:255',
            'category_id' => 'required|exists:lab_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'normal_range' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $test->update($validated);

        return redirect()->route('hms.laboratory.tests')
            ->with('success', 'Lab test updated successfully.');
    }

    public function destroyTest(LabTest $test): RedirectResponse
    {
        $test->delete();
        return redirect()->route('hms.laboratory.tests')
            ->with('success', 'Lab test deleted successfully.');
    }

    // Lab Request Management
    public function requests(): View
    {
        $requests = LabRequest::with(['patient', 'doctor', 'items.labTest'])
            ->latest()
            ->paginate(20);
        
        return view('hms.laboratory.requests.index', compact('requests'));
    }

    public function createRequest(): View
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $tests = LabTest::where('is_active', true)->get();
        
        return view('hms.laboratory.requests.create', compact('patients', 'doctors', 'tests'));
    }

    public function storeRequest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'request_date' => 'required|date',
            'clinical_notes' => 'nullable|string',
            'tests' => 'required|array|min:1',
            'tests.*' => 'exists:lab_tests,id',
        ]);

        DB::transaction(function () use ($validated) {
            $labRequest = LabRequest::create([
                'request_number' => 'LAB-' . str_pad(LabRequest::count() + 1, 6, '0', STR_PAD_LEFT),
                'patient_id' => $validated['patient_id'],
                'doctor_id' => $validated['doctor_id'],
                'request_date' => $validated['request_date'],
                'clinical_notes' => $validated['clinical_notes'],
                'status' => 'pending',
            ]);

            foreach ($validated['tests'] as $testId) {
                LabRequestItem::create([
                    'lab_request_id' => $labRequest->id,
                    'lab_test_id' => $testId,
                    'status' => 'pending',
                ]);
            }
        });

        return redirect()->route('hms.laboratory.requests')
            ->with('success', 'Lab request created successfully.');
    }

    public function showRequest(LabRequest $request): View
    {
        $request->load(['patient', 'doctor', 'items.labTest']);
        return view('hms.laboratory.requests.show', compact('request'));
    }

    public function processRequest(Request $request, LabRequest $labRequest): RedirectResponse
    {
        $validated = $request->validate([
            'results' => 'required|array',
            'results.*.result_value' => 'required|string',
            'results.*.unit' => 'nullable|string',
            'results.*.result_notes' => 'nullable|string',
            'results_notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $labRequest) {
            foreach ($validated['results'] as $itemId => $result) {
                LabRequestItem::where('id', $itemId)
                    ->where('lab_request_id', $labRequest->id)
                    ->update([
                        'result_value' => $result['result_value'],
                        'unit' => $result['unit'],
                        'result_notes' => $result['result_notes'],
                        'status' => 'completed',
                    ]);
            }

            $labRequest->update([
                'status' => 'completed',
                'results_notes' => $validated['results_notes'],
            ]);
        });

        return redirect()->route('hms.laboratory.requests')
            ->with('success', 'Lab request processed successfully.');
    }

    // Technician Management
    public function technicians(): View
    {
        $technicians = LabTechnician::latest()
            ->paginate(20);
        
        return view('hms.laboratory.technicians.index', compact('technicians'));
    }

    public function createTechnician(): View
    {
        return view('hms.laboratory.technicians.create');
    }

    public function storeTechnician(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'technician_id' => 'required|string|unique:lab_technicians,technician_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:lab_technicians,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'qualification' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'required|string|max:255',
            'license_expiry' => 'required|date|after:today',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'shift' => 'required|in:morning,evening,night',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        LabTechnician::create($validated);

        return redirect()->route('hms.laboratory.technicians')
            ->with('success', 'Lab technician added successfully.');
    }

    // Reports
    public function reports(): View
    {
        $monthlyRequests = LabRequest::selectRaw('MONTH(request_date) as month, COUNT(*) as count')
            ->whereYear('request_date', now()->year)
            ->groupBy('month')
            ->get();

        $testStats = LabTest::withCount('requestItems')
            ->orderBy('request_items_count', 'desc')
            ->take(10)
            ->get();

        $statusStats = LabRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('hms.laboratory.reports', compact('monthlyRequests', 'testStats', 'statusStats'));
    }
}


