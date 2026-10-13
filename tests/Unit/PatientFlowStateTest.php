<?php

namespace Tests\Unit;

use App\Models\OpdVisit;
use App\Services\PatientFlowService;
use PHPUnit\Framework\TestCase;

class PatientFlowStateTest extends TestCase
{
    public function test_opd_transition_map_is_complete(): void
    {
        $expected = [
            'registered', 'triaged', 'in_consultation', 'lab_pending',
            'pharmacy_pending', 'billing_pending', 'completed', 'discharged',
        ];

        $this->assertSame($expected, OpdVisit::STATUSES);

        foreach ($expected as $status) {
            $this->assertArrayHasKey($status, OpdVisit::TRANSITIONS, "Missing transitions for {$status}");
        }
    }

    public function test_completed_and_discharged_are_terminal(): void
    {
        $this->assertSame([], OpdVisit::TRANSITIONS['completed']);
        $this->assertSame([], OpdVisit::TRANSITIONS['discharged']);
    }

    public function test_service_transition_map_matches_model(): void
    {
        $this->assertSame(OpdVisit::TRANSITIONS, PatientFlowService::OPD_TRANSITIONS);
    }

    public function test_happy_path_transitions_are_allowed(): void
    {
        $patient = new OpdVisit(['status' => 'registered']);
        $this->assertTrue($patient->canTransitionTo('triaged'));
        $this->assertTrue($patient->canTransitionTo('in_consultation'));
        $this->assertTrue($patient->canTransitionTo('lab_pending'));
        $this->assertTrue($patient->canTransitionTo('pharmacy_pending'));
        $this->assertTrue($patient->canTransitionTo('billing_pending'));
        $this->assertTrue($patient->canTransitionTo('completed'));
    }

    public function test_illegal_backwards_transitions_blocked(): void
    {
        $visit = new OpdVisit(['status' => 'completed']);
        $this->assertFalse($visit->canTransitionTo('registered'));
        $this->assertFalse($visit->canTransitionTo('in_consultation'));
        $this->assertFalse($visit->canTransitionTo('lab_pending'));

        $visit = new OpdVisit(['status' => 'billing_pending']);
        $this->assertFalse($visit->canTransitionTo('registered'));
        $this->assertFalse($visit->canTransitionTo('triaged'));
    }

    public function test_same_status_is_always_allowed(): void
    {
        $visit = new OpdVisit(['status' => 'in_consultation']);
        $this->assertTrue($visit->canTransitionTo('in_consultation'));
    }

    public function test_queue_map_covers_all_statuses(): void
    {
        foreach (OpdVisit::STATUSES as $status) {
            $this->assertArrayHasKey($status, PatientFlowService::QUEUE_FOR_OPD);
        }
    }

    public function test_document_service_document_types_count(): void
    {
        $types = \App\Services\DocumentService::documentTypes();
        $this->assertGreaterThanOrEqual(40, count($types), 'DocumentService should list many document types');
    }

    public function test_invoice_number_format_regex(): void
    {
        $this->assertMatchesRegularExpression('/^INV-\d{6}-\d{6}$/', 'INV-202610-000001');
    }
}
