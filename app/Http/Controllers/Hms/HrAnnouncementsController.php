<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\HrAnnouncement;
use App\Models\EmployeeDepartment;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class HrAnnouncementsController extends Controller
{
    /**
     * Display a listing of HR announcements.
     */
    public function index(Request $request): View
    {
        $query = HrAnnouncement::with(['department', 'designation', 'creator']);
        
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
            } else {
                $query->where('is_active', false);
            }
        }
        
        $announcements = $query->latest()->paginate(15);
        
        return view('hms.hr.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create(): View
    {
        $departments = EmployeeDepartment::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        return view('hms.hr.announcements.create', compact('departments', 'designations', 'employees'));
    }

    /**
     * Store a newly created announcement.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'required|in:all,department,designation,specific',
            'department_id' => 'required_if:target_audience,department|nullable|exists:employee_departments,id',
            'designation_id' => 'required_if:target_audience,designation|nullable|exists:designations,id',
            'target_employee_ids' => 'required_if:target_audience,specific|nullable|array',
            'target_employee_ids.*' => 'exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $validated['created_by'] = auth()->id();
        
        if ($validated['target_audience'] === 'specific' && isset($validated['target_employee_ids'])) {
            $validated['target_employee_ids'] = json_encode($validated['target_employee_ids']);
        } else {
            $validated['target_employee_ids'] = null;
        }

        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = 'announcement_' . time() . '_' . $attachment->getClientOriginalName();
            $attachmentPath = $attachment->storeAs('hr/announcements', $attachmentName, 'public');
            $validated['attachment_path'] = $attachmentPath;
        }

        HrAnnouncement::create($validated);

        return redirect()->route('hms.hr.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified announcement.
     */
    public function show(HrAnnouncement $announcement): View
    {
        $announcement->load(['department', 'designation', 'creator']);
        
        $targetEmployees = null;
        if ($announcement->target_audience === 'specific' && $announcement->target_employee_ids) {
            $targetEmployees = Employee::whereIn('id', json_decode($announcement->target_employee_ids))->get();
        }
        
        return view('hms.hr.announcements.show', compact('announcement', 'targetEmployees'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(HrAnnouncement $announcement): View
    {
        $departments = EmployeeDepartment::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        return view('hms.hr.announcements.edit', compact('announcement', 'departments', 'designations', 'employees'));
    }

    /**
     * Update the specified announcement.
     */
    public function update(Request $request, HrAnnouncement $announcement): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'required|in:all,department,designation,specific',
            'department_id' => 'required_if:target_audience,department|nullable|exists:employee_departments,id',
            'designation_id' => 'required_if:target_audience,designation|nullable|exists:designations,id',
            'target_employee_ids' => 'required_if:target_audience,specific|nullable|array',
            'target_employee_ids.*' => 'exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        if ($validated['target_audience'] === 'specific' && isset($validated['target_employee_ids'])) {
            $validated['target_employee_ids'] = json_encode($validated['target_employee_ids']);
        } else {
            $validated['target_employee_ids'] = null;
        }

        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($announcement->attachment_path && Storage::disk('public')->exists($announcement->attachment_path)) {
                Storage::disk('public')->delete($announcement->attachment_path);
            }
            
            $attachment = $request->file('attachment');
            $attachmentName = 'announcement_' . time() . '_' . $attachment->getClientOriginalName();
            $attachmentPath = $attachment->storeAs('hr/announcements', $attachmentName, 'public');
            $validated['attachment_path'] = $attachmentPath;
        }

        $announcement->update($validated);

        return redirect()->route('hms.hr.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(HrAnnouncement $announcement): RedirectResponse
    {
        // Delete attachment if exists
        if ($announcement->attachment_path && Storage::disk('public')->exists($announcement->attachment_path)) {
            Storage::disk('public')->delete($announcement->attachment_path);
        }
        
        $announcement->delete();

        return redirect()->route('hms.hr.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
