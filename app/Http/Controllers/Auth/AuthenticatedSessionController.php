<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Check if user is already authenticated
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
        $request->authenticate();

        $request->session()->regenerate();

            // Log successful login
            Log::info('User logged in successfully', [
                'user_id' => Auth::id(),
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            // Skip email verification check for development/testing
            // For production, you may want to re-enable this check
            // Check if user needs to verify email (only in production)
            if (config('app.env') === 'production' && !Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // Auto-verify email in development/testing environment
            if (config('app.env') !== 'production' && !Auth::user()->hasVerifiedEmail()) {
                Auth::user()->markEmailAsVerified();
            }

            // Redirect based on user role or intended URL
            $intended = $request->session()->pull('url.intended', route('dashboard'));
            
            return redirect()->intended($intended);
            
        } catch (ValidationException $e) {
            // Log failed login attempt
            Log::warning('Failed login attempt', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
                'errors' => $e->errors()
            ]);
            
            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userId = Auth::id();
        $userEmail = Auth::user()?->email;
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log logout
        Log::info('User logged out', [
            'user_id' => $userId,
            'email' => $userEmail,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        return redirect('/');
    }

    /**
     * Handle social login redirect
     */
    public function socialLogin(string $provider): RedirectResponse
    {
        $allowedProviders = ['google', 'facebook', 'twitter', 'linkedin', 'github'];
        
        if (!in_array($provider, $allowedProviders)) {
            return redirect()->route('login')->with('error', 'Unsupported login provider.');
        }

        try {
            // Check if Laravel Socialite is available
            if (!class_exists('Laravel\Socialite\Facades\Socialite')) {
                return redirect()->route('login')->with('error', 'Social login package not installed. Install Laravel Socialite: composer require laravel/socialite');
            }

            // Redirect to provider's OAuth page
            return \Laravel\Socialite\Facades\Socialite::driver($provider)
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Social login redirect failed', [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Unable to initiate ' . ucfirst($provider) . ' login. Please configure your credentials.');
        }
    }

    /**
     * Handle social login callback
     */
    public function socialCallback(string $provider): RedirectResponse
    {
        $allowedProviders = ['google', 'facebook', 'twitter', 'linkedin', 'github'];
        
        if (!in_array($provider, $allowedProviders)) {
            return redirect()->route('login')->with('error', 'Unsupported login provider.');
        }

        try {
            if (!class_exists('Laravel\Socialite\Facades\Socialite')) {
                return redirect()->route('login')->with('error', 'Social login package not installed.');
            }

            $socialUser = \Laravel\Socialite\Facades\Socialite::driver($provider)->user();
            
            // Find or create user
            $user = \App\Models\User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'email_verified_at' => now(),
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                ]
            );

            // Update user avatar if available
            if ($socialUser->getAvatar() && !$user->avatar) {
                $user->avatar = $socialUser->getAvatar();
                $user->save();
            }

            \Illuminate\Support\Facades\Auth::login($user, true);

            session()->regenerate();

            return redirect()->intended(\Illuminate\Support\Facades\Route::has('dashboard') ? route('dashboard') : '/');
            
        } catch (\Exception $e) {
            \Log::error('Social login callback failed', [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Login with ' . ucfirst($provider) . ' failed. Please try again or use email login.');
        }
    }
}
