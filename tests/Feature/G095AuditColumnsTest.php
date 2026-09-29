<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class G095AuditColumnsTest extends TestCase
{
    use RefreshDatabase;

    private array $tables = [
        'patients',
        'opd_visits',
        'ipd_admissions',
        'prescriptions',
        'prescription_items',
        'lab_requests',
        'lab_request_items',
        'radiology_requests',
        'invoices',
        'invoice_items',
        'payments',
        'referrals',
        'birth_reports',
        'death_reports',
        'operation_reports',
        'patient_diagnoses',
        'consent_forms',
        'documents',
        'mrd_files',
        'vaccination_records',
        'mortuary_records',
    ];

    public function test_all_clinical_tables_have_created_by_column(): void
    {
        foreach ($this->tables as $table) {
            $this->assertTrue(
                Schema::hasColumn($table, 'created_by'),
                "Table [{$table}] is missing [created_by] column"
            );
        }
    }

    public function test_all_clinical_tables_have_updated_by_column(): void
    {
        foreach ($this->tables as $table) {
            $this->assertTrue(
                Schema::hasColumn($table, 'updated_by'),
                "Table [{$table}] is missing [updated_by] column"
            );
        }
    }

    public function test_all_clinical_tables_have_deleted_at_column(): void
    {
        foreach ($this->tables as $table) {
            $this->assertTrue(
                Schema::hasColumn($table, 'deleted_at'),
                "Table [{$table}] is missing [deleted_at] column"
            );
        }
    }

    public function test_all_clinical_tables_have_facility_id_column(): void
    {
        foreach ($this->tables as $table) {
            $this->assertTrue(
                Schema::hasColumn($table, 'facility_id'),
                "Table [{$table}] is missing [facility_id] column"
            );
        }
    }

    public function test_patient_can_be_soft_deleted_and_restored(): void
    {
        $user = User::factory()->create();
        $patient = Patient::create([
            'patient_no' => 'PAT999001',
            'first_name' => 'Test',
            'last_name' => 'Patient',
            'created_by' => $user->id,
        ]);

        $patientId = $patient->id;

        $patient->delete();

        $this->assertSoftDeleted('patients', ['id' => $patientId]);

        $restored = Patient::withTrashed()->find($patientId);
        $restored->restore();

        $this->assertDatabaseHas('patients', ['id' => $patientId, 'deleted_at' => null]);
    }

    public function test_created_by_tracks_the_user_who_created_the_record(): void
    {
        $user = User::factory()->create();

        $patient = Patient::create([
            'patient_no' => 'PAT999002',
            'first_name' => 'Audit',
            'last_name' => 'User',
            'created_by' => $user->id,
        ]);

        $this->assertEquals($user->id, $patient->fresh()->created_by);
    }

    public function test_updated_by_tracks_the_user_who_updated_the_record(): void
    {
        $creator = User::factory()->create();
        $updater = User::factory()->create();

        $patient = Patient::create([
            'patient_no' => 'PAT999003',
            'first_name' => 'Before',
            'last_name' => 'Update',
            'created_by' => $creator->id,
        ]);

        $patient->update(['updated_by' => $updater->id, 'first_name' => 'After']);

        $this->assertEquals($updater->id, $patient->fresh()->updated_by);
        $this->assertEquals('After', $patient->fresh()->first_name);
    }
}
