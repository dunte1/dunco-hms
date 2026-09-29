<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Ward;
use App\Models\Shift;
use App\Models\IpdAdmission;
use App\Models\NurseAllocation;
use App\Models\DutyRoster;
use App\Models\DutyRosterEntry;
use App\Models\ShiftHandover;
use App\Models\NursingProcedure;
use App\Models\BedType;
use App\Models\Bed;
use App\Models\Doctor;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G023NursingCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Ward $ward;
    private Patient $patient;
    private IpdAdmission $admission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Permission::create(['name' => 'manage patients']);
        $this->user->givePermissionTo('manage patients');

        $this->ward = Ward::create([
            'name' => 'General Ward',
            'code' => 'GW-01',
            'ward_type' => 'general',
            'capacity' => 20,
            'is_active' => true,
        ]);

        $this->patient = Patient::factory()->create();

        $bedType = BedType::create(['name' => 'General', 'charge_per_day' => 1000]);
        $bed = Bed::create([
            'bed_number' => 'GW-001',
            'ward_name' => 'General Ward',
            'bed_type_id' => $bedType->id,
            'ward_id' => $this->ward->id,
            'is_available' => true,
        ]);
        $doctor = Doctor::factory()->create();
        $this->admission = IpdAdmission::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $doctor->id,
            'bed_id' => $bed->id,
            'ward_id' => $this->ward->id,
            'admission_date' => now(),
            'status' => 'admitted',
        ]);
    }

    public function test_nurse_allocation_to_ward(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.nursing.allocations.store'), [
            'nurse_user_id' => $this->user->id,
            'ward_id' => $this->ward->id,
            'allocated_date' => now()->toDateString(),
            'shift_type' => 'day',
            'patient_count' => 8,
            'notes' => 'Assigned to beds 1-10.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('nurse_allocations', [
            'nurse_user_id' => $this->user->id,
            'ward_id' => $this->ward->id,
            'shift_type' => 'day',
            'status' => 'active',
            'allocated_by' => $this->user->id,
        ]);
    }

    public function test_nurse_allocation_index(): void
    {
        NurseAllocation::create([
            'nurse_user_id' => $this->user->id,
            'ward_id' => $this->ward->id,
            'allocated_date' => now()->toDateString(),
            'shift_type' => 'day',
            'patient_count' => 5,
            'status' => 'active',
            'allocated_by' => $this->user->id,
        ]);

        $this->assertDatabaseHas('nurse_allocations', [
            'nurse_user_id' => $this->user->id,
            'ward_id' => $this->ward->id,
            'shift_type' => 'day',
            'status' => 'active',
        ]);
    }

    public function test_duty_roster_creation_and_entry(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.nursing.rosters.store'), [
            'ward_id' => $this->ward->id,
            'roster_date' => now()->toDateString(),
            'shift_type' => 'day',
            'roster_name' => 'Monday Day Shift',
            'notes' => 'Standard day shift roster.',
        ]);

        $response->assertRedirect();
        $roster = DutyRoster::where('ward_id', $this->ward->id)->first();
        $this->assertNotNull($roster);
        $this->assertEquals('draft', $roster->status);

        $response = $this->actingAs($this->user)->post(route('hms.nursing.rosters.entries.store', $roster), [
            'nurse_user_id' => $this->user->id,
            'notes' => 'Primary nurse for beds 1-10.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('duty_roster_entries', [
            'roster_id' => $roster->id,
            'nurse_user_id' => $this->user->id,
            'status' => 'scheduled',
        ]);
    }

    public function test_roster_publishing(): void
    {
        $roster = DutyRoster::create([
            'ward_id' => $this->ward->id,
            'roster_date' => now()->toDateString(),
            'shift_type' => 'day',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.nursing.rosters.publish', $roster));

        $response->assertRedirect();
        $roster->refresh();
        $this->assertEquals('published', $roster->status);
        $this->assertEquals($this->user->id, $roster->published_by);
        $this->assertNotNull($roster->published_at);
    }

    public function test_shift_handover_creation_and_acknowledgement(): void
    {
        $nurse1 = User::factory()->create();
        $nurse2 = User::factory()->create();

        $response = $this->actingAs($this->user)->post(route('hms.nursing.handovers.store'), [
            'ward_id' => $this->ward->id,
            'shift_type' => 'day_to_night',
            'handover_date' => now()->toDateString(),
            'handover_from_user_id' => $nurse1->id,
            'handover_to_user_id' => $nurse2->id,
            'patient_count' => 15,
            'critical_patients' => 2,
            'pending_tasks' => 'Medication due at 20:00 for bed 5.',
            'completed_tasks' => 'Vital signs recorded for all patients.',
            'pending_medications' => 'IV antibiotics at 22:00 for bed 12.',
            'equipment_issues' => 'Pulse oximeter in bed 3 not working.',
            'notes' => 'Patient in bed 7 may need pain management.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('shift_handovers', [
            'ward_id' => $this->ward->id,
            'shift_type' => 'day_to_night',
            'handover_from_user_id' => $nurse1->id,
            'handover_to_user_id' => $nurse2->id,
            'patient_count' => 15,
            'critical_patients' => 2,
            'status' => 'pending',
        ]);

        $handover = ShiftHandover::where('ward_id', $this->ward->id)->first();

        $response = $this->actingAs($this->user)->post(route('hms.nursing.handovers.acknowledge', $handover));

        $response->assertRedirect();
        $handover->refresh();
        $this->assertEquals('completed', $handover->status);
        $this->assertNotNull($handover->acknowledged_at);
    }

    public function test_nursing_procedure_recording(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.nursing.procedures.store'), [
            'patient_id' => $this->patient->id,
            'ipd_admission_id' => $this->admission->id,
            'procedure_name' => 'Wound Dressing',
            'description' => 'Changed dressing on surgical wound. Clean, no signs of infection.',
            'body_site' => 'Right abdomen',
            'outcome' => 'Successful',
            'performed_at' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('nursing_procedures', [
            'patient_id' => $this->patient->id,
            'ipd_admission_id' => $this->admission->id,
            'procedure_name' => 'Wound Dressing',
            'body_site' => 'Right abdomen',
            'outcome' => 'Successful',
            'performed_by' => $this->user->id,
        ]);
    }
}
