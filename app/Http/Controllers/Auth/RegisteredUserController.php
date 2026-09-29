<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate and send OTP
        $code = $user->generateVerificationCode();
        \Illuminate\Support\Facades\Mail::raw("Your DuncoHMS verification code is: {$code}\n\nThis code expires in 10 minutes.", function ($message) use ($user, $code) {
            $message->to($user->email)
                    ->subject("DuncoHMS Verification Code: {$code}")
                    ->from(config('mail.from.address'), config('mail.from.name'));
        });

        event(new Registered($user));

        return redirect()->route('otp.verify.form', ['email' => $user->email])
            ->with('success', 'Registration successful! Please check your email for the verification code.');
    }
}
