<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DashboardDefinition;
use App\Models\KpiDefinition;
use App\Models\KpiSnapshot;
use App\Models\SavedReport;
use App\Models\ReportSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G089DashboardCompletionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_dashboard_definition_creation(): void
    {
        $data = [
            'name' => 'Executive Dashboard',
            'slug' => 'executive-dashboard',
            'description' => 'High-level overview for executives',
            'layout' => ['columns' => 3, 'widgets' => ['revenue', 'patients']],
            'is_default' => false,
            'status' => 'published',
        ];

        $response = $this->postJson('/hms/dashboard/definitions', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Dashboard definition created successfully',
            ]);

        $this->assertDatabaseHas('dashboard_definitions', [
            'slug' => 'executive-dashboard',
            'status' => 'published',
            'owner_id' => $this->user->id,
        ]);
    }

    public function test_dashboard_definition_index(): void
    {
        DashboardDefinition::create([
            'name' => 'Test Dashboard',
            'slug' => 'test-dashboard',
            'owner_id' => $this->user->id,
            'status' => 'draft',
        ]);

        $response = $this->getJson('/hms/dashboard/definitions');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_dashboard_definition_show(): void
    {
        $dashboard = DashboardDefinition::create([
            'name' => 'Test Dashboard',
            'slug' => 'test-dashboard-show',
            'owner_id' => $this->user->id,
            'status' => 'published',
        ]);

        $response = $this->getJson("/hms/dashboard/definitions/{$dashboard->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_kpi_definition_and_snapshot_recording(): void
    {
        $kpi = KpiDefinition::create([
            'code' => 'BED_OCCUPANCY',
            'name' => 'Bed Occupancy Rate',
            'description' => 'Percentage of beds occupied',
            'target_value' => 85.00,
            'unit' => 'percent',
            'comparison_period' => 'monthly',
        ]);

        $data = [
            'actual_value' => 92.5,
            'snapshot_date' => '2026-09-28',
            'notes' => 'Month-end snapshot',
        ];

        $response = $this->postJson("/hms/dashboard/kpis/{$kpi->id}/record", $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'KPI snapshot recorded successfully',
            ]);

        $this->assertDatabaseHas('kpi_snapshots', [
            'kpi_definition_id' => $kpi->id,
            'actual_value' => 92.5000,
            'status' => 'met',
        ]);
    }

    public function test_kpi_snapshot_not_met(): void
    {
        $kpi = KpiDefinition::create([
            'code' => 'REVENUE_TARGET',
            'name' => 'Revenue Target',
            'description' => 'Monthly revenue target',
            'target_value' => 100000.00,
            'unit' => 'currency',
            'comparison_period' => 'monthly',
        ]);

        $response = $this->postJson("/hms/dashboard/kpis/{$kpi->id}/record", [
            'actual_value' => 75000,
            'snapshot_date' => '2026-09-28',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('kpi_snapshots', [
            'kpi_definition_id' => $kpi->id,
            'status' => 'not_met',
        ]);
    }

    public function test_kpi_trend_data_retrieval(): void
    {
        $kpi = KpiDefinition::create([
            'code' => 'PATIENT_SATISFACTION',
            'name' => 'Patient Satisfaction',
            'description' => 'Patient satisfaction score',
            'unit' => 'score',
            'comparison_period' => 'monthly',
        ]);

        KpiSnapshot::create([
            'kpi_definition_id' => $kpi->id,
            'snapshot_date' => '2026-08-01',
            'actual_value' => 78.5,
            'status' => 'insufficient_data',
            'created_at' => now(),
        ]);

        KpiSnapshot::create([
            'kpi_definition_id' => $kpi->id,
            'snapshot_date' => '2026-09-01',
            'actual_value' => 82.3,
            'status' => 'insufficient_data',
            'created_at' => now(),
        ]);

        $response = $this->getJson("/hms/dashboard/kpis/{$kpi->id}/trend");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['kpi', 'trend'],
            ]);
    }

    public function test_kpi_index(): void
    {
        KpiDefinition::create([
            'code' => 'TEST_KPI',
            'name' => 'Test KPI',
            'description' => 'A test KPI',
        ]);

        $response = $this->getJson('/hms/dashboard/kpis');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_saved_report_creation_and_run(): void
    {
        $reportData = [
            'name' => 'Monthly Revenue Report',
            'description' => 'Revenue breakdown by department',
            'report_type' => 'financial',
            'parameters' => ['date_from' => '2026-01-01', 'date_to' => '2026-09-30'],
            'is_public' => true,
        ];

        $response = $this->postJson('/hms/reports/saved', $reportData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Saved report created successfully',
            ]);

        $this->assertDatabaseHas('saved_reports', [
            'name' => 'Monthly Revenue Report',
            'report_type' => 'financial',
            'owner_id' => $this->user->id,
        ]);

        $report = SavedReport::where('name', 'Monthly Revenue Report')->first();

        $runResponse = $this->postJson("/hms/reports/saved/{$report->id}/run");

        $runResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Report executed successfully',
            ]);

        $this->assertDatabaseHas('saved_reports', [
            'id' => $report->id,
        ]);
    }

    public function test_saved_report_index(): void
    {
        SavedReport::create([
            'name' => 'Test Report',
            'report_type' => 'patient',
            'parameters' => [],
            'owner_id' => $this->user->id,
        ]);

        $response = $this->getJson('/hms/reports/saved');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_report_schedule_creation_and_pause_resume(): void
    {
        $report = SavedReport::create([
            'name' => 'Weekly Summary',
            'report_type' => 'operational',
            'parameters' => [],
            'owner_id' => $this->user->id,
        ]);

        $scheduleData = [
            'saved_report_id' => $report->id,
            'frequency' => 'weekly',
            'day_of_week' => 'Monday',
            'time_of_day' => '08:00',
            'recipients' => ['admin@example.com'],
        ];

        $response = $this->postJson('/hms/reports/schedules', $scheduleData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Report schedule created successfully',
            ]);

        $schedule = ReportSchedule::where('saved_report_id', $report->id)->first();

        $this->assertDatabaseHas('report_schedules', [
            'id' => $schedule->id,
            'status' => 'active',
        ]);

        // Pause
        $pauseResponse = $this->postJson("/hms/reports/schedules/{$schedule->id}/pause");

        $pauseResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Report schedule paused',
            ]);

        $this->assertDatabaseHas('report_schedules', [
            'id' => $schedule->id,
            'status' => 'paused',
        ]);

        // Resume
        $resumeResponse = $this->postJson("/hms/reports/schedules/{$schedule->id}/resume");

        $resumeResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Report schedule resumed',
            ]);

        $this->assertDatabaseHas('report_schedules', [
            'id' => $schedule->id,
            'status' => 'active',
        ]);
    }
}
