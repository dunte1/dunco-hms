<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\StaffDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffDocumentController extends Controller
{
    public function index(): View
    {
        $documents = StaffDocument::with(['employee', 'uploadedByUser'])
            ->latest()
            ->paginate(10);

        return view('hms.hr.staff-documents.index', compact('documents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'document_type' => 'required|in:contract,id_copy,qualification,licence,appraisal,policy,other',
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('staff-documents', $fileName, 'public');

        StaffDocument::create([
            'employee_id' => $data['employee_id'],
            'document_type' => $data['document_type'],
            'title' => $data['title'],
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('hms.hr.staff-documents.index')
            ->with('success', 'Staff document uploaded.');
    }
}
