<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SidebarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SidebarMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $permissions = [
            'view dashboard analytics', 'view analytics',
            'view patients', 'add patients', 'edit patients',
            'view doctors', 'manage staff profiles', 'manage nurses', 'manage ambulances',
            'create appointments', 'manage appointments', 'view appointments', 'manage queue',
            'view prescriptions', 'manage case handlers', 'generate operation reports',
            'manage bed assignments', 'manage patient vitals', 'admit patients', 'manage admissions',
            'manage test categories', 'add test requests', 'enter test results', 'manage blood bank',
            'dispense medicines', 'manage medicine inventory', 'manage packages', 'manage assets',
            'create invoices', 'edit invoices', 'add payments', 'view payment reports',
            'view invoices', 'view billing', 'view payments', 'manage advance payments',
            'view financial reports', 'generate financial reports', 'manage bank accounts',
            'manage staff profiles', 'view attendance', 'manage payrolls',
            'manage system settings', 'manage hospital info',
            'generate patient reports', 'export reports', 'view reports',
            'generate birth reports', 'generate death reports',
            'use ai assistant', 'manage ai suggestions', 'use telemedicine',
            'manage rfid tags', 'monitor iot sensors',
            'manage homepage', 'manage services', 'manage doctors listing', 'manage marketing',
            'create marketing posts', 'manage campaigns', 'manage social accounts',
            'manage roles', 'manage permissions', 'view audit logs', 'manage backups',
            'manage user accounts', 'manage security incidents', 'manage lost found items',
            'manage access events',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
    }

    public function test_all_sidebar_config_routes_exist(): void
    {
        $service = app(SidebarService::class);
        $names = $service->referencedRouteNames();

        $this->assertNotEmpty($names);

        $missing = [];
        foreach ($names as $name) {
            if (! Route::has($name)) {
                $missing[] = $name;
            }
        }

        // Parameterized routes are allowed in config but filtered at render time
        $this->assertSame([], $missing, 'Missing routes in sidebar config: ' . implode(', ', $missing));
    }

    public function test_sidebar_config_has_no_parameterized_nav_links(): void
    {
        $service = app(SidebarService::class);
        $unusable = $service->unusableRouteNames();

        // Ward-rounds style routes require {id} and must not appear as nav links
        $this->assertNotContains('hms.ipd.ward-rounds.index', $service->referencedRouteNames());
        foreach ($unusable as $name) {
            $this->assertFalse(
                $service->routeUsableAsLink($name) ?? false,
                "Route {$name} is listed as unusable"
            );
        }
    }

    public function test_administration_section_is_last_in_config(): void
    {
        $sections = config('sidebar.sections');
        $keys = array_column($sections, 'key');

        $this->assertContains('administration', $keys);
        $this->assertSame('administration', end($keys), 'Administration must be the last sidebar section');
        $this->assertNotContains('dashboard', array_slice($keys, -1));
    }

    public function test_sidebar_sections_use_expected_ia_order(): void
    {
        $keys = array_column(config('sidebar.sections'), 'key');

        $expected = [
            'dashboard',
            'clinical',
            'diagnostics',
            'pharmacy-inventory',
            'finance',
            'people',
            'reports',
            'digital',
            'cms-marketing',
            'administration',
        ];

        $this->assertSame($expected, $keys);
    }

    public function test_admin_user_sees_sidebar_sections_and_administration_last(): void
    {
        $user = User::factory()->create();
        foreach ([
            'view dashboard analytics', 'view patients', 'view doctors', 'manage staff profiles',
            'create appointments', 'view prescriptions', 'manage medicine inventory',
            'create invoices', 'manage payrolls', 'view attendance',
            'generate patient reports', 'use ai assistant', 'manage marketing',
            'manage system settings', 'manage roles', 'view audit logs', 'manage backups',
            'manage user accounts',
        ] as $perm) {
            $user->givePermissionTo($perm);
        }

        $this->actingAs($user);

        $sections = app(SidebarService::class)->sections();
        $keys = array_column($sections, 'key');

        $this->assertContains('dashboard', $keys);
        $this->assertContains('clinical', $keys);
        $this->assertContains('administration', $keys);
        $this->assertSame('administration', end($keys));
    }

    public function test_nurse_role_sees_clinical_but_not_full_administration(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view patients');
        $user->givePermissionTo('view appointments');

        $this->actingAs($user);

        $sections = app(SidebarService::class)->sections();
        $keys = array_column($sections, 'key');

        $this->assertContains('clinical', $keys);
        $this->assertNotContains('administration', $keys, 'Nurse should not see Administration without admin permissions');
        $this->assertNotContains('cms-marketing', $keys);
    }

    public function test_pharmacist_role_sees_pharmacy_section(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo('manage medicine inventory');
        $user->givePermissionTo('dispense medicines');

        $this->actingAs($user);

        $sections = app(SidebarService::class)->sections();
        $keys = array_column($sections, 'key');

        $this->assertContains('pharmacy-inventory', $keys);
        $this->assertNotContains('administration', $keys);
    }

    public function test_sidebar_partial_renders_without_error_for_admin(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view dashboard analytics');
        $user->givePermissionTo('view patients');
        $user->givePermissionTo('manage system settings');
        $user->givePermissionTo('manage roles');
        $user->givePermissionTo('view audit logs');

        $this->actingAs($user);

        $html = view('partials.sidebar', [
            'themeSettings' => [],
        ])->render();

        $this->assertStringContainsString('Dashboard', $html);
        $this->assertStringContainsString('Administration', $html);
        $this->assertStringContainsString('Clinical', $html);
        $this->assertStringContainsString('sidebar-container', $html);
        $this->assertStringNotContainsString('No menu items available', $html);

        // Administration should appear after Clinical in rendered HTML order
        $dashPos = strpos($html, 'Dashboard');
        $clinPos = strpos($html, 'Clinical');
        $adminPos = strpos($html, 'Administration');
        $this->assertNotFalse($dashPos);
        $this->assertNotFalse($clinPos);
        $this->assertNotFalse($adminPos);
        $this->assertTrue($clinPos > $dashPos);
        $this->assertTrue($adminPos > $clinPos, 'Administration must render after Clinical');
    }

    public function test_sidebar_fonts_are_readable(): void
    {
        $css = file_get_contents(public_path('css/sidebar.css'));

        $this->assertStringContainsString('font-size: 0.9375rem', $css, 'Top-level menu font should be ~15px');
        $this->assertStringContainsString('font-size: 0.9rem', $css, 'Submenu font should be ~14px');
        $this->assertStringContainsString('min-height: 44px', $css, 'Touch targets should be 44px');
    }

    public function test_sidebar_js_top_level_menus_match_config(): void
    {
        $js = file_get_contents(public_path('js/sidebar.js'));
        $keys = array_column(config('sidebar.sections'), 'key');

        foreach ($keys as $key) {
            $this->assertStringContainsString("'$key'", $js, "sidebar.js missing top-level menu id: $key");
        }
    }

    public function test_core_routes_still_resolve_for_sidebar_links(): void
    {
        $core = [
            'dashboard',
            'hms.patients.index',
            'hms.appointments.index',
            'hms.billing.invoices.index',
            'hms.pharmacy.medicines.index',
            'hms.laboratory.index',
            'hms.settings.backup',
            'admin.roles.index',
            'cms.home',
            'marketing.dashboard',
        ];

        foreach ($core as $name) {
            $this->assertTrue(Route::has($name), "Core route missing: {$name}");
        }
    }
}
