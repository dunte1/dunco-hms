<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ItAsset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ItAssetController extends Controller
{
    public function index(): JsonResponse
    {
        $assets = ItAsset::with('assignedTo')->orderByDesc('created_at')->paginate(20);

        return response()->json($assets);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:desktop,laptop,printer,server,network_device,other',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'assigned_to_user_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:active,in_service,retired,disposed',
        ]);

        $data['asset_number'] = 'ICT-' . str_pad(ItAsset::count() + 1, 6, '0', STR_PAD_LEFT);
        $data['status'] ??= 'active';

        ItAsset::create($data);

        return back()->with('status', 'IT asset registered');
    }
}
