<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Show OTP verification form
     */
    public function showForm(Request $request): View
    {
        $email = $request->query('email');
        $user = $email ? User::where('email', $email)->first() : null;

        return view('auth.otp-verify', ['user' => $user, 'email' => $email]);
    }

    /**
     * Send OTP code to user's email
     */
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return redirect()->route('login')
                ->with('status', 'Your email is already verified. Please log in.');
        }

        $code = $user->generateVerificationCode();

        // Send OTP via email
        Mail::raw("Your DuncoHMS verification code is: {$code}\n\nThis code expires in 10 minutes.\n\nIf you did not request this code, please ignore this email.", function ($message) use ($user, $code) {
            $message->to($user->email)
                    ->subject("DuncoHMS Verification Code: {$code}")
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });

        return redirect()->route('otp.verify.form', ['email' => $user->email])
            ->with('success', "Verification code sent to {$user->email}");
    }

    /**
     * Verify the OTP code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return redirect()->route('login')
                ->with('status', 'Your email is already verified. Please log in.');
        }

        if ($user->isCodeExpired()) {
            return back()->withErrors(['code' => 'Verification code has expired. Please request a new one.']);
        }

        if ($user->verifyCode($request->code)) {
            return redirect()->route('login')
                ->with('success', 'Email verified successfully! You can now log in.');
        }

        return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
    }

    /**
     * Resend OTP code
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return redirect()->route('login')
                ->with('status', 'Your email is already verified. Please log in.');
        }

        $code = $user->generateVerificationCode();

        Mail::raw("Your DuncoHMS verification code is: {$code}\n\nThis code expires in 10 minutes.\n\nIf you did not request this code, please ignore this email.", function ($message) use ($user, $code) {
            $message->to($user->email)
                    ->subject("DuncoHMS Verification Code: {$code}")
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });

        return back()->with('success', "New verification code sent to {$user->email}");
    }
}
