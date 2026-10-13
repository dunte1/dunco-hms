<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders(): void
    {
        $response = $this->get('/login');
        $response->assertOk();
        $this->assertIsHtmlPage($response);
        $this->assertStringContainsString('login', strtolower($response->getContent()));
    }

    public function test_valid_credentials_login_successfully(): void
    {
        $user = User::factory()->create([
            'password' => 'secret-password',
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret-password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $user = User::factory()->create([
            'password' => 'secret-password',
            'email_verified_at' => now(),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout_destroys_session(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user);
        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_unauthenticated_user_is_redirected_from_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}
