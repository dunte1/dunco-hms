<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SoftwareLicence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SoftwareLicenceController extends Controller
{
    public function index(): JsonResponse
    {
        $licences = SoftwareLicence::orderByDesc('created_at')->paginate(20);

        return response()->json($licences);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'software_name' => 'required|string|max:255',
            'licence_key' => 'nullable|string',
            'licence_type' => 'required|in:single,concurrent,site,subscription',
            'max_seats' => 'required|integer|min:1',
            'purchase_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'cost' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,expired,unused',
        ]);

        $data['current_seats'] = 0;
        $data['status'] ??= 'unused';

        SoftwareLicence::create($data);

        return back()->with('status', 'Software licence registered');
    }
}
