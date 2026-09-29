<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SafetyIncident;
use App\Models\FireEquipmentRecord;
use App\Models\FireInspectionRecord;
use App\Models\EmergencyDrillRecord;
use App\Models\RiskAssessmentRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G056FireSafetyTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_safety_incident_workflow(): void
    {
        $incident = SafetyIncident::create([
            'incident_number' => 'FSI-20260928-0001',
            'incident_type' => 'fire',
            'severity' => 'high',
            'description' => 'Small fire in kitchen area due to electrical fault.',
            'location' => 'Kitchen, Block A',
            'reported_by' => $this->user->id,
            'reported_at' => now(),
            'first_aid_given' => true,
            'hospital_visit' => false,
            'status' => 'reported',
        ]);

        $this->assertEquals('reported', $incident->status);

        $incident->update(['status' => 'investigating']);
        $incident->refresh();
        $this->assertEquals('investigating', $incident->status);

        $resolvingUser = User::factory()->create();
        $incident->update([
            'status' => 'resolved',
            'resolution_notes' => 'Electrical fault repaired. Extinguisher used successfully.',
            'resolved_by' => $resolvingUser->id,
            'resolved_at' => now(),
        ]);
        $incident->refresh();

        $this->assertEquals('resolved', $incident->status);
        $this->assertEquals($resolvingUser->id, $incident->resolved_by);
        $this->assertNotNull($incident->resolved_at);
    }

    public function test_fire_equipment_record_creation(): void
    {
        FireEquipmentRecord::create([
            'equipment_type' => 'extinguisher',
            'location' => 'Ward 1, Corridor',
            'serial_number' => 'EXT-001',
            'last_inspection_date' => '2026-09-01',
            'next_inspection_date' => '2027-03-01',
            'status' => 'good',
            'notes' => 'Fully charged.',
        ]);

        $this->assertDatabaseHas('fire_equipment_records', [
            'equipment_type' => 'extinguisher',
            'location' => 'Ward 1, Corridor',
            'serial_number' => 'EXT-001',
            'status' => 'good',
        ]);
    }

    public function test_fire_inspection_recording(): void
    {
        FireInspectionRecord::create([
            'inspection_date' => '2026-09-28',
            'inspector_name' => 'John Inspector',
            'equipment_checked' => 25,
            'equipment_passed' => 23,
            'deficiencies_found' => '2 extinguishers low pressure.',
            'corrective_actions' => 'Replaced 2 extinguishers.',
            'status' => 'conditional',
            'next_inspection_date' => '2027-03-28',
        ]);

        $this->assertDatabaseHas('fire_inspection_records', [
            'inspector_name' => 'John Inspector',
            'equipment_checked' => 25,
            'equipment_passed' => 23,
            'status' => 'conditional',
        ]);
    }

    public function test_emergency_drill_recording(): void
    {
        EmergencyDrillRecord::create([
            'drill_type' => 'fire',
            'drill_date' => '2026-09-28',
            'participants_count' => 150,
            'assembly_point' => 'Parking Lot B',
            'duration_minutes' => 12,
            'performance_rating' => 'good',
            'lessons_learned' => 'Evacuation time acceptable. Need to improve ward 3 response.',
            'conducted_by' => $this->user->id,
        ]);

        $this->assertDatabaseHas('emergency_drill_records', [
            'drill_type' => 'fire',
            'participants_count' => 150,
            'performance_rating' => 'good',
            'conducted_by' => $this->user->id,
        ]);
    }

    public function test_risk_assessment_creation(): void
    {
        RiskAssessmentRecord::create([
            'area_or_activity' => 'Operating Theatre',
            'hazard_identified' => 'Exposure to anaesthetic gases.',
            'risk_level' => 'high',
            'existing_controls' => 'Scavenging system, ventilation.',
            'additional_controls' => 'Regular air quality monitoring.',
            'likelihood' => 'possible',
            'consequence' => 'major',
            'residual_risk_level' => 'moderate',
            'assessed_by' => $this->user->id,
            'assessment_date' => '2026-09-28',
            'next_review_date' => '2027-03-28',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('risk_assessment_records', [
            'area_or_activity' => 'Operating Theatre',
            'risk_level' => 'high',
            'residual_risk_level' => 'moderate',
            'assessed_by' => $this->user->id,
            'status' => 'active',
        ]);
    }
}
