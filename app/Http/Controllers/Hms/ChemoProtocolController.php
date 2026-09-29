<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ChemoProtocol;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChemoProtocolController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $protocols = ChemoProtocol::query()
            ->when($request->boolean('active_only'), fn ($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->paginate(20);

        return response()->json($protocols);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:chemo_protocols,code',
            'regimen' => 'required|string',
            'cycle_count' => 'required|integer|min:1',
            'cycle_days' => 'required|integer|min:1',
            'drugs' => 'required|array|min:1',
            'indication' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $data['is_active'] ?? true;

        ChemoProtocol::create($data);

        return redirect()->route('hms.oncology.protocols.index')
            ->with('status', 'Chemo protocol created.');
    }
}
