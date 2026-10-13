<?php

namespace Tests\Feature\Sidebar;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_sidebar_config_has_required_sections(): void
    {
        $sections = config('sidebar.sections');
        $keys = array_column($sections, 'key');

        $this->assertContains('dashboard', $keys);
        $this->assertContains('clinical', $keys);
        $this->assertContains('administration', $keys);
        $this->assertSame('administration', end($keys), 'Administration should be last');
    }

    public function test_sidebar_items_use_existing_routes(): void
    {
        $user = $this->makeUserWithRole('Super Admin');
        $this->actingAs($user);

        $sections = app(\App\Services\SidebarService::class)->sections();
        $this->assertNotEmpty($sections);

        $routeCount = 0;
        foreach ($sections as $section) {
            foreach ($section['items'] ?? [] as $item) {
                if (isset($item['children'])) {
                    foreach ($item['children'] as $child) {
                        if (! empty($child['route'])) {
                            $routeCount++;
                            $this->assertTrue(
                                \Illuminate\Support\Facades\Route::has($child['route']),
                                "Route [{$child['route']}] should exist"
                            );
                        }
                    }
                } elseif (! empty($item['route'])) {
                    $routeCount++;
                    $this->assertTrue(
                        \Illuminate\Support\Facades\Route::has($item['route']),
                        "Route [{$item['route']}] should exist"
                    );
                }
            }
        }

        $this->assertGreaterThan(20, $routeCount, 'Sidebar should expose many routes');
    }

    public function test_sidebar_items_do_not_use_parameterized_routes(): void
    {
        $user = $this->makeUserWithRole('Super Admin');
        $this->actingAs($user);

        $sections = app(\App\Services\SidebarService::class)->sections();

        foreach ($sections as $section) {
            foreach ($section['items'] ?? [] as $item) {
                $routes = $item['children'] ?? [$item];
                foreach ($routes as $child) {
                    if (empty($child['route'])) {
                        continue;
                    }
                    $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName($child['route']);
                    if (! $route) {
                        continue;
                    }
                    foreach ($route->parameterNames() as $param) {
                        if ($param !== '') {
                            $this->fail("Sidebar route [{$child['route']}] has required parameter [{$param}]");
                        }
                    }
                }
            }
        }

        $this->assertTrue(true);
    }

    public function test_sidebar_vaccination_routes_exist(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('vaccination.index'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('vaccination.administer'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('vaccination.vaccine-store'));
    }

    public function test_sidebar_global_search_route_exists(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('hms.global-search'));
    }

    public function test_sidebar_opd_start_consultation_route_exists(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('hms.opd.start-consultation'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('hms.opd.finalize'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('hms.opd.update-status'));
    }
}
