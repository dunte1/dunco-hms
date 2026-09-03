<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\ShaAuthorization;
use App\Models\ShaMember;
use App\Models\ShaProvider;
use App\Models\ShaServiceCode;
use App\Services\ShaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShaController extends Controller
{
    public function __construct(protected ShaService $shaService) {}

    public function index(): View
    {
        $members = ShaMember::with('patient')->latest()->paginate(15);
        $authorizations = ShaAuthorization::with('patient', 'shaMember')->latest()->take(10)->get();

        $stats = [
            'members' => ShaMember::count(),
            'verified' => ShaMember::where('eligibility_status', 'active')->count(),
            'authorizations' => ShaAuthorization::count(),
            'approved' => ShaAuthorization::where('status', 'approved')->count(),
            'pending' => ShaAuthorization::where('status', 'pending')->count(),
            'providers_configured' => ShaProvider::where('is_active', true)->count(),
        ];

        return view('hms.sha.index', compact('members', 'authorizations', 'stats'));
    }

    public function members(): View
    {
        $members = ShaMember::with('patient')->latest()->paginate(15);
        return view('hms.sha.members', compact('members'));
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['member_number' => 'required|string']);

        $result = $this->shaService->verifyMember($request->member_number);

        if ($result['verified']) {
            return redirect()->back()->with('success', 'SHA member verified successfully.')->with('sha_result', $result);
        }

        return redirect()->back()->withErrors(['member_number' => $result['message']]);
    }

    public function storeMember(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'sha_member_number' => 'required|string|unique:sha_members,sha_member_number',
            'national_id' => 'nullable|string',
            'contributor_status' => 'nullable|in:active,inactive,deferred',
            'eligibility_status' => 'nullable|in:active,inactive',
        ]);

        $patient = Patient::findOrFail($data['patient_id']);

        $member = ShaMember::create([
            'patient_id' => $patient->id,
            'sha_member_number' => $data['sha_member_number'],
            'national_id' => $data['national_id'] ?? $patient->national_id ?? null,
            'first_name' => $patient->first_name,
            'last_name' => $patient->last_name,
            'date_of_birth' => $patient->date_of_birth ?? null,
            'gender' => $patient->gender ?? null,
            'phone' => $patient->phone ?? null,
            'contribution_status' => $data['contributor_status'] ?? 'active',
            'eligibility_status' => $data['eligibility_status'] ?? 'active',
            'last_verified_at' => now(),
        ]);

        return redirect()->route('hms.sha.member.show', $member)
            ->with('success', 'SHA member registered successfully.');
    }

    public function memberShow(ShaMember $member): View
    {
        $member->load('patient', 'authorizations');
        return view('hms.sha.member-show', compact('member'));
    }

    public function authorizations(): View
    {
        $authorizations = ShaAuthorization::with('patient', 'shaMember')->latest()->paginate(15);
        return view('hms.sha.authorizations', compact('authorizations'));
    }

    public function requestAuthorization(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'sha_member_id' => 'required|exists:sha_members,id',
            'service_type' => 'required|string',
            'service_code' => 'required|string',
            'diagnosis_code' => 'nullable|string',
            'estimated_amount' => 'nullable|numeric',
        ]);

        $service = ShaServiceCode::where('code', $data['service_code'])->first();
        $member = ShaMember::findOrFail($data['sha_member_id']);

        $result = $this->shaService->requestAuthorization([
            'member_number' => $member->sha_member_number,
            'service_code' => $data['service_code'],
            'diagnosis_code' => $data['diagnosis_code'] ?? null,
            'estimated_amount' => $data['estimated_amount'] ?? $service->tariff_amount ?? null,
        ]);

        $authorization = ShaAuthorization::create([
            'authorization_number' => $result['authorization_number'] ?? ('SHA-' . strtoupper(\Illuminate\Support\Str::random(10))),
            'patient_id' => $data['patient_id'],
            'sha_member_id' => $member->id,
            'service_type' => $data['service_type'],
            'service_code' => $data['service_code'],
            'diagnosis_code' => $data['diagnosis_code'] ?? null,
            'authorized_amount' => $result['authorized_amount'] ?? $data['estimated_amount'] ?? 0,
            'status' => $result['status'] ?? 'pending',
            'authorized_date' => $result['approved'] ?? false ? now() : null,
            'expiry_date' => isset($result['expiry']) ? \Carbon\Carbon::parse($result['expiry']) : now()->addDays(30),
            'api_response' => $result['api_response'] ?? null,
        ]);

        return redirect()->route('hms.sha.authorizations')
            ->with('success', 'Authorization ' . $authorization->authorization_number . ' requested.');
    }

    public function authorizationShow(ShaAuthorization $authorization): View
    {
        $authorization->load('patient', 'shaMember');
        return view('hms.sha.authorization-show', compact('authorization'));
    }

    public function providers(): View
    {
        $providers = ShaProvider::latest()->get();
        return view('hms.sha.providers', compact('providers'));
    }

    public function storeProvider(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:sha_providers,code',
            'facility_code' => 'required|string|unique:sha_providers,facility_code',
            'county' => 'nullable|string',
            'sub_county' => 'nullable|string',
            'tier_level' => 'nullable|string',
            'api_base_url' => 'nullable|url',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        ShaProvider::create(array_merge($data, ['is_active' => $request->boolean('is_active')]));

        return redirect()->route('hms.sha.providers')
            ->with('success', 'SHA provider created successfully.');
    }

    public function updateProvider(Request $request, ShaProvider $provider): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:sha_providers,code,' . $provider->id,
            'facility_code' => 'required|string|unique:sha_providers,facility_code,' . $provider->id,
            'county' => 'nullable|string',
            'sub_county' => 'nullable|string',
            'tier_level' => 'nullable|string',
            'api_base_url' => 'nullable|url',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $provider->update(array_merge($data, ['is_active' => $request->boolean('is_active')]));

        return redirect()->route('hms.sha.providers')
            ->with('success', 'SHA provider updated successfully.');
    }

    public function serviceCodes(): View
    {
        $services = ShaServiceCode::paginate(15);
        return view('hms.sha.service-codes', compact('services'));
    }
}
