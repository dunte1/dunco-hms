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

            // Check if user needs to verify email
            if (!Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
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
        // This would integrate with Laravel Socialite
        // For now, redirect to login with a message
        return redirect()->route('login')->with('status', 'Social login coming soon!');
    }

    /**
     * Handle social login callback
     */
    public function socialCallback(string $provider): RedirectResponse
    {
        // This would handle the OAuth callback
        // For now, redirect to login
        return redirect()->route('login')->with('error', 'Social login not implemented yet.');
    }
}
