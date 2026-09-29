<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\IpdAdmission;
use App\Models\Ward;
use App\Models\Bed;
use App\Models\Medicine;
use App\Models\WardRound;
use App\Models\NursingNote;
use App\Models\FluidBalanceEntry;
use App\Models\MedicationAdministration;
use App\Models\DietOrder;
use App\Models\InpatientDischargeSummary;
use App\Models\InpatientTransfer;
use App\Models\BedType;
use App\Models\MedicineCategory;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G017InpatientCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;
    private Doctor $doctor;
    private IpdAdmission $admission;
    private Ward $ward;
    private Bed $bed;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Permission::create(['name' => 'admit patients']);
        $this->user->givePermissionTo('admit patients');

        $this->patient = Patient::factory()->create();
        $this->doctor = Doctor::factory()->create();
        $bedType = BedType::create(['name' => 'General', 'charge_per_day' => 1000]);
        $this->ward = Ward::create(['name' => 'General Ward', 'code' => 'GW-01', 'ward_type' => 'general', 'capacity' => 20, 'is_active' => true]);
        $this->bed = Bed::create(['bed_number' => 'GW-001', 'ward_name' => 'General Ward', 'bed_type_id' => $bedType->id, 'ward_id' => $this->ward->id, 'is_available' => true]);
        $this->admission = IpdAdmission::create([
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'bed_id' => $this->bed->id,
            'ward_id' => $this->ward->id,
            'admission_date' => now(),
            'status' => 'admitted',
        ]);
    }

    public function test_ward_round_creation_links_to_admission(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ipd.ward-rounds.store', $this->admission), [
            'doctor_id' => $this->doctor->id,
            'round_date' => now()->toDateString(),
            'findings' => 'Patient improving, vital signs stable.',
            'orders' => 'Continue current medication, increase fluid intake.',
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ward_rounds', [
            'ipd_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'findings' => 'Patient improving, vital signs stable.',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_nursing_note_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ipd.nursing-notes.store', $this->admission), [
            'note_type' => 'assessment',
            'content' => 'Patient complains of mild pain at surgical site. Pain score 3/10.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('nursing_notes', [
            'ipd_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'nurse_id' => $this->user->id,
            'note_type' => 'assessment',
            'content' => 'Patient complains of mild pain at surgical site. Pain score 3/10.',
        ]);
    }

    public function test_fluid_balance_intake_output_recording(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ipd.fluid-balance.store', $this->admission), [
            'entry_type' => 'intake',
            'fluid_type' => 'oral',
            'amount_ml' => 500,
            'recorded_at' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('fluid_balance_entries', [
            'ipd_admission_id' => $this->admission->id,
            'entry_type' => 'intake',
            'fluid_type' => 'oral',
            'amount_ml' => 500,
            'recorded_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.ipd.fluid-balance.store', $this->admission), [
            'entry_type' => 'output',
            'fluid_type' => 'urine',
            'amount_ml' => 350,
            'recorded_at' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('fluid_balance_entries', [
            'ipd_admission_id' => $this->admission->id,
            'entry_type' => 'output',
            'fluid_type' => 'urine',
            'amount_ml' => 350,
        ]);
    }

    public function test_fluid_balance_summary_calculates_totals(): void
    {
        FluidBalanceEntry::create([
            'ipd_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'entry_type' => 'intake',
            'fluid_type' => 'oral',
            'amount_ml' => 500,
            'recorded_by' => $this->user->id,
            'recorded_at' => now(),
        ]);
        FluidBalanceEntry::create([
            'ipd_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'entry_type' => 'intake',
            'fluid_type' => 'iv',
            'amount_ml' => 1000,
            'recorded_by' => $this->user->id,
            'recorded_at' => now(),
        ]);
        FluidBalanceEntry::create([
            'ipd_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'entry_type' => 'output',
            'fluid_type' => 'urine',
            'amount_ml' => 600,
            'recorded_by' => $this->user->id,
            'recorded_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.ipd.fluid-balance.summary', $this->admission));

        $response->assertOk();
        $response->assertViewHas('totalIntake', 1500);
        $response->assertViewHas('totalOutput', 600);
        $response->assertViewHas('balance', 900);
    }

    public function test_mar_recording(): void
    {
        $category = MedicineCategory::create(['name' => 'Antibiotics']);
        $medicine = Medicine::create([
            'name' => 'Amoxicillin',
            'category_id' => $category->id,
            'dosage_form' => 'capsule',
            'unit_price' => 50,
            'stock_quantity' => 100,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.ipd.mar.store', $this->admission), [
            'medicine_id' => $medicine->id,
            'dose' => '500mg',
            'route' => 'oral',
            'administered_at' => now()->toDateTimeString(),
            'status' => 'administered',
            'notes' => 'Given with breakfast.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('medication_administrations', [
            'ipd_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'medicine_id' => $medicine->id,
            'dose' => '500mg',
            'route' => 'oral',
            'status' => 'administered',
            'administered_by' => $this->user->id,
        ]);
    }

    public function test_diet_order_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ipd.diet-orders.store', $this->admission), [
            'diet_type' => 'diabetic',
            'instructions' => 'Low sugar, high protein. No added salt.',
            'ordered_by' => $this->doctor->id,
            'start_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('diet_orders', [
            'ipd_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'diet_type' => 'diabetic',
            'ordered_by' => $this->doctor->id,
            'status' => 'active',
        ]);
    }

    public function test_discharge_summary_with_sign_off(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ipd.discharge-summary.store', $this->admission), [
            'doctor_id' => $this->doctor->id,
            'admission_diagnosis' => 'Acute appendicitis',
            'discharge_diagnosis' => 'Post appendectomy',
            'procedure_performed' => 'Laparoscopic appendectomy',
            'treatment_summary' => 'Surgical intervention with post-op antibiotics.',
            'discharge_condition' => 'Stable',
            'follow_up_instructions' => 'Follow up in 2 weeks. Keep wound dry.',
            'medications_on_discharge' => 'Amoxicillin 500mg TDS x 7 days',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('inpatient_discharge_summaries', [
            'ipd_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'admission_diagnosis' => 'Acute appendicitis',
            'discharge_diagnosis' => 'Post appendectomy',
        ]);

        // Sign off
        $response = $this->actingAs($this->user)->post(route('hms.ipd.discharge-summary.sign', $this->admission));

        $response->assertRedirect();
        $this->assertDatabaseHas('inpatient_discharge_summaries', [
            'ipd_admission_id' => $this->admission->id,
            'signed_by' => $this->user->id,
        ]);
        $this->assertNotNull(InpatientDischargeSummary::where('ipd_admission_id', $this->admission->id)->first()->signed_at);
    }

    public function test_transfer_between_wards(): void
    {
        $icuBedType = BedType::create(['name' => 'ICU', 'charge_per_day' => 5000]);
        $toWard = Ward::create(['name' => 'ICU', 'code' => 'ICU-01', 'ward_type' => 'icu', 'capacity' => 10, 'is_active' => true]);
        $toBed = Bed::create(['bed_number' => 'ICU-001', 'ward_name' => 'ICU', 'bed_type_id' => $icuBedType->id, 'ward_id' => $toWard->id, 'is_available' => true]);

        $response = $this->actingAs($this->user)->post(route('hms.ipd.transfer.store', $this->admission), [
            'from_ward_id' => $this->ward->id,
            'to_ward_id' => $toWard->id,
            'from_bed_id' => $this->bed->id,
            'to_bed_id' => $toBed->id,
            'reason' => 'Patient condition deteriorating, requires ICU monitoring.',
            'transferred_at' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('inpatient_transfers', [
            'ipd_admission_id' => $this->admission->id,
            'patient_id' => $this->patient->id,
            'from_ward_id' => $this->ward->id,
            'to_ward_id' => $toWard->id,
            'transferred_by' => $this->user->id,
        ]);

        // Verify admission updated
        $this->admission->refresh();
        $this->assertEquals($toWard->id, $this->admission->ward_id);
        $this->assertEquals($toBed->id, $this->admission->bed_id);

        // Verify beds swapped availability
        $this->assertTrue((bool) $this->bed->fresh()->is_available);
        $this->assertFalse((bool) $toBed->fresh()->is_available);
    }
}
