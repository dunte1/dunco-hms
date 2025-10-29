<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\RadiologyTest;
use App\Models\RadiologyRequest;
use App\Models\RadiologyCategory;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RadiologyController extends Controller
{
    public function index(): View
    {
        // Calculate real radiology statistics
        $stats = [
            ['label' => 'Total Tests', 'value' => RadiologyTest::where('is_active', true)->count()],
            ['label' => 'Pending Requests', 'value' => RadiologyRequest::where('status', 'pending')->count()],
            ['label' => 'Completed Today', 'value' => RadiologyRequest::whereDate('request_date', today())->where('status', 'completed')->count()],
            ['label' => 'Categories', 'value' => RadiologyCategory::count()],
        ];

        // Get recent radiology requests
        $recentRequests = RadiologyRequest::with(['patient', 'doctor'])
            ->latest()
            ->take(10)
            ->get();

        // Get pending requests
        $pendingRequests = RadiologyRequest::with(['patient', 'doctor'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Get popular tests
        $popularTests = RadiologyTest::withCount('requests')
            ->orderBy('requests_count', 'desc')
            ->take(5)
            ->get();

        return view('hms.radiology.index', compact('stats', 'recentRequests', 'pendingRequests', 'popularTests'));
    }

    // Test Management
    public function tests(): View
    {
        $tests = RadiologyTest::with('category')
            ->latest()
            ->paginate(20);
        
        $categories = RadiologyCategory::all();
        
        return view('hms.radiology.tests.index', compact('tests', 'categories'));
    }

    public function createTest(): View
    {
        $categories = RadiologyCategory::all();
        return view('hms.radiology.tests.create', compact('categories'));
    }

    public function storeTest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'test_name' => 'required|string|max:255',
            'category_id' => 'required|exists:radiology_categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'preparation_instructions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        RadiologyTest::create($validated);

        return redirect()->route('hms.radiology.tests')
            ->with('success', 'Radiology test added successfully.');
    }

    // Request Management
    public function requests(): View
    {
        $requests = RadiologyRequest::with(['patient', 'doctor'])
            ->latest()
            ->paginate(20);
        
        return view('hms.radiology.requests.index', compact('requests'));
    }

    public function createRequest(): View
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $tests = RadiologyTest::where('is_active', true)->get();
        
        return view('hms.radiology.requests.create', compact('patients', 'doctors', 'tests'));
    }

    public function storeRequest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'test_id' => 'required|exists:radiology_tests,id',
            'request_date' => 'required|date',
            'clinical_notes' => 'nullable|string',
            'urgency' => 'required|in:routine,urgent,emergency',
        ]);

        RadiologyRequest::create([
            'request_number' => 'RAD-' . str_pad(RadiologyRequest::count() + 1, 6, '0', STR_PAD_LEFT),
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'test_id' => $validated['test_id'],
            'request_date' => $validated['request_date'],
            'clinical_notes' => $validated['clinical_notes'],
            'urgency' => $validated['urgency'],
            'status' => 'pending',
        ]);

        return redirect()->route('hms.radiology.requests')
            ->with('success', 'Radiology request created successfully.');
    }

    public function showRequest(RadiologyRequest $request): View
    {
        $request->load(['patient', 'doctor', 'test']);
        return view('hms.radiology.requests.show', compact('request'));
    }

    public function processRequest(Request $request, RadiologyRequest $radiologyRequest): RedirectResponse
    {
        $validated = $request->validate([
            'result_notes' => 'required|string',
            'findings' => 'required|string',
            'impression' => 'required|string',
            'recommendations' => 'nullable|string',
        ]);

        $radiologyRequest->update([
            'status' => 'completed',
            'result_notes' => $validated['result_notes'],
            'findings' => $validated['findings'],
            'impression' => $validated['impression'],
            'recommendations' => $validated['recommendations'],
        ]);

        return redirect()->route('hms.radiology.requests')
            ->with('success', 'Radiology request processed successfully.');
    }

    // Reports
    public function reports(): View
    {
        $monthlyRequests = RadiologyRequest::selectRaw('MONTH(request_date) as month, COUNT(*) as count')
            ->whereYear('request_date', now()->year)
            ->groupBy('month')
            ->get();

        $testStats = RadiologyTest::withCount('requests')
            ->orderBy('requests_count', 'desc')
            ->take(10)
            ->get();

        $statusStats = RadiologyRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('hms.radiology.reports', compact('monthlyRequests', 'testStats', 'statusStats'));
    }
}


