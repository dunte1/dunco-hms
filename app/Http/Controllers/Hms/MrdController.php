<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MrdFile;
use App\Models\MrdFileMovement;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MrdController extends Controller
{
    public function index(): View
    {
        $query = MrdFile::with('patient');
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('file_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', fn($pq) => $pq->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
            });
        }
        if (request('status')) {
            $query->where('status', request('status'));
        }
        $files = $query->orderByDesc('created_at')->paginate(20);
        return view('hms.mrd.index', compact('files'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'file_type' => 'required|in:discharge_summary,lab_report,imaging,consent,operation_note,correspondence,other',
            'physical_location' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $data['file_number'] = MrdFile::generateFileNumber();
        $data['status'] = 'in_library';
        MrdFile::create($data);
        return back()->with('status', 'MRD file created');
    }

    public function show(MrdFile $file): View
    {
        $file->load(['patient', 'movements.performedByUser']);
        return view('hms.mrd.show', compact('file'));
    }

    public function issue(MrdFile $file): RedirectResponse
    {
        $file->update(['status' => 'issued']);
        MrdFileMovement::create([
            'mrd_file_id' => $file->id,
            'action' => 'issued',
            'performed_by' => auth()->id(),
            'from_location' => $file->physical_location,
            'issued_at' => now(),
        ]);
        return back()->with('status', 'File issued');
    }

    public function return(MrdFile $file): RedirectResponse
    {
        $file->update(['status' => 'returned']);
        MrdFileMovement::create([
            'mrd_file_id' => $file->id,
            'action' => 'returned',
            'performed_by' => auth()->id(),
            'returned_at' => now(),
        ]);
        return back()->with('status', 'File returned');
    }
}
