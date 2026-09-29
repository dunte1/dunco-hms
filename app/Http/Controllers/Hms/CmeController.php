<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CmeRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmeController extends Controller
{
    public function index(Request $request): View
    {
        $query = CmeRecord::with('doctor');

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $records = $query->latest()->paginate(15);

        return view('hms.credentialing.cme', compact('records'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'title' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'credits_earned' => 'required|numeric|min:0|max:100',
            'category' => 'required|in:clinical,ethics,quality,safety,admin',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'status' => 'nullable|in:pending,completed',
        ]);

        $data['status'] = $data['status'] ?? 'pending';

        CmeRecord::create($data);

        return redirect()->route('hms.credentialing.cme.index')
            ->with('success', 'CME record created successfully!');
    }
}
