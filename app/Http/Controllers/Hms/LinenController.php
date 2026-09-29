<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LinenRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LinenController extends Controller
{
    public function index(): JsonResponse
    {
        $records = LinenRecord::with(['ward', 'assignedTo'])->orderByDesc('created_at')->paginate(20);
        return response()->json($records);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'linen_type' => 'required|in:bed_sheets,pillows,gowns,towels,other',
            'quantity' => 'required|integer|min:1',
            'ward_id' => 'nullable|exists:wards,id',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $data['status'] = 'soiled';
        $data['issue_date'] = now()->toDateString();

        LinenRecord::create($data);

        return back()->with('status', 'Linen issued successfully');
    }

    public function returnLinen(Request $request, LinenRecord $record): RedirectResponse
    {
        $record->update([
            'status' => 'clean',
            'return_date' => now()->toDateString(),
        ]);

        return back()->with('status', 'Linen returned successfully');
    }
}
