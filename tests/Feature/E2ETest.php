<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use App\Models\OtRoom;
use App\Models\OtSchedule;
use App\Models\DrugInteraction;
use App\Models\PatientAllergy;
use App\Models\CssdInstrument;
use App\Models\ConsentForm;
use App\Models\MrdFile;
use App\Models\Vaccine;
use App\Models\VaccinationRecord;
use App\Models\MortuaryRecord;
use App\Models\MedicalEquipment;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class E2ETest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Patient $patient;
    protected Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');

        $this->admin = User::factory()->create(['name' => 'Admin User']);
        $department = DoctorDepartment::create(['name' => 'General Surgery']);
        $this->doctor = Doctor::create([
            'first_name' => 'James', 'last_name' => 'Mwangi',
            'email' => 'doctor@test.com', 'phone' => '0712345678',
            'department_id' => $department->id,
        ]);
        $this->patient = Patient::create([
            'first_name' => 'John', 'last_name' => 'Doe',
            'email' => 'john@test.com', 'phone' => '0712345678',
            'date_of_birth' => '1990-05-15', 'gender' => 'male',
        ]);
    }

    /** @test */
    public function patient_management_crud()
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('hms.patients.index'));
        $response->assertStatus(200);
        $response = $this->get(route('hms.patients.show', $this->patient));
        $response->assertStatus(200);
    }

    /** @test */
    public function doctor_management()
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('hms.doctors.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function ot_scheduling_full_workflow()
    {
        $this->actingAs($this->admin);

        $room = OtRoom::create(['name' => 'OT-1', 'type' => 'general', 'status' => 'available']);
        $response = $this->get(route('hms.ot.rooms'));
        $response->assertStatus(200);

        $schedule = OtSchedule::create([
            'schedule_number' => OtSchedule::generateScheduleNumber(),
            'patient_id' => $this->patient->id,
            'ot_room_id' => $room->id,
            'surgeon_id' => $this->doctor->id,
            'procedure_name' => 'Appendectomy',
            'procedure_type' => 'elective',
            'anesthesia_type' => 'general',
            'scheduled_date' => now()->toDateString(),
            'scheduled_start' => '08:00',
            'status' => 'scheduled',
            'risk_level' => 'medium',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->get(route('hms.ot.index'));
        $response->assertStatus(200);

        $response = $this->get(route('hms.ot.show', $schedule));
        $response->assertStatus(200);

        $response = $this->post(route('hms.ot.time-in', $schedule));
        $this->assertDatabaseHas('ot_schedules', ['id' => $schedule->id, 'status' => 'in_progress']);

        $response = $this->post(route('hms.ot.time-out', $schedule));
        $this->assertDatabaseHas('ot_schedules', ['id' => $schedule->id, 'status' => 'completed']);

        $response = $this->get(route('hms.ot.schedule', ['date' => now()->format('Y-m-d')]));
        $response->assertStatus(200);

        $response = $this->get(route('hms.ot.instruments'));
        $response->assertStatus(200);
    }

    /** @test */
    public function drug_interaction_engine()
    {
        $this->actingAs($this->admin);

        $cat = MedicineCategory::create(['name' => 'General']);
        $drugA = Medicine::create(['name' => 'Warfarin', 'category_id' => $cat->id, 'dosage_form' => 'tablet', 'unit_price' => 10, 'stock_quantity' => 100]);
        $drugB = Medicine::create(['name' => 'Aspirin', 'category_id' => $cat->id, 'dosage_form' => 'tablet', 'unit_price' => 5, 'stock_quantity' => 100]);

        $interaction = DrugInteraction::create([
            'drug_a_id' => $drugA->id, 'drug_b_id' => $drugB->id,
            'severity' => 'critical', 'description' => 'Increased bleeding risk',
        ]);

        $this->assertDatabaseHas('drug_interactions', ['severity' => 'critical']);

        PatientAllergy::create([
            'patient_id' => $this->patient->id, 'allergen' => 'penicillin',
            'allergen_type' => 'drug', 'severity' => 'severe',
        ]);
        $this->assertDatabaseHas('patient_allergies', ['allergen' => 'penicillin']);
    }

    /** @test */
    public function cssd_management()
    {
        $this->actingAs($this->admin);
        CssdInstrument::create(['name' => 'Surgical Tray A', 'category' => 'Surgical', 'quantity' => 5]);
        $this->assertDatabaseHas('cssd_instruments', ['name' => 'Surgical Tray A']);
    }

    /** @test */
    public function consent_management()
    {
        $this->actingAs($this->admin);
        $consent = ConsentForm::create([
            'patient_id' => $this->patient->id, 'consent_type' => 'procedure',
            'procedure_name' => 'Appendectomy', 'status' => 'pending',
        ]);
        $this->assertDatabaseHas('consent_forms', ['procedure_name' => 'Appendectomy']);
        $response = $this->post(route('consent.sign', $consent));
        $this->assertDatabaseHas('consent_forms', ['id' => $consent->id, 'status' => 'signed']);
    }

    /** @test */
    public function mrd_management()
    {
        $this->actingAs($this->admin);
        $file = MrdFile::create([
            'patient_id' => $this->patient->id, 'file_number' => MrdFile::generateFileNumber(),
            'file_type' => 'discharge_summary', 'status' => 'in_library',
        ]);
        $this->assertDatabaseHas('mrd_files', ['id' => $file->id, 'status' => 'in_library']);
        $response = $this->post(route('mrd.issue', $file));
        $this->assertDatabaseHas('mrd_files', ['id' => $file->id, 'status' => 'issued']);
        $response = $this->post(route('mrd.return', $file));
        $this->assertDatabaseHas('mrd_files', ['id' => $file->id, 'status' => 'returned']);
    }

    /** @test */
    public function vaccination_management()
    {
        $this->actingAs($this->admin);
        $vaccine = Vaccine::create(['name' => 'COVID-19', 'manufacturer' => 'Pfizer', 'stock_quantity' => 100]);
        $this->assertDatabaseHas('vaccines', ['name' => 'COVID-19', 'stock_quantity' => 100]);

        $response = $this->post(route('vaccination.administer-store'), [
            'patient_id' => $this->patient->id,
            'vaccine_id' => $vaccine->id,
            'dose_number' => 1,
            'site' => 'left_arm',
        ]);

        $vaccine->refresh();
        $this->assertEquals(99, $vaccine->stock_quantity);
        $this->assertDatabaseHas('vaccination_records', ['patient_id' => $this->patient->id]);
    }

    /** @test */
    public function mortuary_management()
    {
        $this->actingAs($this->admin);
        $record = MortuaryRecord::create([
            'body_id' => 'MORT-001', 'received_at' => now(),
            'received_by' => $this->admin->id, 'status' => 'stored',
            'storage_location' => 'Cabinet A-1',
        ]);
        $this->assertDatabaseHas('mortuary_records', ['body_id' => 'MORT-001', 'status' => 'stored']);
    }

    /** @test */
    public function equipment_maintenance()
    {
        $this->actingAs($this->admin);
        $equipment = MedicalEquipment::create([
            'name' => 'X-Ray Machine', 'category' => 'diagnostic',
            'serial_number' => 'XR-001', 'status' => 'operational',
        ]);
        $this->assertDatabaseHas('medical_equipment', ['serial_number' => 'XR-001']);
    }

    /** @test */
    public function core_modules_accessible()
    {
        $this->actingAs($this->admin);
        $routes = [
            'hms.patients.index', 'hms.doctors.index', 'hms.appointments.index',
            'hms.beds.index', 'hms.ot.index', 'hms.ot.rooms',
        ];

        $failed = [];
        foreach ($routes as $route) {
            try {
                $response = $this->get(route($route));
                if ($response->status() !== 200) {
                    $failed[] = "{$route} (HTTP {$response->status()})";
                }
            } catch (\Exception $e) {
                $failed[] = "{$route} (Exception)";
            }
        }
        $this->assertEmpty($failed, "Failed routes: " . implode(', ', $failed));
    }

    /** @test */
    public function public_site_pages()
    {
        $pages = ['home', 'services', 'doctors', 'about', 'contact', 'features', 'book-appointment'];
        foreach ($pages as $page) {
            $response = $this->get(route($page));
            $response->assertStatus(200);
        }
    }

    /** @test */
    public function patient_portal()
    {
        $response = $this->get('/patient-portal/login');
        $response->assertStatus(200);
    }
}
