<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use App\Models\Appointment;
use App\Models\AppointmentReminder;
use App\Models\Referral;
use App\Models\ReferringFacility;
use App\Models\ReferralDocument;
use App\Models\ReferralStatusHistory;
use App\Models\ReferralFeedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class G075AppointmentsReferralsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Schedule $schedule;
    private Appointment $appointment;
    private Referral $referral;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $department = EmployeeDepartment::create([
            'name' => 'General Medicine',
            'code' => 'GM',
        ]);

        $employee = Employee::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'phone' => '0700000000',
            'status' => 'active',
            'department_id' => $department->id,
            'position' => 'Doctor',
            'employment_type' => 'full_time',
            'hire_date' => now()->subYear()->toDateString(),
        ]);

        $this->schedule = Schedule::create([
            'employee_id' => $employee->id,
            'schedule_date' => now()->toDateString(),
            'start_time' => '08:00',
            'end_time' => '17:00',
            'shift_type' => 'morning',
        ]);

        $patient = \App\Models\Patient::create([
            'first_name' => 'John',
            'last_name' => 'Patient',
            'patient_no' => 'PAT-' . uniqid(),
            'phone' => '0711111111',
            'gender' => 'male',
            'dob' => '1990-01-01',
        ]);

        $this->appointment = Appointment::create([
            'patient_id' => $patient->id,
            'scheduled_at' => now()->addDay(),
            'status' => 'scheduled',
        ]);

        $this->referral = Referral::create([
            'patient_id' => $patient->id,
            'referral_type' => 'out',
            'urgency' => 'routine',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_schedule_slot_creation(): void
    {
        $response = $this->actingAs($this->user)->post("/hms/schedules/{$this->schedule->id}/slots", [
            'day_of_week' => 1,
            'start_time' => '09:00',
            'end_time' => '12:00',
            'slot_duration_minutes' => 30,
            'max_appointments' => 2,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('schedule_slots', [
            'schedule_id' => $this->schedule->id,
            'day_of_week' => 1,
            'start_time' => '09:00',
            'end_time' => '12:00',
            'slot_duration_minutes' => 30,
            'max_appointments' => 2,
            'is_available' => true,
        ]);
    }

    public function test_schedule_slot_listing(): void
    {
        ScheduleSlot::create([
            'schedule_id' => $this->schedule->id,
            'day_of_week' => 1,
            'start_time' => '09:00',
            'end_time' => '12:00',
        ]);

        $response = $this->actingAs($this->user)->get("/hms/schedules/{$this->schedule->id}/slots");
        $response->assertOk();
    }

    public function test_appointment_reminder_scheduling(): void
    {
        $response = $this->actingAs($this->user)->post("/hms/appointments/{$this->appointment->id}/reminders", [
            'reminder_type' => 'sms',
            'send_at' => now()->addHours(2)->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('appointment_reminders', [
            'appointment_id' => $this->appointment->id,
            'reminder_type' => 'sms',
            'status' => 'pending',
        ]);
    }

    public function test_appointment_reminder_send(): void
    {
        $reminder = AppointmentReminder::create([
            'appointment_id' => $this->appointment->id,
            'reminder_type' => 'email',
            'send_at' => now()->addHour(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->post("/hms/appointments/reminders/{$reminder->id}/send");

        $response->assertRedirect();
        $this->assertDatabaseHas('appointment_reminders', [
            'id' => $reminder->id,
            'status' => 'sent',
        ]);
    }

    public function test_referring_facility_creation(): void
    {
        $response = $this->actingAs($this->user)->post('/hms/referrals/facilities', [
            'name' => 'Kenyatta National Hospital',
            'facility_code' => 'KNH-001',
            'county' => 'Nairobi',
            'sub_county' => 'Westlands',
            'phone' => '+254202726300',
            'email' => 'info@knh.or.ke',
            'contact_person' => 'Dr. Smith',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('referring_facilities', [
            'name' => 'Kenyatta National Hospital',
            'facility_code' => 'KNH-001',
            'county' => 'Nairobi',
            'is_active' => true,
        ]);
    }

    public function test_referring_facility_listing(): void
    {
        ReferringFacility::create([
            'name' => 'Test Hospital',
            'county' => 'Nairobi',
        ]);

        $response = $this->actingAs($this->user)->get('/hms/referrals/facilities');
        $response->assertOk();
    }

    public function test_referral_document_upload(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('referral_letter.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->user)->post("/hms/referrals/{$this->referral->id}/documents", [
            'document_type' => 'referral_letter',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('referral_documents', [
            'referral_id' => $this->referral->id,
            'document_type' => 'referral_letter',
            'file_name' => 'referral_letter.pdf',
            'uploaded_by' => $this->user->id,
        ]);
    }

    public function test_referral_feedback_recording(): void
    {
        $response = $this->actingAs($this->user)->post("/hms/referrals/{$this->referral->id}/feedback", [
            'treatment_provided' => 'Surgical intervention and post-op care',
            'outcome' => 'Patient recovered fully',
            'feedback_date' => now()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('referral_feedback', [
            'referral_id' => $this->referral->id,
            'treatment_provided' => 'Surgical intervention and post-op care',
            'outcome' => 'Patient recovered fully',
            'feedback_by' => $this->user->id,
        ]);

        $this->assertDatabaseHas('referrals', [
            'id' => $this->referral->id,
            'status' => 'completed',
        ]);
    }

    public function test_referral_status_history_tracking(): void
    {
        $response = $this->actingAs($this->user)->post("/hms/referrals/{$this->referral->id}/feedback", [
            'treatment_provided' => 'Medication prescribed',
            'outcome' => 'Improved',
            'feedback_date' => now()->toDateString(),
        ]);

        $this->assertDatabaseHas('referral_status_history', [
            'referral_id' => $this->referral->id,
            'from_status' => 'pending',
            'to_status' => 'completed',
            'changed_by' => $this->user->id,
        ]);
    }
}
