<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\MrdFile;
use App\Models\IpdAdmission;
use App\Models\OpdVisit;
use App\Models\RecordRequest;
use App\Models\ScannedDocument;
use App\Models\IcdCodingRecord;
use App\Models\KhisReportSubmission;
use App\Models\DataQualityIssue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class G057MedicalRecordsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->patient = Patient::factory()->create();
    }

    public function test_record_request_workflow(): void
    {
        $mrdFile = MrdFile::create([
            'patient_id' => $this->patient->id,
            'file_number' => MrdFile::generateFileNumber(),
            'file_type' => 'discharge_summary',
            'status' => 'in_library',
        ]);

        $response = $this->actingAs($this->user)->post(route('records.requests.store'), [
            'patient_id' => $this->patient->id,
            'mrd_file_id' => $mrdFile->id,
            'request_type' => 'access',
            'reason' => 'Legal proceeding documentation',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $request = RecordRequest::first();
        $this->assertNotNull($request);
        $this->assertEquals($this->patient->id, $request->patient_id);
        $this->assertEquals($mrdFile->id, $request->mrd_file_id);
        $this->assertEquals('access', $request->request_type);
        $this->assertEquals($this->user->id, $request->requested_by);
        $this->assertEquals('pending', $request->status);

        // Approve
        $approver = User::factory()->create();
        $response = $this->actingAs($approver)->post(route('records.requests.approve', $request));

        $response->assertRedirect();
        $request->refresh();
        $this->assertEquals('approved', $request->status);
        $this->assertEquals($approver->id, $request->approved_by);
        $this->assertNotNull($request->approved_at);

        // Release
        $response = $this->actingAs($approver)->post(route('records.requests.release', $request));

        $response->assertRedirect();
        $request->refresh();
        $this->assertEquals('released', $request->status);
        $this->assertNotNull($request->released_at);
    }

    public function test_scanned_document_upload(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('discharge.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->user)->post(route('records.scan.store'), [
            'patient_id' => $this->patient->id,
            'document_type' => 'discharge_summary',
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $doc = ScannedDocument::first();
        $this->assertNotNull($doc);
        $this->assertEquals($this->patient->id, $doc->patient_id);
        $this->assertEquals('discharge_summary', $doc->document_type);
        $this->assertEquals('discharge.pdf', $doc->file_name);
        $this->assertEquals('application/pdf', $doc->mime_type);
        $this->assertEquals($this->user->id, $doc->uploaded_by);

        Storage::disk('public')->assertExists($doc->file_path);
    }

    public function test_icd_coding_creation_and_approval(): void
    {
        $response = $this->actingAs($this->user)->post(route('records.coding.store'), [
            'patient_id' => $this->patient->id,
            'primary_diagnosis_code' => 'A09',
            'primary_diagnosis_desc' => 'Infectious gastroenteritis',
            'secondary_diagnosis_codes' => ['E11.9', 'I10'],
            'procedure_codes' => ['99213'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $coding = IcdCodingRecord::first();
        $this->assertNotNull($coding);
        $this->assertEquals($this->patient->id, $coding->patient_id);
        $this->assertEquals('A09', $coding->primary_diagnosis_code);
        $this->assertEquals('Infectious gastroenteritis', $coding->primary_diagnosis_desc);
        $this->assertEquals(['E11.9', 'I10'], $coding->secondary_diagnosis_codes);
        $this->assertEquals(['99213'], $coding->procedure_codes);
        $this->assertEquals('pending', $coding->coding_status);
        $this->assertEquals($this->user->id, $coding->coded_by);
        $this->assertNotNull($coding->coded_at);

        // Approve
        $reviewer = User::factory()->create();
        $response = $this->actingAs($reviewer)->post(route('records.coding.approve', $coding));

        $response->assertRedirect();
        $coding->refresh();
        $this->assertEquals('approved', $coding->coding_status);
        $this->assertEquals($reviewer->id, $coding->reviewed_by);
        $this->assertNotNull($coding->reviewed_at);
    }

    public function test_khis_report_creation_and_submission(): void
    {
        $response = $this->actingAs($this->user)->post(route('reports.khis.store'), [
            'report_type' => 'moh_731',
            'reporting_period_month' => 9,
            'reporting_period_year' => 2026,
            'facility_code' => 'FAC-001',
            'total_patients' => 150,
            'total_visits' => 320,
            'report_data' => [
                'hiv_new' => 12,
                'hiv_existing' => 85,
                'malaria' => 45,
                'tb' => 8,
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $report = KhisReportSubmission::first();
        $this->assertNotNull($report);
        $this->assertEquals('moh_731', $report->report_type);
        $this->assertEquals(9, $report->reporting_period_month);
        $this->assertEquals(2026, $report->reporting_period_year);
        $this->assertEquals('FAC-001', $report->facility_code);
        $this->assertEquals(150, $report->total_patients);
        $this->assertEquals(320, $report->total_visits);
        $this->assertEquals('draft', $report->status);
        $this->assertEquals($this->user->id, $report->submitted_by);

        // Submit
        $response = $this->actingAs($this->user)->post(route('reports.khis.submit', $report));

        $response->assertRedirect();
        $report->refresh();
        $this->assertEquals('submitted', $report->status);
        $this->assertNotNull($report->submitted_at);
    }

    public function test_data_quality_issue_tracking(): void
    {
        $response = $this->actingAs($this->user)->post(route('records.data-quality.store'), [
            'issue_type' => 'missing_field',
            'patient_id' => $this->patient->id,
            'table_name' => 'patients',
            'field_name' => 'national_id',
            'issue_description' => 'Patient missing national ID number',
            'severity' => 'medium',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $issue = DataQualityIssue::first();
        $this->assertNotNull($issue);
        $this->assertEquals('missing_field', $issue->issue_type);
        $this->assertEquals($this->patient->id, $issue->patient_id);
        $this->assertEquals('patients', $issue->table_name);
        $this->assertEquals('national_id', $issue->field_name);
        $this->assertEquals('medium', $issue->severity);
        $this->assertEquals('open', $issue->status);

        // Resolve
        $resolver = User::factory()->create();
        $response = $this->actingAs($resolver)->post(route('records.data-quality.resolve', $issue), [
            'resolution_notes' => 'Patient provided national ID during next visit',
        ]);

        $response->assertRedirect();
        $issue->refresh();
        $this->assertEquals('resolved', $issue->status);
        $this->assertEquals($resolver->id, $issue->resolved_by);
        $this->assertNotNull($issue->resolved_at);
        $this->assertEquals('Patient provided national ID during next visit', $issue->resolution_notes);
    }
}
