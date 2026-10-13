<?php

namespace Tests\Feature\Document;

use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\LabRequest;
use App\Models\Patient;
use App\Services\DocumentService;
use App\Services\InvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_document_service_branding_returns_array(): void
    {
        $branding = DocumentService::branding();

        $this->assertIsArray($branding);
        $this->assertArrayHasKey('hospital_name', $branding);
        $this->assertArrayHasKey('currency_symbol', $branding);
        $this->assertArrayHasKey('primary_color', $branding);
    }

    public function test_document_service_lists_document_types(): void
    {
        $types = DocumentService::documentTypes();

        $this->assertIsArray($types);
        $this->assertArrayHasKey('invoice', $types);
        $this->assertArrayHasKey('lab-report', $types);
        $this->assertArrayHasKey('discharge-summary', $types);
        $this->assertArrayHasKey('prescription', $types);
        $this->assertGreaterThan(20, count($types));
    }

    public function test_invoice_pdf_string_generates_valid_pdf(): void
    {
        $patient = Patient::factory()->create(['patient_no' => 'PATDOC001']);
        $doctor = Doctor::factory()->create();

        $invoice = Invoice::create([
            'invoice_number' => 'INV-TEST-001',
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'subtotal' => 1000,
            'tax_amount' => 160,
            'discount_amount' => 0,
            'total_amount' => 1160,
            'paid_amount' => 0,
            'balance_amount' => 1160,
            'status' => 'pending',
        ]);

        $invoice->items()->create([
            'description' => 'Consultation',
            'quantity' => 1,
            'unit_price' => 1000,
            'total_price' => 1000,
            'item_type' => 'service',
        ]);

        $service = new InvoiceService();
        $pdf = $service->generatePdf($invoice);
        $output = $pdf->output();

        $this->assertNotEmpty($output);
        $this->assertStringStartsWith('%PDF', $output, 'Invoice PDF should start with %PDF');
        $this->assertGreaterThan(500, strlen($output), 'PDF should have reasonable size');
    }

    public function test_invoice_service_generate_invoice_number(): void
    {
        $service = new InvoiceService();
        $number = $service->generateInvoiceNumber();

        $this->assertMatchesRegularExpression('/^INV-\d{6}-\d{6}$/', $number);
    }

    public function test_invoice_service_create_invoice_with_items(): void
    {
        $patient = Patient::factory()->create(['patient_no' => 'PATDOC002']);
        $service = new InvoiceService();

        $invoice = $service->createInvoice([
            'patient_id' => $patient->id,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
        ], [
            [
                'description' => 'Lab test',
                'quantity' => 2,
                'unit_price' => 250,
                'item_type' => 'lab_test',
            ],
        ]);

        $this->assertSame(500, (float) $invoice->subtotal);
        $this->assertSame(500, (float) $invoice->total_amount);
        $this->assertCount(1, $invoice->items);
        $this->assertSame('pending', $invoice->status);
    }

    public function test_lab_report_pdf_view_exists_and_renders(): void
    {
        $this->assertTrue(view()->exists('hms.laboratory.lab-report-pdf'));

        $patient = Patient::factory()->create(['patient_no' => 'PATDOC003']);
        $lab = LabRequest::create([
            'request_number' => 'LAB-DOC-003',
            'patient_id' => $patient->id,
            'request_date' => now()->toDateString(),
            'status' => 'completed',
            'results_notes' => 'Normal',
        ]);

        $html = view('hms.laboratory.lab-report-pdf', [
            'labRequest' => $lab->load(['patient', 'doctor', 'items.labTest']),
            'branding' => DocumentService::branding(),
        ])->render();

        $this->assertStringContainsString('LAB-DOC-003', $html);
    }

    public function test_invoice_pdf_view_exists(): void
    {
        $this->assertTrue(view()->exists('hms.billing.invoices.pdf'));
    }

    public function test_discharge_pdf_view_exists(): void
    {
        $this->assertTrue(view()->exists('hms.discharge-summary.discharge-pdf'));
    }

    public function test_email_template_views_exist(): void
    {
        $templates = [
            'emails.patient-welcome',
            'emails.lab-results-ready',
            'emails.invoice-created',
            'emails.appointment-confirmation',
            'emails.payment-receipt',
            'emails.leave-approved',
            'emails.leave-rejected',
            'emails.daily-summary',
        ];

        foreach ($templates as $template) {
            $this->assertTrue(view()->exists($template), "Email template {$template} should exist");
        }
    }

    public function test_invoice_pdf_string_via_document_service(): void
    {
        $patient = Patient::factory()->create(['patient_no' => 'PATDOC004']);
        $invoice = Invoice::create([
            'invoice_number' => 'INV-DOC-004',
            'patient_id' => $patient->id,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'subtotal' => 100,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => 100,
            'paid_amount' => 0,
            'balance_amount' => 100,
            'status' => 'pending',
        ]);
        $invoice->items()->create([
            'description' => 'Service',
            'quantity' => 1,
            'unit_price' => 100,
            'total_price' => 100,
        ]);

        $pdfString = DocumentService::generatePdfString('hms.billing.invoices.pdf', [
            'invoice' => $invoice->load(['patient', 'doctor', 'items', 'payments']),
        ]);

        $this->assertStringStartsWith('%PDF', $pdfString);
    }
}
