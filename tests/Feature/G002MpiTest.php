<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\PatientIdentifier;
use App\Models\PatientContact;
use App\Models\PatientMergeLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class G002MpiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
        $this->user = User::factory()->create(['status' => 'active']);
        $this->user->assignRole('Receptionist');
    }

    private function createPatient(array $overrides = []): Patient
    {
        return Patient::create(array_merge([
            'patient_no' => 'PAT' . strtoupper(uniqid()),
            'first_name' => 'Test',
            'last_name'  => 'Patient',
        ], $overrides));
    }

    public function test_patient_identifiers_table_can_store_and_retrieve(): void
    {
        $patient = $this->createPatient();

        $identifier = PatientIdentifier::create([
            'patient_id'       => $patient->id,
            'identifier_type'  => 'national_id',
            'identifier_value' => '12345678',
            'issuing_authority'=> 'Government',
            'expiry_date'      => now()->addYears(5),
            'is_primary'       => true,
        ]);

        $this->assertDatabaseHas('patient_identifiers', [
            'id'               => $identifier->id,
            'identifier_type'  => 'national_id',
            'identifier_value' => '12345678',
        ]);

        $this->assertCount(1, $patient->identifiers);
        $this->assertTrue($patient->identifiers->first()->is_primary);
    }

    public function test_patient_contacts_table_works_with_contact_types(): void
    {
        $patient = $this->createPatient();

        $nok = PatientContact::create([
            'patient_id'   => $patient->id,
            'contact_type' => 'next_of_kin',
            'first_name'   => 'Jane',
            'last_name'    => 'Doe',
            'relationship' => 'Spouse',
            'phone'        => '0700000000',
            'is_primary'   => true,
        ]);

        $emergency = PatientContact::create([
            'patient_id'   => $patient->id,
            'contact_type' => 'emergency',
            'first_name'   => 'John',
            'last_name'    => 'Doe',
            'relationship' => 'Brother',
            'phone'        => '0711111111',
            'is_primary'   => false,
        ]);

        $this->assertDatabaseHas('patient_contacts', ['id' => $nok->id, 'contact_type' => 'next_of_kin']);
        $this->assertDatabaseHas('patient_contacts', ['id' => $emergency->id, 'contact_type' => 'emergency']);

        $this->assertCount(2, $patient->contacts);
    }

    public function test_duplicate_detection_finds_matching_patients(): void
    {
        $existing = $this->createPatient([
            'first_name'  => 'John',
            'last_name'   => 'Doe',
            'dob'         => '1990-01-15',
            'national_id' => 'NAT12345',
        ]);

        $newPatient = $this->createPatient([
            'first_name' => 'Other',
            'last_name'  => 'Person',
        ]);

        $duplicates = $newPatient->detectDuplicates([
            'first_name'  => 'John',
            'last_name'   => 'Doe',
            'dob'         => '1990-01-15',
            'national_id' => 'NAT12345',
        ]);

        $this->assertTrue($duplicates->contains('id', $existing->id));
    }

    public function test_patient_merge_transfers_records_and_soft_deletes_duplicate(): void
    {
        $this->actingAs($this->user);

        $primary = $this->createPatient(['patient_no' => 'PAT000001', 'first_name' => 'Primary']);
        $duplicate = $this->createPatient(['patient_no' => 'PAT000002', 'first_name' => 'Duplicate']);

        PatientIdentifier::create([
            'patient_id'       => $duplicate->id,
            'identifier_type'  => 'national_id',
            'identifier_value' => 'MERGE123',
            'is_primary'       => true,
        ]);

        PatientContact::create([
            'patient_id'   => $duplicate->id,
            'contact_type' => 'emergency',
            'first_name'   => 'Contact',
            'last_name'    => 'Person',
            'is_primary'   => true,
        ]);

        $log = $primary->mergeWith($duplicate->id);

        $this->assertSoftDeleted('patients', ['id' => $duplicate->id]);

        $this->assertDatabaseHas('patient_identifiers', [
            'patient_id'       => $primary->id,
            'identifier_value' => 'MERGE123',
        ]);

        $this->assertDatabaseHas('patient_contacts', [
            'patient_id' => $primary->id,
            'contact_type' => 'emergency',
        ]);

        $this->assertInstanceOf(PatientMergeLog::class, $log);
        $this->assertEquals($primary->id, $log->primary_patient_id);
        $this->assertEquals($duplicate->id, $log->duplicate_patient_id);
        $this->assertTrue($log->data_migrated);
    }

    public function test_emergency_registration_creates_patient_with_temporary_mrn(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('hms.patients.emergency'), [
                'first_name' => 'Emergency',
                'last_name'  => 'Patient',
                'gender'     => 'male',
            ]);

        $response->assertRedirect();

        $patient = Patient::where('first_name', 'Emergency')->where('last_name', 'Patient')->first();
        $this->assertNotNull($patient);
        $this->assertStringStartsWith('EMG-', $patient->patient_no);
    }

    public function test_merge_log_records_the_action(): void
    {
        $this->actingAs($this->user);

        $primary = $this->createPatient(['patient_no' => 'PAT00010']);
        $duplicate = $this->createPatient(['patient_no' => 'PAT00011']);

        $primary->mergeWith($duplicate->id);

        $this->assertDatabaseHas('patient_merge_log', [
            'primary_patient_id'  => $primary->id,
            'duplicate_patient_id' => $duplicate->id,
            'merged_by'           => $this->user->id,
            'data_migrated'       => true,
        ]);

        $log = PatientMergeLog::first();
        $this->assertNotNull($log->merged_at);
    }
}
