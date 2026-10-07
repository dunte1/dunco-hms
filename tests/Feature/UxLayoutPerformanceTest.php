<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\PatientPortalAccount;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UxLayoutPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'view dashboard analytics']);
        Permission::firstOrCreate(['name' => 'manage settings']);
    }

    public function test_system_setting_get_is_cached_and_invalidated_on_set(): void
    {
        Cache::flush();

        SystemSetting::set('system_name', 'Cache Test Hospital', 'string');
        $first = SystemSetting::get('system_name');
        $this->assertSame('Cache Test Hospital', $first);

        $cached = Cache::get('system_setting:system_name');
        $this->assertNotNull($cached);

        SystemSetting::set('system_name', 'Updated Hospital', 'string');
        $this->assertSame('Updated Hospital', SystemSetting::get('system_name'));
    }

    public function test_system_setting_returns_default_when_missing(): void
    {
        Cache::flush();
        $this->assertSame('Fallback', SystemSetting::get('does_not_exist_key_xyz', 'Fallback'));
    }

    public function test_dashboard_page_loads_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view dashboard analytics');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard');
    }

    public function test_dashboard_uses_grouped_aggregates_not_per_day_loops(): void
    {
        $source = file_get_contents(app_path('Http/Controllers/Hms/DashboardController.php'));

        $this->assertStringContainsString('groupBy', $source);
        $this->assertStringNotContainsString('Patient::whereDate(\'created_at\', $date)->count()', $source);
        $this->assertStringNotContainsString('Appointment::whereDate(\'scheduled_at\', $date)->count()', $source);
    }

    public function test_billing_payment_reports_paginates_payments(): void
    {
        $user = User::factory()->create();
        Permission::firstOrCreate(['name' => 'view billing']);
        $user->givePermissionTo('view billing');

        $response = $this->actingAs($user)->get('/hms/billing/payment-reports');
        $response->assertOk();

        $source = file_get_contents(app_path('Http/Controllers/Hms/BillingController.php'));
        $this->assertStringContainsString('paginate(20)', $source);
    }

    public function test_app_layout_includes_flash_messages_partial(): void
    {
        $source = file_get_contents(resource_path('views/components/app-layout.blade.php'));
        $this->assertStringContainsString('<x-flash />', $source);
        $this->assertStringContainsString('p-4 md:p-6', $source);
    }

    public function test_admin_layout_wires_sidebar_collapse_event(): void
    {
        $source = file_get_contents(resource_path('views/admin/layouts/app.blade.php'));
        $this->assertStringContainsString('@toggle-sidebar-collapse.window', $source);
        $this->assertStringContainsString('sidebarCollapsed', $source);
    }

    public function test_sidebar_mobile_touch_targets_are_large_enough(): void
    {
        $css = file_get_contents(public_path('css/sidebar.css'));
        $this->assertStringContainsString('min-height: 44px', $css);
        $this->assertStringContainsString('width: 100% !important', $css);
    }

    public function test_patient_portal_login_page_has_csrf_and_form_post(): void
    {
        $response = $this->get(route('patient-portal.login'));
        $response->assertOk();
        $response->assertSee('csrf-token');
        $response->assertSee('/patient-portal/login', false);
        $response->assertSee('name="username"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('<form method="POST"', false);
    }

    public function test_patient_portal_authenticate_supports_form_redirect(): void
    {
        $patient = Patient::factory()->create([
            'first_name' => 'Amina',
            'last_name' => 'Hassan',
        ]);

        $account = PatientPortalAccount::create([
            'patient_id' => $patient->id,
            'username' => 'amina.hassan',
            'email' => 'amina@example.com',
            'password_hash' => bcrypt('secret123'),
            'is_active' => true,
        ]);

        $response = $this->post(route('patient-portal.authenticate'), [
            'username' => 'amina.hassan',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('patient-portal.dashboard'));
        $this->assertSame($account->id, session('patient_portal_user'));
    }

    public function test_patient_portal_authenticate_rejects_invalid_credentials(): void
    {
        $response = $this->from(route('patient-portal.login'))->post(route('patient-portal.authenticate'), [
            'username' => 'missing-user',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('patient-portal.login'));
        $response->assertSessionHasErrors('username');
    }

    public function test_patient_portal_dashboard_requires_session_and_shows_user_name(): void
    {
        $patient = Patient::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Otieno',
        ]);

        $account = PatientPortalAccount::create([
            'patient_id' => $patient->id,
            'username' => 'john.otieno',
            'email' => 'john@example.com',
            'password_hash' => bcrypt('secret123'),
            'is_active' => true,
        ]);

        $this->withSession(['patient_portal_user' => $account->id])
            ->get(route('patient-portal.dashboard'))
            ->assertOk()
            ->assertSee('John')
            ->assertSee('Otieno')
            ->assertSee('portal-sidebar');
    }

    public function test_patient_portal_dashboard_requires_authentication(): void
    {
        $this->get(route('patient-portal.dashboard'))->assertUnauthorized();
    }

    public function test_flash_partial_exists_and_handles_all_message_types(): void
    {
        $path = resource_path('views/components/flash.blade.php');
        $this->assertFileExists($path);
        $source = file_get_contents($path);

        foreach (['success', 'status', 'warning', 'error', 'errors'] as $key) {
            $this->assertStringContainsString($key, $source);
        }
    }

    public function test_key_index_tables_have_horizontal_scroll_wrappers(): void
    {
        $files = [
            'resources/views/hms/vaccination/index.blade.php',
            'resources/views/hms/equipment/index.blade.php',
            'resources/views/marketing/scheduler/index.blade.php',
            'resources/views/hms/billing/payment-reports/index.blade.php',
        ];

        foreach ($files as $file) {
            $path = base_path($file);
            $this->assertFileExists($path);
            $source = file_get_contents($path);
            $this->assertStringContainsString('overflow-x-auto', $source, "Missing overflow wrapper in {$file}");
        }
    }

    public function test_static_spinners_include_fa_spin(): void
    {
        $files = [
            'resources/views/hms/laboratory/requests/index.blade.php',
            'resources/views/hms/radiology/requests/index.blade.php',
            'resources/views/hms/ambulance/index.blade.php',
            'resources/views/hms/queue/index.blade.php',
        ];

        foreach ($files as $file) {
            $source = file_get_contents(base_path($file));
            $this->assertMatchesRegularExpression('/fa-spinner[^"\']*fa-spin/', $source, "Missing fa-spin in {$file}");
        }
    }

    public function test_public_booking_form_has_submit_loading_state(): void
    {
        $source = file_get_contents(base_path('resources/views/site/book-appointment.blade.php'));
        $this->assertStringContainsString('bookAppointmentSubmit', $source);
        $this->assertStringContainsString('fa-spin', $source);
    }
}
