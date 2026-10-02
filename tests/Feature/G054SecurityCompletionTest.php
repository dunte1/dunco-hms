<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VisitorLog;
use App\Models\VisitorPass;
use App\Models\SecurityIncident;
use App\Models\LostFoundItem;
use App\Models\AccessEvent;
use App\Models\VehicleAccessRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class G054SecurityCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
        $this->user = User::factory()->create();
        $this->user->assignRole('Security Officer');
    }

    public function test_visitor_pass_creation_with_check_in(): void
    {
        $visitorLog = VisitorLog::create([
            'visitor_name' => 'John Doe',
            'visitor_phone' => '+254700000000',
            'visitor_type' => 'family',
            'purpose' => 'Patient visit',
            'check_in_time' => now(),
            'status' => 'checked_in',
        ]);

        $pass = VisitorPass::create([
            'visitor_log_id' => $visitorLog->id,
            'badge_number' => 'VP-001',
            'visit_purpose' => 'Patient visit',
            'ward_authorized' => 'General Ward',
            'check_in_time' => now(),
            'status' => 'active',
            'issued_by' => $this->user->id,
        ]);

        $this->assertDatabaseHas('visitor_passes', [
            'badge_number' => 'VP-001',
            'visit_purpose' => 'Patient visit',
            'status' => 'active',
            'issued_by' => $this->user->id,
        ]);

        $pass->update(['check_out_time' => now(), 'status' => 'completed']);
        $pass->refresh();
        $this->assertEquals('completed', $pass->status);
        $this->assertNotNull($pass->check_out_time);
    }

    public function test_security_incident_workflow(): void
    {
        $incident = SecurityIncident::create([
            'incident_number' => 'SI-20260928-0001',
            'incident_type' => 'theft',
            'severity' => 'high',
            'description' => 'Laptop stolen from ward 3.',
            'location' => 'Ward 3, Bed 12',
            'reported_by' => $this->user->id,
            'reported_at' => now(),
            'status' => 'reported',
        ]);

        $this->assertEquals('reported', $incident->status);

        $incident->update(['status' => 'investigating']);
        $incident->refresh();
        $this->assertEquals('investigating', $incident->status);

        $resolvingUser = User::factory()->create();
        $incident->update([
            'status' => 'resolved',
            'actions_taken' => 'CCTV reviewed. Suspect identified and handed to police.',
            'resolved_by' => $resolvingUser->id,
            'resolved_at' => now(),
        ]);
        $incident->refresh();

        $this->assertEquals('resolved', $incident->status);
        $this->assertEquals($resolvingUser->id, $incident->resolved_by);
        $this->assertNotNull($incident->resolved_at);
    }

    public function test_lost_found_item_lifecycle(): void
    {
        $item = LostFoundItem::create([
            'item_description' => 'Gold watch found near reception.',
            'location_found' => 'Main Reception',
            'date_found' => today(),
            'found_by' => $this->user->id,
            'status' => 'unclaimed',
        ]);

        $this->assertDatabaseHas('lost_found_items', [
            'item_description' => 'Gold watch found near reception.',
            'status' => 'unclaimed',
        ]);

        $item->update([
            'claimed_by_name' => 'Jane Smith',
            'claimed_by_id_number' => 'ID-12345',
            'claimed_at' => now(),
            'status' => 'claimed',
        ]);
        $item->refresh();

        $this->assertEquals('claimed', $item->status);
        $this->assertEquals('Jane Smith', $item->claimed_by_name);
        $this->assertNotNull($item->claimed_at);
    }

    public function test_access_event_recording(): void
    {
        $response = $this->actingAs($this->user)->post(route('access-events.store'), [
            'user_id' => $this->user->id,
            'event_type' => 'entry',
            'location' => 'Main Gate',
            'access_method' => 'badge',
            'device_id' => 'GATE-001',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('access_events', [
            'user_id' => $this->user->id,
            'event_type' => 'entry',
            'location' => 'Main Gate',
            'access_method' => 'badge',
        ]);
    }

    public function test_vehicle_access_tracking(): void
    {
        $response = $this->actingAs($this->user)->post(route('vehicles.store'), [
            'vehicle_registration' => 'KBA 123A',
            'driver_name' => 'Peter Kamau',
            'purpose' => 'Delivery',
            'destination' => 'Pharmacy Store',
            'gate_pass_number' => 'GP-001',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('vehicle_access_records', [
            'vehicle_registration' => 'KBA 123A',
            'driver_name' => 'Peter Kamau',
            'purpose' => 'Delivery',
            'recorded_by' => $this->user->id,
        ]);

        $vehicle = VehicleAccessRecord::where('vehicle_registration', 'KBA 123A')->first();

        $response = $this->actingAs($this->user)->post(route('vehicles.depart', $vehicle));
        $response->assertRedirect();

        $vehicle->refresh();
        $this->assertNotNull($vehicle->departure_time);
    }
}
