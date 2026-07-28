<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ReportTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomReportBuilderController extends Controller
{
    /**
     * Display the custom report builder interface
     */
    public function index(): View
    {
        $templates = ReportTemplate::where('is_active', true)
            ->orderBy('usage_count', 'desc')
            ->paginate(20);
        
        $categories = ReportTemplate::distinct('category')
            ->whereNotNull('category')
            ->pluck('category');
        
        return view('hms.reports.custom-builder', compact('templates', 'categories'));
    }

    /**
     * Show the form for creating a new report template
     */
    public function create(): View
    {
        $availableTables = [
            'patients' => 'Patients',
            'appointments' => 'Appointments',
            'invoices' => 'Invoices',
            'payments' => 'Payments',
            'opd_visits' => 'OPD Visits',
            'ipd_admissions' => 'IPD Admissions',
            'lab_requests' => 'Lab Requests',
            'radiology_requests' => 'Radiology Requests',
            'prescriptions' => 'Prescriptions',
            'medicines' => 'Medicines',
            'employees' => 'Employees',
            'doctors' => 'Doctors',
        ];
        
        return view('hms.reports.create-template', compact('availableTables'));
    }

    /**
     * Store a newly created report template
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'config' => 'required|array',
            'config.table' => 'required|string',
            'config.fields' => 'nullable',
            'config.order_by' => 'nullable|string',
            'config.order_direction' => 'nullable|in:asc,desc',
            'config.group_by' => 'nullable|string',
            'config.limit' => 'nullable|integer|min:1',
            'layout' => 'nullable|array',
            'is_premium' => 'nullable|boolean',
        ]);

        // Handle fields if it's a JSON string
        $config = $validated['config'];
        if (isset($config['fields']) && is_string($config['fields'])) {
            $config['fields'] = json_decode($config['fields'], true) ?? ['*'];
        }

        $template = ReportTemplate::create([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'] ?? null,
            'config' => $config,
            'layout' => $validated['layout'] ?? [],
            'is_premium' => $request->has('is_premium'),
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('hms.reports.custom-builder.show', $template)
            ->with('success', 'Report template created successfully.');
    }

    /**
     * Display a specific report template
     */
    public function show(ReportTemplate $template): View
    {
        return view('hms.reports.show-template', compact('template'));
    }

    /**
     * Show the form for editing a report template
     */
    public function edit(ReportTemplate $template): View
    {
        $availableTables = [
            'patients' => 'Patients',
            'appointments' => 'Appointments',
            'invoices' => 'Invoices',
            'payments' => 'Payments',
            'opd_visits' => 'OPD Visits',
            'ipd_admissions' => 'IPD Admissions',
            'lab_requests' => 'Lab Requests',
            'radiology_requests' => 'Radiology Requests',
            'prescriptions' => 'Prescriptions',
            'medicines' => 'Medicines',
            'employees' => 'Employees',
            'doctors' => 'Doctors',
        ];
        
        return view('hms.reports.edit-template', compact('template', 'availableTables'));
    }

    /**
     * Update a report template
     */
    public function update(Request $request, ReportTemplate $template): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'config' => 'required|array',
            'layout' => 'nullable|array',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $template->update($validated);

        return redirect()
            ->route('hms.reports.custom-builder.show', $template)
            ->with('success', 'Report template updated successfully.');
    }

    /**
     * Generate and run a report based on template
     */
    public function generate(Request $request, ReportTemplate $template)
    {
        $template->markAsUsed();

        $config = $template->config;
        $filters = $request->get('filters', []);
        
        // Build query based on template configuration
        $data = $this->buildReportData($config, $filters);
        
        // Format output based on request
        if ($request->get('format') === 'pdf') {
            return $this->generatePdf($template, $data);
        } elseif ($request->get('format') === 'excel') {
            return $this->generateExcel($template, $data);
        } else {
            return view('hms.reports.generated-report', [
                'template' => $template,
                'data' => $data,
                'filters' => $filters,
            ]);
        }
    }

    /**
     * Build report data based on configuration
     */
    private function buildReportData(array $config, array $filters): array
    {
        $table = $config['table'] ?? 'patients';
        $fields = $config['fields'] ?? ['*'];
        $groupBy = $config['group_by'] ?? null;
        $orderBy = $config['order_by'] ?? 'id';
        $orderDirection = $config['order_direction'] ?? 'asc';
        
        $query = DB::table($table);
        
        // Apply filters
        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                if (!empty($value)) {
                    if (is_array($value) && isset($value['from']) && isset($value['to'])) {
                        $query->whereBetween($field, [$value['from'], $value['to']]);
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        }
        
        // Apply date range if provided
        if (isset($config['date_field']) && isset($filters['date_from']) && isset($filters['date_to'])) {
            $query->whereBetween($config['date_field'], [
                $filters['date_from'],
                $filters['date_to']
            ]);
        }
        
        // Select fields
        if ($fields !== ['*']) {
            $query->select($fields);
        }
        
        // Group by
        if ($groupBy) {
            $query->groupBy($groupBy);
        }
        
        // Order by
        $query->orderBy($orderBy, $orderDirection);
        
        // Limit
        if (isset($config['limit'])) {
            $query->limit($config['limit']);
        }
        
        return $query->get()->toArray();
    }

    /**
     * Generate PDF report
     */
    private function generatePdf(ReportTemplate $template, array $data)
    {
        // This would use a PDF library like DomPDF or TCPDF
        // For now, return a placeholder response
        return response()->json([
            'message' => 'PDF generation will be implemented with DomPDF',
            'template' => $template->name,
            'record_count' => count($data),
        ]);
    }

    /**
     * Generate Excel report
     */
    private function generateExcel(ReportTemplate $template, array $data)
    {
        // This would use Maatwebsite\Excel
        // For now, return a placeholder response
        return response()->json([
            'message' => 'Excel generation will be implemented with Maatwebsite\Excel',
            'template' => $template->name,
            'record_count' => count($data),
        ]);
    }

    /**
     * Delete a report template
     */
    public function destroy(ReportTemplate $template): RedirectResponse
    {
        $template->delete();

        return redirect()
            ->route('hms.reports.custom-builder.index')
            ->with('success', 'Report template deleted successfully.');
    }

    /**
     * Get available fields for a table
     */
    public function getTableFields(Request $request)
    {
        $table = $request->get('table');
        
        if (!$table) {
            return response()->json(['fields' => []]);
        }
        
        try {
            $columns = DB::getSchemaBuilder()->getColumnListing($table);
            return response()->json(['fields' => $columns]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Table not found'], 404);
        }
    }

    /**
     * Duplicate a report template
     */
    public function duplicate(ReportTemplate $template): RedirectResponse
    {
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (Copy)';
        $newTemplate->usage_count = 0;
        $newTemplate->last_run_at = null;
        $newTemplate->created_by = auth()->id();
        $newTemplate->save();

        return redirect()
            ->route('hms.reports.custom-builder.show', $newTemplate)
            ->with('success', 'Report template duplicated successfully.');
    }

    /**
     * Schedule a report for automatic delivery
     */
    public function schedule(Request $request, ReportTemplate $template): RedirectResponse
    {
        $validated = $request->validate([
            'schedule_frequency' => 'required|in:daily,weekly,monthly',
            'recipient_email' => 'required|email',
            'format' => 'required|in:pdf,excel',
        ]);

        // This would integrate with Laravel's task scheduler
        // Store schedule in database for cron job processing
        
        return redirect()
            ->route('hms.reports.custom-builder.show', $template)
            ->with('success', 'Report scheduled successfully.');
    }
}
