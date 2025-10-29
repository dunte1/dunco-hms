<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HrDocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $documents = Document::with(['employee', 'documentType'])
            ->whereNotNull('employee_id')
            ->latest()
            ->paginate(15);

        return view('hms.hr.documents.index', compact('documents'));
    }

    /**
     * Display document types management page.
     */
    public function types(): View
    {
        $documentTypes = DocumentType::withCount('documents')
            ->latest()
            ->paginate(15);

        return view('hms.hr.documents.types', compact('documentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document_type_id' => 'required|exists:document_types,id',
            'employee_id' => 'required|exists:employees,id',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents/employee', $fileName, 'public');

            // Generate document number
            $documentNumber = 'EMP-DOC-' . str_pad(Document::count() + 1, 6, '0', STR_PAD_LEFT);

            Document::create([
                'document_number' => $documentNumber,
                'employee_id' => $validated['employee_id'],
                'document_type_id' => $validated['document_type_id'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'status' => 'active',
            ]);

            return redirect()->route('hms.hr.documents.index')
                ->with('success', 'Document uploaded successfully.');
        }

        return back()->with('error', 'File upload failed.');
    }
}
