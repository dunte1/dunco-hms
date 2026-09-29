<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ScannedDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ScannedDocumentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'mrd_file_id' => 'nullable|exists:mrd_files,id',
            'document_type' => 'required|in:discharge_summary,lab_report,imaging,consent,legal,other',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('scanned-documents', 'public');

        ScannedDocument::create([
            'patient_id' => $data['patient_id'],
            'mrd_file_id' => $data['mrd_file_id'] ?? null,
            'document_type' => $data['document_type'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('status', 'Document uploaded successfully');
    }
}
