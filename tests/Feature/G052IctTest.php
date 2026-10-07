<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ItAsset;
use App\Models\ItTicket;
use App\Models\BackupRecord;
use App\Models\SoftwareLicence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class G052IctTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'manage backups']);
        $this->user = User::factory()->create();
        $this->user->givePermissionTo('manage backups');
    }

    public function test_it_asset_creation_and_tracking(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ict.assets.store'), [
            'name' => 'Dell OptiPlex 7090',
            'type' => 'desktop',
            'manufacturer' => 'Dell',
            'model' => 'OptiPlex 7090',
            'serial_number' => 'DL-2026-001',
            'purchase_date' => '2026-01-15',
            'warranty_expiry' => '2029-01-15',
            'location' => 'Admin Office',
            'assigned_to_user_id' => $this->user->id,
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('it_assets', [
            'name' => 'Dell OptiPlex 7090',
            'type' => 'desktop',
            'manufacturer' => 'Dell',
            'serial_number' => 'DL-2026-001',
            'assigned_to_user_id' => $this->user->id,
            'status' => 'active',
        ]);

        $asset = ItAsset::where('serial_number', 'DL-2026-001')->first();
        $this->assertStringStartsWith('ICT-', $asset->asset_number);

        $response = $this->actingAs($this->user)->get(route('hms.ict.assets.index'));
        $response->assertOk();
    }

    public function test_it_ticket_workflow(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ict.tickets.store'), [
            'subject' => 'Printer not working',
            'description' => 'The HP printer on 3rd floor is not printing.',
            'category' => 'hardware',
            'priority' => 'high',
            'assigned_to' => $this->user->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('it_tickets', [
            'subject' => 'Printer not working',
            'category' => 'hardware',
            'priority' => 'high',
            'status' => 'open',
            'reported_by' => $this->user->id,
        ]);

        $ticket = ItTicket::where('subject', 'Printer not working')->first();
        $this->assertStringStartsWith('TKT-', $ticket->ticket_number);

        $ticket->update(['status' => 'in_progress']);
        $ticket->refresh();
        $this->assertEquals('in_progress', $ticket->status);

        $response = $this->actingAs($this->user)->post(route('hms.ict.tickets.resolve', $ticket), [
            'resolution_notes' => 'Printer driver reinstalled. Working now.',
        ]);

        $response->assertRedirect();
        $ticket->refresh();
        $this->assertEquals('resolved', $ticket->status);
        $this->assertNotNull($ticket->resolved_at);
        $this->assertEquals('Printer driver reinstalled. Working now.', $ticket->resolution_notes);

        $response = $this->actingAs($this->user)->get(route('hms.ict.tickets.index'));
        $response->assertOk();
    }

    public function test_backup_record_creation_and_verification(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ict.backups.store'), [
            'backup_type' => 'full',
            'backup_location' => '/backups/full_20260928.sql',
            'file_size_mb' => 1024.50,
            'notes' => 'Weekly full backup',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('backup_records', [
            'backup_type' => 'full',
            'backup_location' => '/backups/full_20260928.sql',
            'status' => 'completed',
        ]);

        $backup = BackupRecord::where('backup_location', '/backups/full_20260928.sql')->first();
        $this->assertFalse($backup->verified);

        $response = $this->actingAs($this->user)->post(route('hms.ict.backups.verify', $backup));
        $response->assertRedirect();

        $backup->refresh();
        $this->assertTrue($backup->verified);
        $this->assertNotNull($backup->verified_at);

        $response = $this->actingAs($this->user)->get(route('hms.ict.backups.index'));
        $response->assertOk();
    }

    public function test_backup_routes_require_manage_backups_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('hms.ict.backups.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('hms.settings.backup'))
            ->assertForbidden();
    }

    public function test_software_licence_management(): void
    {
        $response = $this->actingAs($this->user)->post(route('hms.ict.software-licences.store'), [
            'software_name' => 'Microsoft Office 365',
            'licence_key' => 'XXXX-YYYY-ZZZZ-1234',
            'licence_type' => 'subscription',
            'max_seats' => 50,
            'purchase_date' => '2026-01-01',
            'expiry_date' => '2027-01-01',
            'cost' => 7500.00,
            'vendor' => 'Microsoft',
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('software_licences', [
            'software_name' => 'Microsoft Office 365',
            'licence_type' => 'subscription',
            'max_seats' => 50,
            'current_seats' => 0,
            'vendor' => 'Microsoft',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get(route('hms.ict.software-licences.index'));
        $response->assertOk();
    }
}
