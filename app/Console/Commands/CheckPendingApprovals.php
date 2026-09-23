<?php

namespace App\Console\Commands;

use App\Models\Requisition;
use App\Models\StockAdjustment;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Notifications\PendingApprovalNotification;
use Illuminate\Console\Command;

class CheckPendingApprovals extends Command
{
    protected $signature = 'approvals:check';

    protected $description = 'Check for pending approvals and send notifications to approvers';

    public function handle(): int
    {
        $this->info('Checking for pending approvals...');
        $this->newLine();

        $approvers = User::where('is_active', true)->get();

        if ($approvers->isEmpty()) {
            $this->warn('No active users found to notify.');
            return self::SUCCESS;
        }

        // Pending Requisitions
        $pendingRequisitions = Requisition::where('status', 'pending')->get();
        if ($pendingRequisitions->isNotEmpty()) {
            $this->info("Found {$pendingRequisitions->count()} pending requisition(s).");
            foreach ($approvers as $approver) {
                $approver->notify(new PendingApprovalNotification($pendingRequisitions->first(), 'requisition'));
            }
        } else {
            $this->line('No pending requisitions.');
        }

        // Pending Stock Adjustments
        $pendingAdjustments = StockAdjustment::where('status', 'pending')->get();
        if ($pendingAdjustments->isNotEmpty()) {
            $this->info("Found {$pendingAdjustments->count()} pending stock adjustment(s).");
            foreach ($approvers as $approver) {
                $approver->notify(new PendingApprovalNotification($pendingAdjustments->first(), 'stock_adjustment'));
            }
        } else {
            $this->line('No pending stock adjustments.');
        }

        // Pending Purchase Orders
        $pendingPOs = PurchaseOrder::where('status', 'pending')->get();
        if ($pendingPOs->isNotEmpty()) {
            $this->info("Found {$pendingPOs->count()} pending purchase order(s).");
            foreach ($approvers as $approver) {
                $approver->notify(new PendingApprovalNotification($pendingPOs->first(), 'purchase_order'));
            }
        } else {
            $this->line('No pending purchase orders.');
        }

        $this->newLine();
        $this->info('Approval check complete.');

        return self::SUCCESS;
    }
}
