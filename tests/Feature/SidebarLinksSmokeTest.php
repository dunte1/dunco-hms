<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SidebarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SidebarLinksSmokeTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<int, string> */
    protected array $permissions = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->permissions = [
            'view dashboard analytics', 'view analytics',
            'view patients', 'add patients', 'edit patients', 'delete patients',
            'view doctors', 'manage staff profiles', 'manage nurses', 'manage ambulances',
            'create appointments', 'manage appointments', 'view appointments', 'manage queue',
            'view prescriptions', 'create prescriptions', 'edit prescriptions', 'dispense medicines',
            'manage case handlers', 'generate operation reports', 'manage bed assignments',
            'manage patient vitals', 'admit patients', 'manage admissions', 'view admissions',
            'manage test categories', 'add test requests', 'enter test results', 'manage blood bank',
            'manage medicine inventory', 'manage packages', 'manage assets', 'manage inventory',
            'manage suppliers', 'transfer assets', 'dispose assets',
            'create invoices', 'edit invoices', 'add payments', 'add refunds', 'view payment reports',
            'view invoices', 'view billing', 'view payments', 'manage advance payments',
            'manage payment methods', 'manage charges', 'manage discounts',
            'view financial reports', 'generate financial reports', 'manage bank accounts',
            'view attendance', 'manage payrolls', 'manage employees', 'manage employee contracts',
            'manage leave requests', 'manage recruitment', 'manage job postings',
            'manage training programs', 'manage appraisals', 'manage shift types',
            'manage roster builder', 'manage payroll exports', 'manage disciplinary records',
            'manage system settings', 'manage hospital info',
            'generate patient reports', 'export reports', 'view reports',
            'generate birth reports', 'generate death reports', 'generate pathology reports',
            'use ai assistant', 'manage ai suggestions', 'use telemedicine',
            'manage rfid tags', 'monitor iot sensors',
            'manage homepage', 'manage services', 'manage doctors listing', 'manage marketing',
            'create marketing posts', 'manage campaigns', 'manage social accounts',
            'manage roles', 'manage permissions', 'view audit logs', 'manage backups',
            'manage user accounts', 'manage security incidents', 'manage lost found items',
            'manage access events', 'manage lab equipment', 'manage lab integration',
            'manage insurance API', 'manage insurance integration', 'verify insurance',
            'manage insurance claims', 'manage lab worklists', 'view test results',
            'manage maintenance requests', 'manage work orders', 'manage calibrations',
            'manage record requests', 'upload documents', 'manage immunization schedules',
        ];

        foreach ($this->permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
    }

    public function test_every_sidebar_link_returns_ok_or_expected_redirect(): void
    {
        $super = User::factory()->create();
        foreach ($this->permissions as $perm) {
            $super->givePermissionTo($perm);
        }

        $this->actingAs($super);

        $service = app(SidebarService::class);
        $sections = $service->sections();

        $this->assertNotEmpty($sections, 'Super admin should see sidebar sections');

        $checked = 0;
        $failures = [];

        foreach ($sections as $section) {
            foreach ($section['items'] as $item) {
                if (! empty($item['children'])) {
                    foreach ($item['children'] as $child) {
                        if (empty($child['route'])) {
                            continue;
                        }
                        $result = $this->probeRoute($child['route']);
                        $checked++;
                        if ($result !== true) {
                            $failures[] = "{$child['label']} ({$child['route']}): {$result}";
                        }
                    }
                    continue;
                }

                if (empty($item['route'])) {
                    continue;
                }

                $result = $this->probeRoute($item['route']);
                $checked++;
                if ($result !== true) {
                    $failures[] = "{$item['label']} ({$item['route']}): {$result}";
                }
            }
        }

        $this->assertGreaterThan(50, $checked, "Expected to probe many links, probed {$checked}");

        $this->assertSame(
            [],
            $failures,
            "Sidebar link failures:\n" . implode("\n", $failures)
        );
    }

    public function test_all_referenced_routes_are_registered(): void
    {
        $service = app(SidebarService::class);
        $missing = [];

        foreach ($service->referencedRouteNames() as $name) {
            if (! Route::has($name)) {
                $missing[] = $name;
            }
        }

        $this->assertSame([], $missing, 'Unregistered routes: ' . implode(', ', $missing));
    }

    /**
     * @return true|string
     */
    protected function probeRoute(string $name): bool|string
    {
        try {
            $route = Route::getRoutes()->getByName($name);

            if (! $route) {
                return 'route not found';
            }

            if (! empty($route->parameterNames())) {
                return 'parameterized route (skipped for smoke)';
            }

            $uri = '/' . ltrim($route->uri(), '/');
            // Skip external-only board routes that need live queue data if they 500
            $response = $this->get($uri);

            $status = $response->getStatusCode();

            if ($status === 404) {
                return '404';
            }

            if ($status === 500) {
                $content = $response->getContent();
                $snippet = '500';
                if (preg_match('/exception[^<]{0,200}/i', strip_tags($content), $m)) {
                    $snippet = '500: ' . trim(substr($m[0], 0, 180));
                }

                return $snippet;
            }

            if ($status === 403) {
                // Super admin should not get 403 on these; if they do, permission wiring is wrong
                return '403 unexpected for super admin';
            }

            if ($status >= 300 && $status < 400) {
                $location = $response->headers->get('Location') ?? '';
                // Login redirect means auth failed unexpectedly
                if (str_contains($location, 'login')) {
                    return 'redirected to login';
                }
            }

            return true;
        } catch (\Throwable $e) {
            return 'exception: ' . $e->getMessage();
        }
    }
}
