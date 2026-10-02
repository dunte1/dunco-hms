<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CssdBatch;
use App\Models\InstrumentSet;
use App\Models\CssdCycleRecord;
use App\Models\SterilizerRun;
use App\Models\SterilityIndicatorResult;
use App\Models\CssdIssueRecord;
use App\Models\CssdReturnRecord;
use App\Models\Doctor;
use App\Models\OtRoom;
use App\Models\OtSchedule;
use App\Models\Patient;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class G047CssdCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);

        $this->user = User::factory()->create();
        $this->user->assignRole('CSSD Technician');
    }

    public function test_instrument_set_index(): void
    {
        InstrumentSet::create(['name' => 'General Surgery Set', 'code' => 'GS-001']);

        $response = $this->actingAs($this->user)->get(route('hms.cssd.instrument-sets.index'));

        $response->assertOk();
    }

    public function test_instrument_set_store(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.cssd.instrument-sets.store'), [
            'name' => 'General Surgery Set',
            'code' => 'GS-001',
            'description' => 'Standard general surgery instrument set',
            'instrument_count' => 15,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('instrument_sets', [
            'name' => 'General Surgery Set',
            'code' => 'GS-001',
            'instrument_count' => 15,
            'is_active' => true,
        ]);
    }

    public function test_instrument_set_store_unique_code_validation(): void
    {
        InstrumentSet::create(['name' => 'Existing Set', 'code' => 'GS-001']);

        $response = $this->actingAs($this->user)->post(route('hms.cssd.instrument-sets.store'), [
            'name' => 'Duplicate Set',
            'code' => 'GS-001',
        ]);

        $response->assertSessionHasErrors('code');
    }

    public function test_cycle_record_store(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.cssd.cycles.store'), [
            'cycle_type' => 'sterilization',
            'notes' => 'Autoclave cycle for surgical instruments',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cssd_cycle_records', [
            'cycle_type' => 'sterilization',
            'performed_by' => $this->user->id,
            'status' => 'in_progress',
        ]);
    }

    public function test_cycle_record_store_with_batch_and_set(): void
    {
        $batch = CssdBatch::create([
            'batch_number' => 'CSSD-202609-001',
            'status' => 'processing',
        ]);
        $set = InstrumentSet::create(['name' => 'Ortho Set', 'code' => 'ORT-001']);

        $response = $this->actingAs($this->user)->post(route('hms.cssd.cycles.store'), [
            'batch_id' => $batch->id,
            'instrument_set_id' => $set->id,
            'cycle_type' => 'decontamination',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cssd_cycle_records', [
            'batch_id' => $batch->id,
            'instrument_set_id' => $set->id,
            'cycle_type' => 'decontamination',
        ]);
    }

    public function test_cycle_complete(): void
    {
        $cycle = CssdCycleRecord::create([
            'cycle_type' => 'cleaning',
            'start_time' => now()->subMinutes(30),
            'performed_by' => $this->user->id,
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.cssd.cycles.complete', $cycle));

        $response->assertRedirect();
        $this->assertDatabaseHas('cssd_cycle_records', [
            'id' => $cycle->id,
            'status' => 'completed',
        ]);
    }

    public function test_sterilizer_run_store(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.cssd.sterilizer-runs.store'), [
            'sterilizer_name' => 'Autoclave Unit 1',
            'load_number' => 42,
            'temperature' => 134.5,
            'pressure' => 30.2,
            'exposure_time_minutes' => 18,
            'cycle_type' => 'pre_vac',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sterilizer_runs', [
            'sterilizer_name' => 'Autoclave Unit 1',
            'load_number' => 42,
            'temperature' => 134.5,
            'pressure' => 30.2,
            'exposure_time_minutes' => 18,
            'cycle_type' => 'pre_vac',
            'operator_id' => $this->user->id,
            'status' => 'completed',
        ]);
    }

    public function test_sterility_indicator_store(): void
    {
        $run = SterilizerRun::create([
            'sterilizer_name' => 'Autoclave Unit 2',
            'load_number' => 10,
            'start_time' => now(),
            'cycle_type' => 'gravity',
            'operator_id' => $this->user->id,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)->post(
            route('hms.cssd.sterilizer-runs.indicators.store', $run),
            [
                'indicator_type' => 'biological',
                'result' => 'pass',
                'batch_number' => 'BIO-2026-001',
                'expiry_date' => now()->addMonths(3)->toDateString(),
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('sterility_indicator_results', [
            'sterilizer_run_id' => $run->id,
            'indicator_type' => 'biological',
            'result' => 'pass',
            'batch_number' => 'BIO-2026-001',
            'recorded_by' => $this->user->id,
        ]);
    }

    public function test_issue_store(): void
    {
        $set = InstrumentSet::create(['name' => 'Neuro Set', 'code' => 'NEU-001']);
        $recipient = User::factory()->create();

        $response = $this->actingAs($this->user)->post(route('hms.cssd.issues.store'), [
            'instrument_set_id' => $set->id,
            'issued_to_user_id' => $recipient->id,
            'expected_return_at' => now()->addHours(4)->toDateTimeString(),
            'notes' => 'For scheduled craniotomy',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cssd_issue_records', [
            'instrument_set_id' => $set->id,
            'issued_to_user_id' => $recipient->id,
            'status' => 'issued',
        ]);
    }

    public function test_issue_store_with_theatre_schedule(): void
    {
        $set = InstrumentSet::create(['name' => 'Cardiac Set', 'code' => 'CAR-001']);
        $recipient = User::factory()->create();
        $room = OtRoom::create(['name' => 'OT-1', 'type' => 'cardiac', 'status' => 'available']);
        $surgeon = Doctor::factory()->create();
        $schedule = OtSchedule::create([
            'schedule_number' => 'OT-202609-0001',
            'patient_id' => Patient::factory()->create()->id,
            'ot_room_id' => $room->id,
            'surgeon_id' => $surgeon->id,
            'procedure_name' => 'CABG',
            'scheduled_date' => now()->toDateString(),
            'scheduled_start' => '08:00',
            'status' => 'scheduled',
            'created_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.cssd.issues.store'), [
            'instrument_set_id' => $set->id,
            'issued_to_user_id' => $recipient->id,
            'theatre_schedule_id' => $schedule->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cssd_issue_records', [
            'theatre_schedule_id' => $schedule->id,
            'status' => 'issued',
        ]);
    }

    public function test_return_set(): void
    {
        $set = InstrumentSet::create(['name' => 'Gen Set', 'code' => 'GEN-001']);
        $issue = CssdIssueRecord::create([
            'instrument_set_id' => $set->id,
            'issued_to_user_id' => User::factory()->create()->id,
            'issued_at' => now()->subHours(3),
            'status' => 'issued',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.cssd.issues.return', $issue), [
            'condition' => 'complete',
            'notes' => 'All instruments accounted for',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cssd_return_records', [
            'issue_record_id' => $issue->id,
            'condition' => 'complete',
            'inspected_by' => $this->user->id,
        ]);
        $this->assertDatabaseHas('cssd_issue_records', [
            'id' => $issue->id,
            'status' => 'returned',
        ]);
    }

    public function test_return_set_with_missing_items(): void
    {
        $set = InstrumentSet::create(['name' => 'ENT Set', 'code' => 'ENT-001']);
        $issue = CssdIssueRecord::create([
            'instrument_set_id' => $set->id,
            'issued_to_user_id' => User::factory()->create()->id,
            'issued_at' => now()->subHours(2),
            'status' => 'issued',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.cssd.issues.return', $issue), [
            'condition' => 'missing',
            'missing_items' => '1x forceps, 1x retractor',
            'notes' => 'Missing items noted during inspection',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cssd_return_records', [
            'issue_record_id' => $issue->id,
            'condition' => 'missing',
            'missing_items' => '1x forceps, 1x retractor',
        ]);
        $this->assertDatabaseHas('cssd_issue_records', [
            'id' => $issue->id,
            'status' => 'returned',
        ]);
    }
}
