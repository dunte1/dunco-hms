<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Store;
use App\Models\Rfq;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\SupplierInvoice;
use App\Models\StockIssue;
use App\Models\Requisition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class G064ProcurementCompletionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Supplier $supplier;
    private Store $store;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->supplier = Supplier::create([
            'supplier_code' => 'SUP-' . uniqid(),
            'name' => 'Test Supplier',
            'phone' => '0700000000',
            'address' => 'Test Address',
            'supplier_type' => 'general',
            'payment_terms' => 'credit_30',
            'status' => 'active',
        ]);
        $this->store = Store::create([
            'name' => 'Main Store',
            'code' => 'MAIN',
            'type' => 'main',
            'is_main' => true,
            'status' => 'active',
        ]);
    }

    public function test_rfq_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.procurement.rfqs.store'), [
            'title' => 'Medical Supplies RFQ',
            'description' => 'Quarterly medical supplies',
            'issued_date' => now()->toDateString(),
            'closing_date' => now()->addDays(14)->toDateString(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $rfq = Rfq::first();
        $this->assertNotNull($rfq);
        $this->assertStringStartsWith('RFQ-', $rfq->rfq_number);
        $this->assertEquals('Medical Supplies RFQ', $rfq->title);
        $this->assertEquals('draft', $rfq->status);
        $this->assertEquals($this->user->id, $rfq->issued_by);
    }

    public function test_rfq_close(): void
    {
        $rfq = Rfq::create([
            'title' => 'Test RFQ',
            'issued_date' => now()->toDateString(),
            'status' => 'sent',
            'issued_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.procurement.rfqs.close', $rfq));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $rfq->refresh();
        $this->assertEquals('closed', $rfq->status);
    }

    public function test_rfq_close_rejects_non_sent(): void
    {
        $rfq = Rfq::create([
            'title' => 'Draft RFQ',
            'issued_date' => now()->toDateString(),
            'status' => 'draft',
            'issued_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.procurement.rfqs.close', $rfq));

        $response->assertSessionHas('error');
        $rfq->refresh();
        $this->assertEquals('draft', $rfq->status);
    }

    public function test_quotation_submission(): void
    {
        $rfq = Rfq::create([
            'title' => 'Test RFQ for Quote',
            'issued_date' => now()->toDateString(),
            'status' => 'draft',
            'issued_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.procurement.quotations.store', $rfq), [
            'supplier_id' => $this->supplier->id,
            'total_amount' => 25000,
            'valid_until' => now()->addDays(30)->toDateString(),
            'items' => [
                ['product_description' => 'Surgical Gloves', 'quantity' => 100, 'unit_price' => 50, 'delivery_days' => 7],
                ['product_description' => 'Face Masks', 'quantity' => 200, 'unit_price' => 25, 'delivery_days' => 5],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $quotation = Quotation::first();
        $this->assertNotNull($quotation);
        $this->assertEquals($rfq->id, $quotation->rfq_id);
        $this->assertEquals($this->supplier->id, $quotation->supplier_id);
        $this->assertEquals(25000, (float) $quotation->total_amount);
        $this->assertEquals('received', $quotation->status);

        $items = $quotation->items;
        $this->assertCount(2, $items);
        $this->assertEquals('Surgical Gloves', $items->first()->product_description);

        $rfq->refresh();
        $this->assertEquals('sent', $rfq->status);
    }

    public function test_quotation_acceptance(): void
    {
        $rfq = Rfq::create([
            'title' => 'Acceptance Test RFQ',
            'issued_date' => now()->toDateString(),
            'status' => 'sent',
            'issued_by' => $this->user->id,
        ]);

        $quotation = Quotation::create([
            'rfq_id' => $rfq->id,
            'supplier_id' => $this->supplier->id,
            'total_amount' => 15000,
            'valid_until' => now()->addDays(30)->toDateString(),
            'status' => 'received',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.procurement.quotations.accept', $quotation));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $quotation->refresh();
        $this->assertEquals('accepted', $quotation->status);

        $rfq->refresh();
        $this->assertEquals('closed', $rfq->status);
    }

    public function test_quotation_rejection(): void
    {
        $rfq = Rfq::create([
            'title' => 'Rejection Test RFQ',
            'issued_date' => now()->toDateString(),
            'status' => 'sent',
            'issued_by' => $this->user->id,
        ]);

        $quotation = Quotation::create([
            'rfq_id' => $rfq->id,
            'supplier_id' => $this->supplier->id,
            'total_amount' => 15000,
            'valid_until' => now()->addDays(30)->toDateString(),
            'status' => 'received',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.procurement.quotations.reject', $quotation));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $quotation->refresh();
        $this->assertEquals('rejected', $quotation->status);
    }

    public function test_supplier_invoice_creation(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.procurement.supplier-invoices.store'), [
            'invoice_number' => 'INV-TEST-001',
            'supplier_id' => $this->supplier->id,
            'amount' => 50000,
            'tax_amount' => 8000,
            'total' => 58000,
            'invoice_date' => now()->toDateString(),
            'notes' => 'Test invoice',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $invoice = SupplierInvoice::first();
        $this->assertNotNull($invoice);
        $this->assertEquals('INV-TEST-001', $invoice->invoice_number);
        $this->assertEquals($this->supplier->id, $invoice->supplier_id);
        $this->assertEquals(50000, (float) $invoice->amount);
        $this->assertEquals(8000, (float) $invoice->tax_amount);
        $this->assertEquals(58000, (float) $invoice->total);
        $this->assertEquals('received', $invoice->status);
    }

    public function test_supplier_invoice_verification(): void
    {
        $invoice = SupplierInvoice::create([
            'invoice_number' => 'INV-VERIFY-001',
            'supplier_id' => $this->supplier->id,
            'amount' => 30000,
            'tax_amount' => 4800,
            'total' => 34800,
            'invoice_date' => now()->toDateString(),
            'status' => 'received',
        ]);

        $response = $this->actingAs($this->user)->post(route('hms.procurement.supplier-invoices.verify', $invoice));

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $invoice->refresh();
        $this->assertEquals('verified', $invoice->status);
        $this->assertEquals($this->user->id, $invoice->verified_by);
        $this->assertNotNull($invoice->verified_at);
    }

    public function test_stock_issue_recording(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.stores.issues.store'), [
            'store_id' => $this->store->id,
            'issued_to_user_id' => $this->user->id,
            'issued_to_department' => 'Nursing',
            'items' => [
                ['description' => 'Bandages', 'quantity' => 50, 'unit' => 'packs'],
                ['description' => 'Antiseptic', 'quantity' => 10, 'unit' => 'bottles'],
            ],
            'notes' => 'Ward supplies',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $issue = StockIssue::first();
        $this->assertNotNull($issue);
        $this->assertStringStartsWith('SI-', $issue->issue_number);
        $this->assertEquals($this->store->id, $issue->store_id);
        $this->assertEquals($this->user->id, $issue->issued_to_user_id);
        $this->assertEquals('Nursing', $issue->issued_to_department);
        $this->assertEquals('issued', $issue->status);
        $this->assertNotNull($issue->issued_at);
        $this->assertCount(2, $issue->items);
    }
}
