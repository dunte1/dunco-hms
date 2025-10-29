<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Marketing\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class SocialAccountController extends Controller
{
    public function index(): View
    {
        $accounts = SocialAccount::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('marketing.social-accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        return view('marketing.social-accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'platform' => 'required|in:facebook,instagram,twitter,linkedin,tiktok',
            'account_name' => 'required|string|max:255',
            'access_token' => 'nullable|string',
            'is_default' => 'nullable|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'active';

        if ($validated['is_default'] ?? false) {
            SocialAccount::where('user_id', auth()->id())
                ->where('platform', $validated['platform'])
                ->update(['is_default' => false]);
        }

        SocialAccount::create($validated);

        return redirect()->route('marketing.social-accounts.index')
            ->with('success', 'Social account added successfully.');
    }

    public function show(SocialAccount $socialAccount): View
    {
        $socialAccount->load(['scheduledPosts.marketingPost']);
        return view('marketing.social-accounts.show', compact('socialAccount'));
    }

    public function edit(SocialAccount $socialAccount): View
    {
        return view('marketing.social-accounts.edit', compact('socialAccount'));
    }

    public function update(Request $request, SocialAccount $socialAccount): RedirectResponse
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'access_token' => 'nullable|string',
            'status' => 'required|in:active,expired,revoked',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validated['is_default'] ?? false) {
            SocialAccount::where('user_id', auth()->id())
                ->where('platform', $socialAccount->platform)
                ->where('id', '!=', $socialAccount->id)
                ->update(['is_default' => false]);
        }

        $socialAccount->update($validated);

        return redirect()->route('marketing.social-accounts.index')
            ->with('success', 'Social account updated successfully.');
    }

    public function destroy(SocialAccount $socialAccount): RedirectResponse
    {
        $socialAccount->delete();
        return redirect()->route('marketing.social-accounts.index')
            ->with('success', 'Social account deleted successfully.');
    }

    public function connect(Request $request, string $platform): RedirectResponse
    {
        return Socialite::driver($platform)->redirect();
    }

    public function callback(Request $request, string $platform): RedirectResponse
    {
        try {
            $user = Socialite::driver($platform)->user();
            
            SocialAccount::updateOrCreate(
                [
                    'platform' => $platform,
                    'account_id' => $user->id,
                    'user_id' => auth()->id(),
                ],
                [
                    'account_name' => $user->name ?? $user->email,
                    'access_token' => $user->token,
                    'refresh_token' => $user->refreshToken ?? null,
                    'status' => 'active',
                    'metadata' => [
                        'email' => $user->email,
                        'avatar' => $user->avatar,
                    ],
                ]
            );

            return redirect()->route('marketing.social-accounts.index')
                ->with('success', ucfirst($platform) . ' account connected successfully.');
        } catch (\Exception $e) {
            return redirect()->route('marketing.social-accounts.index')
                ->with('error', 'Failed to connect account: ' . $e->getMessage());
        }
    }
}
