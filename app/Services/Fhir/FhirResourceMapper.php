<?php

namespace App\Services\Fhir;

/**
 * Standard FHIR R4 resource mappers for HMS domain models.
 *
 * IMPORTANT: These map to generic FHIR R4 resource shapes only.
 * They do NOT claim Kenya DHA/SHA Implementation Guide conformance.
 * Official Kenya IG profiles must be validated separately before production use.
 *
 * Architecture: HMS domain -> FHIR Mapping Layer -> FHIR R4 JSON
 * Validation, terminology mapping, consent and HIE transport are separate layers.
 */
class FhirResourceMapper
{
    /**
     * @param  \App\Models\Patient  $patient
     * @return array<string, mixed> FHIR R4 Patient resource
     */
    public function mapPatient(\App\Models\Patient $patient): array
    {
        $name = [
            'use' => 'official',
            'family' => $patient->last_name ?? '',
            'given' => array_filter([(string) ($patient->first_name ?? '')]),
        ];

        $telecom = [];
        if ($patient->phone) {
            $telecom[] = ['system' => 'phone', 'value' => $patient->phone, 'use' => 'mobile'];
        }
        if ($patient->email) {
            $telecom[] = ['system' => 'email', 'value' => $patient->email];
        }

        $identifier = [];
        if ($patient->patient_no) {
            $identifier[] = [
                'type' => ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v2-0203', 'code' => 'MR', 'display' => 'Medical record number']]],
                'system' => 'urn:oid:local:medical-record-number',
                'value' => $patient->patient_no,
            ];
        }
        if ($patient->national_id) {
            $identifier[] = [
                'type' => ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v2-0203', 'code' => 'NI', 'display' => 'National unique individual identifier']]],
                'system' => 'urn:oid:local:national-id',
                'value' => $patient->national_id,
            ];
        }
        if ($patient->dha_cr_id) {
            // Local reference to DHA Client Registry id — not a claim of live sync.
            $identifier[] = [
                'type' => ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v2-0203', 'code' => 'CR', 'display' => 'Client Registry']]],
                'system' => 'urn:oid:local:dha-client-registry',
                'value' => $patient->dha_cr_id,
            ];
        }

        $resource = [
            'resourceType' => 'Patient',
            'id' => (string) $patient->id,
            'meta' => ['source' => 'urn:oid:local:duncohms'],
            'identifier' => $identifier,
            'active' => true,
            'name' => [$name],
            'telecom' => $telecom,
        ];

        if ($patient->gender) {
            $resource['gender'] = strtolower((string) $patient->gender) === 'male'
                ? 'male'
                : (strtolower((string) $patient->gender) === 'female' ? 'female' : 'unknown');
        }

        if ($patient->dob) {
            $resource['birthDate'] = \Carbon\Carbon::parse($patient->dob)->toDateString();
        }

        return $resource;
    }

    /**
     * @param  \App\Models\LabRequest  $labRequest
     * @return array<string, mixed> FHIR R4 DiagnosticReport
     */
    public function mapDiagnosticReport(\App\Models\LabRequest $labRequest): array
    {
        $labRequest->loadMissing('items.labTest', 'patient');

        $results = [];
        foreach ($labRequest->items as $item) {
            $results[] = [
                'resourceType' => 'Observation',
                'status' => $this->mapObservationStatus($item->status ?? $labRequest->status),
                'code' => [
                    'text' => $item->labTest->name ?? 'Laboratory observation',
                ],
                'subject' => ['reference' => 'Patient/' . $labRequest->patient_id],
                'effectiveDateTime' => optional($labRequest->created_at)->toIso8601String(),
                'valueString' => $item->result ?? $item->value ?? null,
            ];
        }

        return [
            'resourceType' => 'DiagnosticReport',
            'id' => (string) $labRequest->id,
            'meta' => ['source' => 'urn:oid:local:duncohms'],
            'status' => $this->mapDiagnosticReportStatus($labRequest->status),
            'category' => [[
                'coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v2-0074', 'code' => 'LAB', 'display' => 'Laboratory']],
            ]],
            'code' => ['text' => 'Laboratory report'],
            'subject' => ['reference' => 'Patient/' . $labRequest->patient_id],
            'issued' => optional($labRequest->updated_at ?? $labRequest->created_at)->toIso8601String(),
            'result' => array_map(
                fn (array $obs, int $i) => ['reference' => 'Observation/' . $labRequest->id . '-' . $i],
                $results,
                array_keys($results)
            ),
            'contained' => array_values($results),
        ];
    }

    /**
     * @param  \App\Models\Prescription  $prescription
     * @return array<string, mixed> FHIR R4 MedicationRequest
     */
    public function mapMedicationRequest(\App\Models\Prescription $prescription): array
    {
        $prescription->loadMissing('items.medicine', 'patient');

        $medicationRequests = [];
        foreach ($prescription->items as $item) {
            $medicineName = $item->medicine->name ?? $item->medicine_name ?? 'Medication';
            $medicationRequests[] = [
                'resourceType' => 'MedicationRequest',
                'id' => $prescription->id . '-' . $item->id,
                'meta' => ['source' => 'urn:oid:local:duncohms'],
                'status' => $this->mapMedicationRequestStatus($prescription->status),
                'intent' => 'order',
                'medicationCodeableConcept' => ['text' => $medicineName],
                'subject' => ['reference' => 'Patient/' . $prescription->patient_id],
                'authoredOn' => optional($prescription->prescription_date ?? $prescription->created_at)->toDateString(),
                'dosageInstruction' => [[
                    'text' => trim(($item->dosage ?? '') . ' ' . ($item->frequency ?? '') . ' ' . ($item->duration_days ? $item->duration_days . ' days' : '')),
                ]],
            ];
        }

        return [
            'resourceType' => 'Bundle',
            'type' => 'collection',
            'entry' => array_map(
                fn (array $r) => ['resource' => $r],
                $medicationRequests
            ),
        ];
    }

    private function mapObservationStatus(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'completed', 'verified', 'released', 'validated' => 'final',
            'cancelled', 'void' => 'cancelled',
            default => 'preliminary',
        };
    }

    private function mapDiagnosticReportStatus(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'completed', 'verified', 'released' => 'final',
            'cancelled' => 'cancelled',
            default => 'registered',
        };
    }

    private function mapMedicationRequestStatus(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'cancelled', 'void' => 'cancelled',
            'dispensed', 'completed' => 'completed',
            'rejected' => 'stopped',
            default => 'active',
        };
    }
}
