<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use App\Models\EmployeeDepartment;
use App\Models\PractitionerQualification;
use App\Models\PractitionerLicence;
use App\Models\Privilege;
use App\Models\PractitionerPrivilege;
use App\Models\OncallSchedule;
use App\Models\CmeRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G074CredentialingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->doctor = Doctor::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'qualification' => 'MBChB',
        ]);
    }

    public function test_qualification_recording_and_verification(): void
    {
        $response = $this->actingAs($this->user)->post('/hms/credentialing/qualifications', [
            'doctor_id' => $this->doctor->id,
            'qualification_name' => 'MBChB',
            'institution' => 'University of Nairobi',
            'country' => 'Kenya',
            'year_obtained' => 2015,
            'certificate_number' => 'CERT-001',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('practitioner_qualifications', [
            'doctor_id' => $this->doctor->id,
            'qualification_name' => 'MBChB',
            'institution' => 'University of Nairobi',
            'verified' => false,
        ]);

        $qual = PractitionerQualification::first();
        $qual->update(['verified' => true, 'verified_by' => $this->user->id, 'verified_at' => now()]);

        $this->assertTrue($qual->fresh()->verified);
    }

    public function test_licence_creation_and_expiry_tracking(): void
    {
        $response = $this->actingAs($this->user)->post('/hms/credentialing/licences', [
            'doctor_id' => $this->doctor->id,
            'licence_type' => 'medical',
            'licence_number' => 'MED-12345',
            'issuing_body' => 'KMPDC',
            'issue_date' => now()->subYear()->toDateString(),
            'expiry_date' => now()->addMonths(2)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('practitioner_licences', [
            'doctor_id' => $this->doctor->id,
            'licence_number' => 'MED-12345',
            'status' => 'active',
        ]);

        $licence = PractitionerLicence::first();
        $this->assertTrue($licence->isExpiringSoon());
        $this->assertFalse($licence->isExpired());

        // Test expired licence
        PractitionerLicence::create([
            'doctor_id' => $this->doctor->id,
            'licence_type' => 'specialist',
            'licence_number' => 'SPEC-99999',
            'issuing_body' => 'KMPDC',
            'issue_date' => now()->subYears(2)->toDateString(),
            'expiry_date' => now()->subMonth()->toDateString(),
            'status' => 'active',
        ]);

        $expired = PractitionerLicence::where('licence_number', 'SPEC-99999')->first();
        $this->assertTrue($expired->isExpired());
    }

    public function test_licence_expiry_endpoint(): void
    {
        PractitionerLicence::create([
            'doctor_id' => $this->doctor->id,
            'licence_type' => 'medical',
            'licence_number' => 'MED-EXP1',
            'issuing_body' => 'KMPDC',
            'issue_date' => now()->subYear()->toDateString(),
            'expiry_date' => now()->addDays(15)->toDateString(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get('/hms/credentialing/licences/expiring');
        $response->assertOk();
    }

    public function test_privilege_grant_and_revoke(): void
    {
        $privilege = Privilege::create([
            'name' => 'Appendectomy',
            'category' => 'surgical',
        ]);

        $response = $this->actingAs($this->user)->post('/hms/credentialing/privileges', [
            'doctor_id' => $this->doctor->id,
            'privilege_id' => $privilege->id,
            'granted_date' => now()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('practitioner_privileges', [
            'doctor_id' => $this->doctor->id,
            'privilege_id' => $privilege->id,
            'status' => 'active',
        ]);

        // Revoke
        $response = $this->actingAs($this->user)->post("/hms/credentialing/privileges/{$privilege->id}/revoke", [
            'doctor_id' => $this->doctor->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('practitioner_privileges', [
            'doctor_id' => $this->doctor->id,
            'privilege_id' => $privilege->id,
            'status' => 'revoked',
            'revoked_by' => $this->user->id,
        ]);
    }

    public function test_oncall_schedule_creation(): void
    {
        $response = $this->actingAs($this->user)->post('/hms/credentialing/oncall', [
            'doctor_id' => $this->doctor->id,
            'oncall_date' => now()->addDays(3)->toDateString(),
            'shift_type' => 'day',
            'start_time' => '08:00',
            'end_time' => '20:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('oncall_schedules', [
            'doctor_id' => $this->doctor->id,
            'shift_type' => 'day',
            'status' => 'scheduled',
        ]);
    }

    public function test_cme_record_recording(): void
    {
        $response = $this->actingAs($this->user)->post('/hms/credentialing/cme', [
            'doctor_id' => $this->doctor->id,
            'title' => 'Advanced Cardiac Life Support',
            'provider' => 'American Heart Association',
            'credits_earned' => 12.5,
            'category' => 'clinical',
            'date_from' => now()->subWeek()->toDateString(),
            'date_to' => now()->subWeek()->addDays(2)->toDateString(),
            'status' => 'completed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cme_records', [
            'doctor_id' => $this->doctor->id,
            'title' => 'Advanced Cardiac Life Support',
            'credits_earned' => 12.5,
            'category' => 'clinical',
            'status' => 'completed',
        ]);
    }

    public function test_credentialing_index_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get('/hms/credentialing');
        $response->assertOk();
    }

    public function test_privileges_index(): void
    {
        $response = $this->actingAs($this->user)->get('/hms/credentialing/privileges');
        $response->assertOk();
    }

    public function test_licences_index(): void
    {
        $response = $this->actingAs($this->user)->get('/hms/credentialing/licences');
        $response->assertOk();
    }

    public function test_oncall_index(): void
    {
        $response = $this->actingAs($this->user)->get('/hms/credentialing/oncall');
        $response->assertOk();
    }

    public function test_cme_index(): void
    {
        $response = $this->actingAs($this->user)->get('/hms/credentialing/cme');
        $response->assertOk();
    }
}
