<?php

namespace App\Http\Controllers\Rfid;

use App\Http\Controllers\Controller;
use App\Models\RfidTag;
use App\Models\Patient;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class RfidController extends Controller
{
    public function index(): View
    {
        $tags = RfidTag::with(['patient', 'employee'])
            ->latest()
            ->paginate(20);
        
        return view('hms.rfid.index', compact('tags'));
    }

    public function create(): View
    {
        $patients = Patient::latest()->get();
        $employees = Employee::latest()->get();
        
        return view('hms.rfid.create', compact('patients', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tag_id' => 'required|string|unique:rfid_tags',
            'tag_type' => 'required|string|in:patient,staff,equipment,visitor',
            'patient_id' => 'nullable|exists:patients,id',
            'employee_id' => 'nullable|exists:employees,id',
            'associated_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        // Validate that either patient_id or employee_id is provided for patient/staff tags
        if (in_array($data['tag_type'], ['patient', 'staff'])) {
            if ($data['tag_type'] === 'patient' && !$data['patient_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient ID is required for patient tags'
                ], 400);
            }
            
            if ($data['tag_type'] === 'staff' && !$data['employee_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee ID is required for staff tags'
                ], 400);
            }
        }

        $tag = RfidTag::create($data);

        return response()->json([
            'success' => true,
            'data' => $tag,
            'message' => 'RFID tag created successfully'
        ], 201);
    }

    public function scan(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tag_id' => 'required|string',
            'location' => 'required|string',
        ]);

        $tag = RfidTag::where('tag_id', $data['tag_id'])->first();

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'RFID tag not found'
            ], 404);
        }

        // Update tag location and last seen
        $tag->update([
            'last_seen' => now(),
            'last_location' => $data['location'],
        ]);

        // Load relationships
        $tag->load(['patient', 'employee']);

        return response()->json([
            'success' => true,
            'data' => $tag,
            'message' => 'RFID tag scanned successfully'
        ]);
    }

    public function getTagInfo(string $tagId): JsonResponse
    {
        $tag = RfidTag::with(['patient', 'employee'])
            ->where('tag_id', $tagId)
            ->first();

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'RFID tag not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tag
        ]);
    }

    public function updateStatus(Request $request, RfidTag $tag): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|string|in:active,inactive,lost,damaged',
            'notes' => 'nullable|string|max:500',
        ]);

        $tag->update($data);

        return response()->json([
            'success' => true,
            'data' => $tag,
            'message' => 'Tag status updated successfully'
        ]);
    }

    public function getLocationHistory(RfidTag $tag): JsonResponse
    {
        // In a real implementation, you would have a separate table for location history
        // For now, we'll return the current location info
        $history = [
            [
                'location' => $tag->last_location,
                'timestamp' => $tag->last_seen,
                'status' => $tag->status,
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    public function getActiveTags(): JsonResponse
    {
        $activeTags = RfidTag::with(['patient', 'employee'])
            ->where('status', 'active')
            ->where('last_seen', '>=', now()->subMinutes(30)) // Active within last 30 minutes
            ->get();

        return response()->json([
            'success' => true,
            'data' => $activeTags
        ]);
    }

    public function getTagsByLocation(string $location): JsonResponse
    {
        $tags = RfidTag::with(['patient', 'employee'])
            ->where('last_location', $location)
            ->where('last_seen', '>=', now()->subHours(1)) // Within last hour
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }

    public function generateReport(Request $request): JsonResponse
    {
        $data = $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'tag_type' => 'nullable|string|in:patient,staff,equipment,visitor',
        ]);

        $query = RfidTag::with(['patient', 'employee'])
            ->whereBetween('created_at', [$data['date_from'], $data['date_to']]);

        if ($data['tag_type']) {
            $query->where('tag_type', $data['tag_type']);
        }

        $tags = $query->get();

        $report = [
            'total_tags' => $tags->count(),
            'by_type' => $tags->groupBy('tag_type')->map->count(),
            'by_status' => $tags->groupBy('status')->map->count(),
            'recent_activity' => $tags->where('last_seen', '>=', now()->subHours(24))->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tag_ids' => 'required|array',
            'status' => 'required|string|in:active,inactive,lost,damaged',
            'notes' => 'nullable|string|max:500',
        ]);

        $updated = RfidTag::whereIn('id', $data['tag_ids'])
            ->update([
                'status' => $data['status'],
                'notes' => $data['notes'],
            ]);

        return response()->json([
            'success' => true,
            'updated_count' => $updated,
            'message' => 'Tags updated successfully'
        ]);
    }
}
