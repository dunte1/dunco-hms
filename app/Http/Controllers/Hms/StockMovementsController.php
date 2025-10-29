<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Medicine;
use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockMovementsController extends Controller
{
    public function index(Request $request): View
    {
        $query = StockMovement::with(['medicine', 'user', 'purchaseOrder']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('movement_number', 'like', "%{$search}%")
                  ->orWhere('batch_number', 'like', "%{$search}%")
                  ->orWhereHas('medicine', function($medicineQuery) use ($search) {
                      $medicineQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by medicine
        if ($request->filled('medicine_id')) {
            $query->where('medicine_id', $request->medicine_id);
        }
        
        // Filter by movement type
        if ($request->filled('movement_type')) {
            $query->ofType($request->movement_type);
        }
        
        // Filter by direction
        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }
        
        // Filter by date range
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->dateRange($request->from_date, $request->to_date);
        }
        
        $stockMovements = $query->latest('movement_date')->paginate(20)->withQueryString();
        
        // Statistics
        $stats = [
            'total_movements' => StockMovement::count(),
            'stock_in_count' => StockMovement::stockIn()->count(),
            'stock_out_count' => StockMovement::stockOut()->count(),
            'total_stock_in_value' => StockMovement::stockIn()->sum('total_cost'),
            'total_stock_out_value' => StockMovement::stockOut()->sum('total_cost'),
        ];
        
        $medicines = Medicine::orderBy('name')->get(['id', 'name']);
        
        return view('hms.inventory.stock-movements.index', compact('stockMovements', 'stats', 'medicines'));
    }

    public function create(): View
    {
        $medicines = Medicine::orderBy('name')->get(['id', 'name', 'stock_quantity']);
        $purchaseOrders = PurchaseOrder::whereIn('status', ['approved', 'ordered', 'partially_received'])
            ->with('supplier')
            ->orderBy('po_number')
            ->get();
        
        // Generate movement number
        $lastMovement = StockMovement::latest()->first();
        $nextNumber = $lastMovement ? intval(substr($lastMovement->movement_number, 3)) + 1 : 1;
        $movementNumber = 'SM-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        
        return view('hms.inventory.stock-movements.create', compact('medicines', 'purchaseOrders', 'movementNumber'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'movement_number' => 'required|string|unique:stock_movements,movement_number',
            'medicine_id' => 'required|exists:medicines,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'movement_type' => 'required|in:purchase,sale,adjustment,transfer,return,damage,expiry',
            'direction' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'nullable|numeric|min:0',
            'batch_number' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date|after:today',
            'movement_date' => 'required|date',
            'from_location' => 'nullable|string|max:255',
            'to_location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'reason' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $medicine = Medicine::findOrFail($data['medicine_id']);
            
            // Get current stock
            $stockBefore = $medicine->stock_quantity;
            
            // Calculate new stock based on direction
            if ($data['direction'] === 'in') {
                $stockAfter = $stockBefore + $data['quantity'];
            } else {
                // Check if sufficient stock for outward movement
                if ($stockBefore < $data['quantity']) {
                    return back()->withErrors([
                        'quantity' => 'Insufficient stock. Available: ' . $stockBefore
                    ])->withInput();
                }
                $stockAfter = $stockBefore - $data['quantity'];
            }
            
            // Calculate cost
            $unitCost = $data['unit_cost'] ?? $medicine->unit_price ?? 0;
            $totalCost = $data['quantity'] * $unitCost;
            
            // Create stock movement
            StockMovement::create([
                'movement_number' => $data['movement_number'],
                'medicine_id' => $data['medicine_id'],
                'purchase_order_id' => $data['purchase_order_id'],
                'user_id' => Auth::id(),
                'movement_type' => $data['movement_type'],
                'direction' => $data['direction'],
                'quantity' => $data['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'unit_cost' => $unitCost,
                'total_cost' => $totalCost,
                'batch_number' => $data['batch_number'],
                'expiry_date' => $data['expiry_date'],
                'movement_date' => $data['movement_date'],
                'from_location' => $data['from_location'],
                'to_location' => $data['to_location'],
                'notes' => $data['notes'],
                'reason' => $data['reason'],
            ]);
            
            // Update medicine stock
            $medicine->update(['stock_quantity' => $stockAfter]);
            
            DB::commit();
            
            return redirect()->route('hms.inventory.stock-movements.index')
                ->with('status', 'Stock movement recorded successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to record stock movement: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(StockMovement $stockMovement): View
    {
        $stockMovement->load(['medicine', 'user', 'purchaseOrder.supplier']);
        
        return view('hms.inventory.stock-movements.show', compact('stockMovement'));
    }

    public function receiveStock(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:purchase_order_items,id',
            'items.*.quantity_received' => 'required|integer|min:1',
            'items.*.batch_number' => 'nullable|string',
            'items.*.expiry_date' => 'nullable|date|after:today',
            'items.*.unit_cost' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $purchaseOrder = PurchaseOrder::with('items')->findOrFail($data['purchase_order_id']);
            
            foreach ($data['items'] as $itemData) {
                $poItem = $purchaseOrder->items()->findOrFail($itemData['item_id']);
                
                // Check if not over-receiving
                $remainingQty = $poItem->quantity_ordered - $poItem->quantity_received;
                if ($itemData['quantity_received'] > $remainingQty) {
                    return back()->withErrors([
                        'items.' . $itemData['item_id'] => 'Cannot receive more than ordered quantity'
                    ])->withInput();
                }
                
                // Generate movement number
                $lastMovement = StockMovement::latest()->first();
                $nextNumber = $lastMovement ? intval(substr($lastMovement->movement_number, 3)) + 1 : 1;
                $movementNumber = 'SM-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
                
                // Get medicine and current stock
                if ($poItem->medicine_id) {
                    $medicine = Medicine::findOrFail($poItem->medicine_id);
                    $stockBefore = $medicine->stock_quantity;
                    $stockAfter = $stockBefore + $itemData['quantity_received'];
                    
                    // Create stock movement
                    StockMovement::create([
                        'movement_number' => $movementNumber,
                        'medicine_id' => $poItem->medicine_id,
                        'purchase_order_id' => $purchaseOrder->id,
                        'user_id' => Auth::id(),
                        'movement_type' => 'purchase',
                        'direction' => 'in',
                        'quantity' => $itemData['quantity_received'],
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                        'unit_cost' => $itemData['unit_cost'] ?? $poItem->unit_price,
                        'total_cost' => $itemData['quantity_received'] * ($itemData['unit_cost'] ?? $poItem->unit_price),
                        'batch_number' => $itemData['batch_number'] ?? null,
                        'expiry_date' => $itemData['expiry_date'] ?? null,
                        'movement_date' => now(),
                        'notes' => 'Received from PO: ' . $purchaseOrder->po_number,
                    ]);
                    
                    // Update medicine stock
                    $medicine->update(['stock_quantity' => $stockAfter]);
                }
                
                // Update PO item received quantity
                $poItem->increment('quantity_received', $itemData['quantity_received']);
            }
            
            // Update PO status
            $allReceived = $purchaseOrder->items->every(function ($item) {
                return $item->quantity_received >= $item->quantity_ordered;
            });
            
            $anyReceived = $purchaseOrder->items->some(function ($item) {
                return $item->quantity_received > 0;
            });
            
            if ($allReceived) {
                $purchaseOrder->update([
                    'status' => 'received',
                    'actual_delivery_date' => now(),
                ]);
            } elseif ($anyReceived) {
                $purchaseOrder->update(['status' => 'partially_received']);
            }
            
            DB::commit();
            
            return redirect()->route('hms.inventory.purchase-orders.show', $purchaseOrder)
                ->with('status', 'Stock received successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to receive stock: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function stockReport(): View
    {
        $medicines = Medicine::with(['category'])
            ->select('medicines.*')
            ->orderBy('stock_quantity', 'asc')
            ->get();
        
        $lowStock = $medicines->filter(function ($medicine) {
            return $medicine->stock_quantity <= $medicine->minimum_stock;
        });
        
        $expiringMedicines = Medicine::where('expiry_date', '<=', now()->addMonths(3))
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date', 'asc')
            ->get();
        
        $expiredMedicines = Medicine::where('expiry_date', '<=', now())->get();
        
        return view('hms.inventory.stock-report', compact('medicines', 'lowStock', 'expiringMedicines', 'expiredMedicines'));
    }
}

