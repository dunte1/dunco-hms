<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetTransfer;
use App\Models\AssetDisposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(): View
    {
        $assets = Asset::with('department')
            ->orderByDesc('created_at')
            ->paginate(20);

        $stats = [
            'total' => Asset::count(),
            'active' => Asset::where('status', 'active')->count(),
            'transferred' => Asset::where('status', 'transferred')->count(),
            'disposed' => Asset::where('status', 'disposed')->count(),
        ];

        return view('hms.assets.index', compact('assets', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'required|in:medical,office,it,furniture,vehicle,other',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'location' => 'nullable|string',
            'department_id' => 'nullable|exists:employee_departments,id',
            'serial_number' => 'nullable|string',
            'model_number' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'warranty_expiry' => 'nullable|date',
        ]);

        $data['asset_number'] = 'AST-' . str_pad(Asset::count() + 1, 6, '0', STR_PAD_LEFT);
        $data['status'] = 'active';

        Asset::create($data);

        return back()->with('status', 'Asset registered');
    }

    public function transfer(Request $request, Asset $asset): RedirectResponse
    {
        $data = $request->validate([
            'to_location' => 'required|string',
            'to_department_id' => 'nullable|exists:employee_departments,id',
            'reason' => 'required|string',
        ]);

        AssetTransfer::create([
            'asset_id' => $asset->id,
            'from_location' => $asset->location,
            'from_department_id' => $asset->department_id,
            'to_location' => $data['to_location'],
            'to_department_id' => $data['to_department_id'],
            'transfer_date' => now()->toDateString(),
            'reason' => $data['reason'],
            'transferred_by' => auth()->id(),
        ]);

        $asset->update([
            'location' => $data['to_location'],
            'department_id' => $data['to_department_id'] ?? $asset->department_id,
            'status' => 'transferred',
        ]);

        return back()->with('status', 'Asset transferred');
    }

    public function dispose(Request $request, Asset $asset): RedirectResponse
    {
        $data = $request->validate([
            'disposal_method' => 'required|in:sold,donated,recycled,trashed',
            'disposal_value' => 'nullable|numeric|min:0',
            'reason' => 'required|string',
        ]);

        AssetDisposal::create([
            'asset_id' => $asset->id,
            'disposal_date' => now()->toDateString(),
            'disposal_method' => $data['disposal_method'],
            'disposal_value' => $data['disposal_value'] ?? null,
            'approved_by' => auth()->id(),
            'reason' => $data['reason'],
        ]);

        $asset->update(['status' => 'disposed']);

        return back()->with('status', 'Asset disposed');
    }
}
