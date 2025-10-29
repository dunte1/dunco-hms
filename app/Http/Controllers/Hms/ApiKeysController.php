<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApiKeysController extends Controller
{
    public function index(): View
    {
        $tokens = ApiToken::latest()->get();
        return view('hms.settings.api-keys', compact('tokens'));
    }
    
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'nullable|array',
            'expires_at' => 'nullable|date|after:today',
        ]);
        
        // Generate a secure random token
        $token = bin2hex(random_bytes(32));
        
        $apiToken = ApiToken::create([
            'name' => $validated['name'],
            'token' => $token,
            'abilities' => $validated['abilities'] ?? ['*'],
            'expires_at' => $validated['expires_at'] ?? now()->addYear(),
        ]);
        
        return redirect()->route('hms.system.api-keys')
            ->with('success', 'API key created successfully! Token: ' . $token)
            ->with('new_token', $token);
    }
    
    public function destroy(ApiToken $apiKey): RedirectResponse
    {
        $name = $apiKey->name;
        $apiKey->delete();
        
        return redirect()->route('hms.system.api-keys')
            ->with('success', "API key '{$name}' revoked successfully.");
    }
}
