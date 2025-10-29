<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(): View
    {
        // Inventory Statistics
        $stats = [
            'total_items' => Medicine::count(),
            'low_stock' => Medicine::whereRaw('stock_quantity <= minimum_stock')->count(),
            'out_of_stock' => Medicine::where('stock_quantity', 0)->count(),
            'expiring_soon' => Medicine::where('expiry_date', '<=', now()->addDays(30))
                                     ->where('expiry_date', '>', now())
                                     ->count(),
            'expired' => Medicine::where('expiry_date', '<=', now())->count(),
            'total_suppliers' => Supplier::where('status', 'active')->count(),
            'pending_orders' => PurchaseOrder::whereIn('status', ['draft', 'submitted'])->count(),
            'total_value' => Medicine::sum(DB::raw('stock_quantity * unit_price')),
        ];
        
        // Recent Stock Movements
        $recentMovements = StockMovement::with('medicine', 'user')
            ->latest('movement_date')
            ->limit(10)
            ->get();
        
        // Low Stock Items
        $lowStockItems = Medicine::with('category')
            ->whereRaw('stock_quantity <= minimum_stock')
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get();
        
        // Expiring Soon Items
        $expiringSoon = Medicine::with('category')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date')
            ->limit(10)
            ->get();
        
        // Stock Movement by Type (Last 30 days)
        $movementsByType = StockMovement::select('movement_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(quantity) as total_quantity'))
            ->where('movement_date', '>=', now()->subDays(30))
            ->groupBy('movement_type')
            ->get();
        
        // Top Suppliers by Order Value
        $topSuppliers = Supplier::withCount('purchaseOrders')
            ->where('status', 'active')
            ->orderByDesc('purchase_orders_count')
            ->limit(5)
            ->get();
        
        // Pending Purchase Orders
        $pendingOrders = PurchaseOrder::with('supplier')
            ->whereIn('status', ['draft', 'submitted', 'approved'])
            ->latest()
            ->limit(5)
            ->get();
        
        return view('hms.inventory.index', compact(
            'stats',
            'recentMovements',
            'lowStockItems',
            'expiringSoon',
            'movementsByType',
            'topSuppliers',
            'pendingOrders'
        ));
    }
}


