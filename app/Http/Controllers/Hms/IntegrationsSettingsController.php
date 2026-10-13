<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Services\DhaService;
use App\Services\IntegrationSettingsService;
use App\Services\MpesaService;
use App\Services\ShaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IntegrationsSettingsController extends Controller
{
    public function __construct(protected IntegrationSettingsService $settings) {}

    public function index(): View
    {
        return view('hms.settings.integrations', [
            'status' => $this->settings->status(),
            'mpesa' => $this->settings->get('mpesa'),
            'dha' => $this->settings->get('dha'),
            'eha' => $this->settings->get('eha-sha'),
        ]);
    }

    public function updateMpesa(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'environment' => 'required|in:sandbox,production',
            'consumer_key' => 'nullable|string|max:255',
            'consumer_secret' => 'nullable|string|max:255',
            'shortcode' => 'nullable|string|max:20',
            'passkey' => 'nullable|string|max:255',
            'business_name' => 'nullable|string|max:100',
            'callback_url' => 'nullable|url|max:500',
        ]);

        $current = $this->settings->get('mpesa');
        $merged = array_merge($current, array_filter($data, fn ($v) => $v !== null && $v !== ''));
        // Blank secrets keep existing values
        foreach (['consumer_key', 'consumer_secret', 'passkey'] as $secret) {
            if ($request->filled($secret) === false && filled($current[$secret] ?? null)) {
                $merged[$secret] = $current[$secret];
            }
        }

        $this->settings->put('mpesa', $merged);

        return redirect()->route('hms.settings.integrations')->with('success', 'M-Pesa settings saved.');
    }

    public function updateDha(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'env' => 'required|in:uat,production',
            'client_id' => 'nullable|string|max:255',
            'client_secret' => 'nullable|string|max:255',
            'facility_id' => 'nullable|string|max:100',
            'base_url' => 'nullable|url|max:500',
        ]);

        $current = $this->settings->get('dha');
        $merged = array_merge($current, array_filter($data, fn ($v) => $v !== null && $v !== ''));
        if (! $request->filled('client_secret') && filled($current['client_secret'] ?? null)) {
            $merged['client_secret'] = $current['client_secret'];
        }

        $this->settings->put('dha', $merged);

        return redirect()->route('hms.settings.integrations')->with('success', 'DHA settings saved.');
    }

    public function updateEha(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'env' => 'required|in:uat,production',
            'client_id' => 'nullable|string|max:255',
            'client_secret' => 'nullable|string|max:255',
            'facility_id' => 'nullable|string|max:100',
            'facility_id_type' => 'nullable|in:fr-code',
            'base_url' => 'nullable|url|max:500',
        ]);

        $current = $this->settings->get('eha-sha');
        $merged = array_merge($current, array_filter($data, fn ($v) => $v !== null && $v !== ''));
        if (! $request->filled('client_secret') && filled($current['client_secret'] ?? null)) {
            $merged['client_secret'] = $current['client_secret'];
        }

        $this->settings->put('eha-sha', $merged);

        return redirect()->route('hms.settings.integrations')->with('success', 'SHA / EHA settings saved.');
    }

    public function test(string $slug)
    {
        try {
            if ($slug === 'mpesa') {
                $svc = app(MpesaService::class);
                // Coverage is a light local check; gateway auth is the real test
                $configured = filled(config('mpesa.consumer_key')) && filled(config('mpesa.consumer_secret'));
                return response()->json([
                    'success' => $configured,
                    'message' => $configured
                        ? 'M-Pesa credentials present. STK push will use configured shortcode.'
                        : 'M-Pesa consumer key/secret missing.',
                ]);
            }

            if ($slug === 'dha') {
                $svc = app(DhaService::class);
                return response()->json([
                    'success' => $svc->isConfigured(),
                    'message' => $svc->isConfigured()
                        ? 'DHA client credentials configured (client_id + secret).'
                        : 'DHA client_id / client_secret missing.',
                ]);
            }

            if ($slug === 'eha-sha' || $slug === 'sha') {
                $svc = app(ShaService::class);
                return response()->json([
                    'success' => $svc->isConfigured(),
                    'message' => $svc->isConfigured()
                        ? 'EHA/SHA credentials configured.'
                        : 'EHA client_id / client_secret missing.',
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Unknown integration.'], 404);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
