<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReferralDocumentController extends Controller
{
    public function store(Request $request, Referral $referral): RedirectResponse
    {
        $data = $request->validate([
            'document_type' => 'required|in:referral_letter,clinical_summary,lab_results,imaging,other',
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('referral-documents/' . $referral->id, 'public');

        $referral->documents()->create([
            'document_type' => $data['document_type'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'uploaded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Referral document uploaded successfully!');
    }
}
