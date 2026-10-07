<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_otp_form_is_rendered_for_unverified_user(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->get(route('otp.verify.form', ['email' => $user->email]));

        $response->assertOk();
        $response->assertSee($user->email);
    }

    public function test_send_otp_code_stores_verification_code_for_unverified_user(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->from(route('otp.verify.form', ['email' => $user->email]))
            ->post(route('otp.send'), ['email' => $user->email]);

        $response->assertRedirect(route('otp.verify.form', ['email' => $user->email]));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotNull($user->verification_code);
        $this->assertSame(6, strlen((string) $user->verification_code));
        $this->assertTrue($user->verification_code_expires_at->isFuture());
    }

    public function test_send_otp_skips_already_verified_users(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->post(route('otp.send'), ['email' => $user->email]);

        $response->assertRedirect(route('login'));
        Mail::assertNothingSent();
    }

    public function test_send_otp_rejects_unknown_email(): void
    {
        $response = $this->post(route('otp.send'), ['email' => 'missing@example.com']);

        $response->assertSessionHasErrors('email');
    }

    public function test_verify_otp_successfully_verifies_user(): void
    {
        $user = User::factory()->unverified()->create();
        $code = $user->generateVerificationCode();
        $this->assertNotNull($code);

        $response = $this->from(route('otp.verify.form', ['email' => $user->email]))
            ->post(route('otp.verify'), [
                'email' => $user->email,
                'code' => $code,
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $fresh = User::where('email', $user->email)->first();
        $this->assertNotNull($fresh->email_verified_at, 'User should be verified after correct OTP');
        $this->assertNull($fresh->verification_code);
    }

    public function test_verify_otp_rejects_invalid_code(): void
    {
        $user = User::factory()->unverified()->create();
        $user->generateVerificationCode();

        $response = $this->from(route('otp.verify.form', ['email' => $user->email]))
            ->post(route('otp.verify'), [
                'email' => $user->email,
                'code' => '000000',
            ]);

        $response->assertSessionHasErrors('code');
        $this->assertNull(User::where('email', $user->email)->value('email_verified_at'));
    }

    public function test_verify_otp_rejects_expired_code(): void
    {
        $user = User::factory()->unverified()->create();
        $code = $user->generateVerificationCode();
        User::whereKey($user->id)->update(['verification_code_expires_at' => now()->subMinute()]);

        $response = $this->from(route('otp.verify.form', ['email' => $user->email]))
            ->post(route('otp.verify'), [
                'email' => $user->email,
                'code' => $code,
            ]);

        $response->assertSessionHasErrors('code');
        $this->assertStringContainsString('expired', strtolower(implode(' ', session('errors')->all())));
    }

    public function test_resend_otp_issues_new_code(): void
    {
        $user = User::factory()->unverified()->create();
        $user->generateVerificationCode();

        $response = $this->from(route('otp.verify.form', ['email' => $user->email]))
            ->post(route('otp.resend'), ['email' => $user->email]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $fresh = User::where('email', $user->email)->first();
        $this->assertNotNull($fresh->verification_code);
        $this->assertTrue($fresh->verification_code_expires_at->isFuture());
    }

    public function test_verify_requires_six_digit_code(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->post(route('otp.verify'), [
            'email' => $user->email,
            'code' => '123',
        ]);

        $response->assertSessionHasErrors('code');
    }
}
