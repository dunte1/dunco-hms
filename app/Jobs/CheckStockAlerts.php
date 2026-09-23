<?php

namespace App\Jobs;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Store;
use App\Models\User;
use App\Notifications\StockExpiryAlert;
use App\Notifications\LowStockAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckStockAlerts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function handle(): void
    {
        $this->checkExpiryAlerts();
        $this->checkLowStockAlerts();
    }

    protected function checkExpiryAlerts(): void
    {
        $alertUsers = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Pharmacist', 'Inventory Manager', 'Super Admin', 'Hospital Admin']);
        })->get();

        if ($alertUsers->isEmpty()) {
            return;
        }

        // Items expiring within 1 week
        $expiringWeek = MedicineBatch::where('status', 'active')
            ->whereBetween('expiry_date', [now(), now()->addWeek()])
            ->with(['medicine', 'store'])
            ->get();

        // Items expiring within 1 month
        $expiringMonth = MedicineBatch::where('status', 'active')
            ->whereBetween('expiry_date', [now()->addWeek()->startOfDay(), now()->addMonth()])
            ->with(['medicine', 'store'])
            ->get();

        // Already expired
        $expired = MedicineBatch::where('status', 'active')
            ->where('expiry_date', '<', now())
            ->with(['medicine', 'store'])
            ->get();

        if ($expiringWeek->isNotEmpty() || $expiringMonth->isNotEmpty() || $expired->isNotEmpty()) {
            foreach ($alertUsers as $user) {
                $user->notify(new StockExpiryAlert($expiringWeek, $expiringMonth, $expired));
            }
        }
    }

    protected function checkLowStockAlerts(): void
    {
        $alertUsers = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Pharmacist', 'Inventory Manager', 'Super Admin', 'Hospital Admin']);
        })->get();

        if ($alertUsers->isEmpty()) {
            return;
        }

        $lowStockItems = Medicine::where('stock_quantity', '<=', 'minimum_stock')
            ->where('minimum_stock', '>', 0)
            ->get();

        if ($lowStockItems->isNotEmpty()) {
            foreach ($alertUsers as $user) {
                $user->notify(new LowStockAlert($lowStockItems));
            }
        }
    }
}
