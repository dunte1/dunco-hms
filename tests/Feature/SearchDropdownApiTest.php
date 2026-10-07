<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SearchDropdownApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'view patients']);
        Permission::firstOrCreate(['name' => 'view appointments']);
    }

    public function test_patient_search_api_returns_matching_patients(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view patients');

        \App\Models\Patient::factory()->create([
            'first_name' => 'Amina',
            'last_name' => 'Hassan',
            'patient_no' => 'PAT000001',
        ]);

        \App\Models\Patient::factory()->create([
            'first_name' => 'Brian',
            'last_name' => 'Ochieng',
            'patient_no' => 'PAT000002',
        ]);

        $response = $this->actingAs($user)->getJson('/hms/api/patients/search?q=Amina');

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id', 'label']]]);
        $this->assertCount(1, $response->json('data'));
        $this->assertStringContainsString('Amina', $response->json('data.0.label'));
    }

    public function test_patient_search_api_is_auth_protected(): void
    {
        $this->getJson('/hms/api/patients/search?q=test')->assertUnauthorized();
    }

    public function test_doctor_search_api_returns_matching_doctors(): void
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view appointments');

        \App\Models\Doctor::factory()->create([
            'first_name' => 'Grace',
            'last_name' => 'Wanjiku',
        ]);

        $response = $this->actingAs($user)->getJson('/hms/api/doctors/search?q=Grace');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertStringContainsString('Grace', $response->json('data.0.label'));
    }

    public function test_controllers_no_longer_load_entire_patient_table_into_dropdowns(): void
    {
        $files = [
            'app/Http/Controllers/Hms/LaboratoryController.php',
            'app/Http/Controllers/Hms/RadiologyController.php',
            'app/Http/Controllers/Hms/AdmissionsController.php',
            'app/Http/Controllers/Hms/AppointmentsController.php',
            'app/Http/Controllers/Hms/InvoicesController.php',
            'app/Http/Controllers/Hms/MessagingController.php',
        ];

        foreach ($files as $file) {
            $source = file_get_contents(base_path($file));
            $this->assertStringNotContainsString('Patient::all()', $source, "Patient::all() still present in {$file}");
            $this->assertStringNotContainsString("Patient::orderBy('first_name')->get()", $source, "Unbounded patient get() still present in {$file}");
        }
    }

    public function test_queue_health_command_runs(): void
    {
        $this->artisan('hms:queue-health')
            ->assertExitCode(0);
    }

    public function test_searchable_select_component_exists(): void
    {
        $this->assertFileExists(resource_path('views/components/searchable-select.blade.php'));
    }

    public function test_mobile_css_shrinks_bootstrap_large_controls(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));
        $this->assertStringContainsString('.btn-lg', $css);
        $this->assertStringContainsString('form-control-lg', $css);
        $this->assertStringContainsString('search-bar-fixed', $css);
        $this->assertStringContainsString('@media (max-width: 767.98px)', $css);
    }

    public function test_layouts_include_flash_and_responsive_padding(): void
    {
        foreach ([
            'resources/views/components/app-layout.blade.php',
            'resources/views/admin/layouts/app.blade.php',
            'resources/views/layouts/app.blade.php',
        ] as $file) {
            $source = file_get_contents(base_path($file));
            $this->assertStringContainsString('<x-flash />', $source, "Missing flash in {$file}");
            $this->assertStringContainsString('p-4 md:p-6', $source, "Missing responsive padding in {$file}");
        }
    }
}
