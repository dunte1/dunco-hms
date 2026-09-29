<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ReferringFacility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferringFacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = ReferringFacility::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('county', 'like', "%{$search}%")
                  ->orWhere('facility_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('county')) {
            $query->where('county', $request->county);
        }

        $facilities = $query->orderBy('name')->paginate(15)->withQueryString();

        return response()->json($facilities);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'facility_code' => 'nullable|string|max:50',
            'county' => 'required|string|max:100',
            'sub_county' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:200',
            'contact_person' => 'nullable|string|max:200',
        ]);

        ReferringFacility::create($data);

        return back()->with('success', 'Referring facility created successfully!');
    }
}
