<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Services\DhaService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DhaIntegrationController extends Controller
{
    public function __construct(protected DhaService $dha)
    {
    }

    public function index(): View
    {
        return view('integration.dha.index', [
            'configured' => $this->dha->isConfigured(),
            'environment' => config('dha.env'),
            'facilityId' => config('dha.facility_id'),
            'baseUrl' => $this->dha->baseUrl(),
        ]);
    }

    public function searchClientRegistry(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'identifier_type' => 'nullable|string|in:ID,PASSPORT,BIOMETRIC_REF,CR_ID',
        ]);

        $result = $this->dha->searchClientRegistry(
            $request->input('identifier'),
            $request->input('identifier_type', 'ID')
        );

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message'] ?? ($result['success'] ? 'Client Registry lookup completed.' : 'Client Registry lookup failed.')
        )->with('cr_result', $result);
    }

    public function verifyPatient(Request $request)
    {
        $request->validate([
            'digital_id' => 'required|string',
            'digital_id_type' => 'nullable|string|in:ID,PASSPORT,BIOMETRIC_REF',
        ]);

        $result = $this->dha->verifyPatient(
            $request->input('digital_id'),
            $request->input('digital_id_type', 'ID')
        );

        if ($request->has('patient_id')) {
            $patient = Patient::find($request->input('patient_id'));
            if ($patient && !empty($result['cr_id'])) {
                $patient->update([
                    'dha_cr_id' => $result['cr_id'],
                    'dha_verified_at' => now(),
                ]);
            }
        }

        return back()->with(
            $result['verified'] ? 'success' : 'error',
            $result['message'] ?? ($result['verified'] ? 'Patient verified via DHA.' : 'Patient verification failed.')
        )->with('verify_result', $result);
    }

    public function lookupFacility(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $result = $this->dha->getFacility();

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message'] ?? ($result['success'] ? 'Facility Registry lookup completed.' : 'Facility Registry lookup failed.')
        )->with('facility_result', $result);
    }

    public function searchProvider(Request $request)
    {
        $request->validate(['identifier' => 'required|string']);

        $result = $this->dha->searchProviderRegistry($request->input('identifier'));

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message'] ?? ($result['success'] ? 'Provider Registry search completed.' : 'Provider Registry search failed.')
        )->with('provider_result', $result);
    }

    public function transmitDocument(Request $request)
    {
        $request->validate(['document_id' => 'required|integer']);

        $document = [
            'document_id' => $request->input('document_id'),
            'facility_id' => config('dha.facility_id'),
            'submitted_at' => now()->toIso8601String(),
        ];

        $result = $this->dha->transmitClinicalDocument($document);

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message'] ?? ($result['success'] ? 'Document transmitted via Afyalink.' : 'Document transmission failed.')
        )->with('document_result', $result);
    }

    public function verifyBiometric(Request $request)
    {
        $request->validate([
            'biometric_template' => 'required|string',
            'biometric_type' => 'nullable|string|in:fingerprint,face,iris,voice',
            'national_id' => 'nullable|string',
        ]);

        $result = $this->dha->verifyBiometric(
            $request->input('biometric_template'),
            $request->input('biometric_type', 'fingerprint'),
            $request->input('national_id')
        );

        return back()->with(
            (!empty($result['verified'])) ? 'success' : 'error',
            $result['message'] ?? 'Biometric verification completed.'
        )->with('biometric_result', $result);
    }
}