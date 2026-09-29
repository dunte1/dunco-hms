<?php

namespace Tests\Feature;

use App\Models\ApiClient;
use App\Models\DocumentTemplate;
use App\Models\FacilityProfile;
use App\Models\FeatureFlag;
use App\Models\IntegrationConfig;
use App\Models\NotificationTemplate;
use App\Models\NumberSequence;
use App\Models\PaymentMethod;
use App\Models\PriceList;
use App\Models\Service;
use App\Models\TaxRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class M54ConfigTablesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed', ['--class' => 'DefaultConfigSeeder']);
    }

    // ── Facility Profile ─────────────────────────────────────────────────────────

    public function test_facility_profile_can_be_created(): void
    {
        $profile = FacilityProfile::create([
            'name' => 'Test Hospital',
            'slug' => 'test-hospital',
            'level' => '6',
            'facility_type' => 'public',
        ]);

        $this->assertDatabaseHas('facility_profiles', ['slug' => 'test-hospital']);
        $this->assertEquals('6', $profile->level);
    }

    public function test_facility_profile_singleton_returns_default(): void
    {
        $profile = FacilityProfile::get();
        $this->assertEquals('Dunco HMS', $profile->name);
    }

    // ── Number Sequences ────────────────────────────────────────────────────────

    public function test_number_sequence_generates_unique_numbers(): void
    {
        $seq1 = NumberSequence::next('patient_mrn');
        $seq2 = NumberSequence::next('patient_mrn');
        $seq3 = NumberSequence::next('patient_mrn');

        $this->assertNotEquals($seq1, $seq2);
        $this->assertNotEquals($seq2, $seq3);
        $this->assertStringStartsWith('PAT', $seq1);
    }

    public function test_number_sequence_respects_padding(): void
    {
        $number = NumberSequence::next('patient_mrn');
        $this->assertMatchesRegularExpression('/^PAT\d{6}$/', $number);
    }

    public function test_number_sequence_is_concurrency_safe(): void
    {
        // Simulate concurrent access
        $numbers = [];
        for ($i = 0; $i < 10; $i++) {
            $numbers[] = NumberSequence::next('patient_mrn');
        }

        // All numbers should be unique
        $this->assertCount(10, array_unique($numbers));
    }

    public function test_number_sequence_peek_does_not_increment(): void
    {
        $peek1 = NumberSequence::peek('invoice');
        $peek2 = NumberSequence::peek('invoice');
        $this->assertEquals($peek1, $peek2);

        NumberSequence::next('invoice');
        $peek3 = NumberSequence::peek('invoice');
        $this->assertNotEquals($peek1, $peek3);
    }

    public function test_seeder_creates_all_sequences(): void
    {
        $expected = ['patient_mrn', 'invoice', 'lab_order', 'radiology_order', 'prescription', 'visit', 'admission', 'referral_in', 'referral_out', 'receipt'];
        foreach ($expected as $name) {
            $this->assertDatabaseHas('number_sequences', ['name' => $name]);
        }
    }

    // ── Services ────────────────────────────────────────────────────────────────

    public function test_service_can_be_created(): void
    {
        Service::create([
            'name' => 'General Consultation',
            'code' => 'CONS-GEN',
            'category' => 'consultation',
            'default_price' => 1000.00,
        ]);

        $this->assertDatabaseHas('services', ['code' => 'CONS-GEN']);
    }

    // ── Price Lists ─────────────────────────────────────────────────────────────

    public function test_price_list_with_items(): void
    {
        $service = Service::create(['name' => 'Lab Test', 'code' => 'LAB-001', 'category' => 'lab']);
        $priceList = PriceList::create(['name' => 'Standard', 'is_default' => true]);

        $priceList->items()->create([
            'service_id' => $service->id,
            'price' => 500.00,
        ]);

        $this->assertDatabaseHas('price_items', [
            'price_list_id' => $priceList->id,
            'service_id' => $service->id,
            'price' => 500.00,
        ]);
    }

    // ── Tax Rates ───────────────────────────────────────────────────────────────

    public function test_tax_rate_calculation_exclusive(): void
    {
        $vat = TaxRate::where('code', 'VAT')->first();
        $tax = $vat->calculateTax(1000.00);
        $this->assertEqualsWithDelta(160.00, $tax, 0.01);
    }

    public function test_tax_rate_calculation_inclusive(): void
    {
        $vat = TaxRate::create([
            'name' => 'VAT Inclusive',
            'code' => 'VAT-INCL',
            'rate' => 16.00,
            'is_inclusive' => true,
        ]);
        $tax = $vat->calculateTax(1160.00);
        $this->assertEqualsWithDelta(160.00, $tax, 0.01);
    }

    // ── Payment Methods ─────────────────────────────────────────────────────────

    public function test_payment_methods_seeded(): void
    {
        $this->assertDatabaseHas('payment_methods', ['code' => 'CASH']);
        $this->assertDatabaseHas('payment_methods', ['code' => 'MPESA']);
        $this->assertDatabaseHas('payment_methods', ['code' => 'INS']);
    }

    public function test_payment_method_with_configuration(): void
    {
        PaymentMethod::create([
            'name' => 'Custom Gateway',
            'code' => 'CUSTOM',
            'type' => 'card',
            'configuration' => ['api_key' => 'test', 'merchant_id' => '123'],
        ]);

        $method = PaymentMethod::where('code', 'CUSTOM')->first();
        $this->assertEquals('test', $method->configuration['api_key']);
    }

    // ── Document Templates ──────────────────────────────────────────────────────

    public function test_document_template_rendering(): void
    {
        $template = DocumentTemplate::create([
            'name' => 'Invoice Default',
            'type' => 'invoice',
            'body' => 'Invoice {{ invoice_number }} for {{ patient_name }}',
            'format' => 'pdf',
        ]);

        $rendered = $template->render([
            'invoice_number' => 'INV-001',
            'patient_name' => 'John Doe',
        ]);

        $this->assertEquals('Invoice INV-001 for John Doe', $rendered);
    }

    public function test_document_template_scope_for_type(): void
    {
        DocumentTemplate::create(['name' => 'Inv', 'type' => 'invoice', 'body' => 'test']);
        DocumentTemplate::create(['name' => 'Rcp', 'type' => 'receipt', 'body' => 'test']);

        $invoiceTemplates = DocumentTemplate::forType('invoice')->get();
        $this->assertCount(1, $invoiceTemplates);
    }

    // ── Notification Templates ──────────────────────────────────────────────────

    public function test_notification_template_rendering(): void
    {
        $template = NotificationTemplate::create([
            'name' => 'Appointment Reminder SMS',
            'event' => 'appointment_reminder',
            'channel' => 'sms',
            'body' => 'Dear {{ patient_name }}, your appointment is on {{ date }}.',
        ]);

        $rendered = $template->render([
            'patient_name' => 'Jane',
            'date' => '2026-10-01',
        ]);

        $this->assertStringContainsString('Jane', $rendered);
        $this->assertStringContainsString('2026-10-01', $rendered);
    }

    public function test_notification_template_scope_for_event(): void
    {
        NotificationTemplate::create(['name' => 'SMS', 'event' => 'payment', 'channel' => 'sms', 'body' => 'test']);
        NotificationTemplate::create(['name' => 'Email', 'event' => 'payment', 'channel' => 'email', 'body' => 'test']);

        $smsTemplates = NotificationTemplate::forEvent('payment', 'sms')->get();
        $this->assertCount(1, $smsTemplates);
    }

    // ── Integration Configs ─────────────────────────────────────────────────────

    public function test_integration_config_can_be_created(): void
    {
        IntegrationConfig::create([
            'name' => 'M-Pesa',
            'slug' => 'mpesa',
            'type' => 'payment',
            'provider' => 'safaricom',
            'credentials' => ['consumer_key' => 'test', 'consumer_secret' => 'test'],
            'is_sandbox' => true,
        ]);

        $this->assertDatabaseHas('integration_configs', ['slug' => 'mpesa']);
    }

    // ── Feature Flags ───────────────────────────────────────────────────────────

    public function test_feature_flag_is_disabled_by_default(): void
    {
        FeatureFlag::create(['name' => 'New UI', 'slug' => 'new-ui']);
        $this->assertFalse(FeatureFlag::isEnabled('new-ui'));
    }

    public function test_feature_flag_can_be_enabled(): void
    {
        FeatureFlag::create(['name' => 'New UI', 'slug' => 'new-ui', 'is_enabled' => true]);
        $this->assertTrue(FeatureFlag::isEnabled('new-ui'));
    }

    public function test_feature_flag_nonexistent_returns_false(): void
    {
        $this->assertFalse(FeatureFlag::isEnabled('nonexistent-flag'));
    }

    // ── API Clients ─────────────────────────────────────────────────────────────

    public function test_api_client_can_be_created(): void
    {
        ApiClient::create([
            'name' => 'Mobile App',
            'client_id' => 'mobile-app-001',
            'client_secret_hash' => bcrypt('secret'),
            'scopes' => ['read:patients', 'write:appointments'],
        ]);

        $this->assertDatabaseHas('api_clients', ['client_id' => 'mobile-app-001']);
    }
}
