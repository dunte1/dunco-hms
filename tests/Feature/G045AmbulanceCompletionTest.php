<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Ambulance;
use App\Models\AmbulanceCrew;
use App\Models\AmbulanceTrip;
use App\Models\AmbulanceFuelLog;
use App\Models\AmbulanceMaintenanceRecord;
use App\Models\PatientHandoverRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G045AmbulanceCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Ambulance $ambulance;
    private Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->ambulance = Ambulance::create([
            'vehicle_number' => 'AMB-001',
            'driver_name' => 'John Driver',
            'driver_phone' => '0712345678',
            'vehicle_type' => 'advanced',
            'is_available' => true,
            'status' => 'available',
        ]);
        $this->patient = Patient::factory()->create();
    }

    public function test_crew_assignment(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ambulance.crews.store'), [
            'ambulance_id' => $this->ambulance->id,
            'user_id' => $this->user->id,
            'role' => 'driver',
            'is_primary' => true,
            'start_date' => now()->toDateString(),
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ambulance_crews', [
            'ambulance_id' => $this->ambulance->id,
            'user_id' => $this->user->id,
            'role' => 'driver',
            'is_primary' => true,
            'is_active' => true,
        ]);
    }

    public function test_trip_creation_and_completion(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ambulance.trips.store'), [
            'ambulance_id' => $this->ambulance->id,
            'patient_id' => $this->patient->id,
            'pickup_location' => '123 Main St, Nairobi',
            'dropoff_location' => 'KNH Hospital, Nairobi',
            'trip_type' => 'emergency',
            'departure_time' => now()->toDateTimeString(),
        ]);

        $response->assertRedirect();
        $trip = AmbulanceTrip::where('ambulance_id', $this->ambulance->id)->first();
        $this->assertNotNull($trip);
        $this->assertEquals('dispatched', $trip->status);
        $this->assertEquals('emergency', $trip->trip_type);

        $response = $this->actingAs($this->user)->post(route('hms.ambulance.trips.complete', $trip), [
            'arrival_time' => now()->addMinutes(30)->toDateTimeString(),
            'distance_km' => 12.5,
        ]);

        $response->assertRedirect();
        $trip->refresh();
        $this->assertEquals('completed', $trip->status);
        $this->assertNotNull($trip->arrival_time);
        $this->assertEquals('12.50', $trip->distance_km);
    }

    public function test_fuel_log_recording(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ambulance.fuel.store'), [
            'ambulance_id' => $this->ambulance->id,
            'fill_date' => now()->toDateString(),
            'liters' => 45.5,
            'cost' => 6500.00,
            'odometer_km' => 15230,
            'station' => 'Kenol Kibera',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ambulance_fuel_logs', [
            'ambulance_id' => $this->ambulance->id,
            'liters' => 45.5,
            'cost' => 6500.00,
            'odometer_km' => 15230,
            'station' => 'Kenol Kibera',
            'recorded_by' => $this->user->id,
        ]);
    }

    public function test_maintenance_record_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ambulance.maintenance.store'), [
            'ambulance_id' => $this->ambulance->id,
            'maintenance_type' => 'preventive',
            'description' => 'Regular 10,000km service - oil change, brake inspection, tire rotation',
            'service_date' => now()->toDateString(),
            'next_service_date' => now()->addMonths(6)->toDateString(),
            'cost' => 15000.00,
            'provider' => 'AutoCare Garage',
            'status' => 'completed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ambulance_maintenance_records', [
            'ambulance_id' => $this->ambulance->id,
            'maintenance_type' => 'preventive',
            'status' => 'completed',
            'cost' => 15000.00,
            'provider' => 'AutoCare Garage',
        ]);
    }

    public function test_patient_handover_workflow(): void
    {
        $trip = AmbulanceTrip::create([
            'ambulance_id' => $this->ambulance->id,
            'patient_id' => $this->patient->id,
            'pickup_location' => '123 Main St',
            'dropoff_location' => 'KNH Hospital',
            'status' => 'completed',
            'trip_type' => 'transfer',
            'departure_time' => now()->subHour(),
            'arrival_time' => now(),
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.ambulance.trips.handover.store', $trip), [
            'patient_id' => $this->patient->id,
            'receiving_facility' => 'KNH Hospital',
            'receiving_person' => 'Dr. Wafula',
            'receiving_department' => 'Emergency',
            'clinical_summary' => 'Male, 45yo, road traffic accident. GCS 14, BP 110/70, HR 98. Right femur fracture. Morphine 10mg IV given.',
            'handover_time' => now()->toDateTimeString(),
            'received_by_name' => 'Nurse Achieng',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('patient_handover_records', [
            'trip_id' => $trip->id,
            'patient_id' => $this->patient->id,
            'handed_over_by' => $this->user->id,
            'receiving_facility' => 'KNH Hospital',
            'status' => 'pending',
        ]);
    }
}
