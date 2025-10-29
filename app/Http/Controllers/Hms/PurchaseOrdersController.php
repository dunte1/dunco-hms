<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseOrdersController extends Controller
{
    public function index(Request $request): View
    {
        $query = PurchaseOrder::with(['supplier', 'creator']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($supplierQuery) use ($search) {
                      $supplierQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('company_name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        
        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('order_date', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('order_date', '<=', $request->to_date);
        }
        
        $purchaseOrders = $query->latest('order_date')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total_orders' => PurchaseOrder::count(),
            'pending_orders' => PurchaseOrder::pending()->count(),
            'received_orders' => PurchaseOrder::status('received')->count(),
            'total_value' => PurchaseOrder::whereIn('status', ['approved', 'ordered', 'received'])->sum('total_amount'),
            'pending_value' => PurchaseOrder::pending()->sum('total_amount'),
        ];
        
        $suppliers = Supplier::active()->orderBy('name')->get(['id', 'name', 'company_name']);
        
        return view('hms.inventory.purchase-orders.index', compact('purchaseOrders', 'stats', 'suppliers'));
    }

    public function create(): View
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        $medicines = Medicine::orderBy('name')->get(['id', 'name', 'dosage_form', 'strength', 'unit_price']);
        
        // Generate PO number
        $lastPO = PurchaseOrder::latest()->first();
        $nextNumber = $lastPO ? intval(substr($lastPO->po_number, 3)) + 1 : 1;
        $poNumber = 'PO-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        
        return view('hms.inventory.purchase-orders.create', compact('suppliers', 'medicines', 'poNumber'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'po_number' => 'required|string|unique:purchase_orders,po_number',
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'nullable|exists:medicines,id',
            'items.*.item_name' => 'required|string',
            'items.*.item_code' => 'nullable|string',
            'items.*.description' => 'nullable|string',
            'items.*.unit_of_measure' => 'required|string',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = 0;
            $items = [];
            
            foreach ($data['items'] as $item) {
                $quantity = $item['quantity_ordered'];
                $unitPrice = $item['unit_price'];
                $taxRate = $item['tax_rate'] ?? 0;
                $discountPercent = $item['discount_percent'] ?? 0;
                
                $lineSubtotal = $quantity * $unitPrice;
                $discountAmount = $lineSubtotal * ($discountPercent / 100);
                $taxableAmount = $lineSubtotal - $discountAmount;
                $taxAmount = $taxableAmount * ($taxRate / 100);
                $lineTotal = $taxableAmount + $taxAmount;
                
                $subtotal += $lineTotal;
                
                $items[] = array_merge($item, [
                    'line_total' => $lineTotal,
                ]);
            }
            
            $taxAmount = $data['tax_amount'] ?? 0;
            $discountAmount = $data['discount_amount'] ?? 0;
            $shippingCost = $data['shipping_cost'] ?? 0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount + $shippingCost;
            
            // Create purchase order
            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $data['po_number'],
                'supplier_id' => $data['supplier_id'],
                'created_by' => Auth::id(),
                'order_date' => $data['order_date'],
                'expected_delivery_date' => $data['expected_delivery_date'],
                'reference_number' => $data['reference_number'],
                'notes' => $data['notes'],
                'terms_and_conditions' => $data['terms_and_conditions'],
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'status' => 'draft',
                'payment_method' => 'credit',
                'payment_status' => 'unpaid',
            ]);
            
            // Create purchase order items
            foreach ($items as $item) {
                $purchaseOrder->items()->create($item);
            }
            
            DB::commit();
            
            return redirect()->route('hms.inventory.purchase-orders.show', $purchaseOrder)
                ->with('status', 'Purchase Order created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create purchase order: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['supplier', 'creator', 'approver', 'items.medicine']);
        
        return view('hms.inventory.purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder): View
    {
        // Only draft and pending orders can be edited
        if (!in_array($purchaseOrder->status, ['draft', 'pending'])) {
            abort(403, 'Cannot edit purchase order in ' . $purchaseOrder->status . ' status');
        }
        
        $purchaseOrder->load('items');
        $suppliers = Supplier::active()->orderBy('name')->get();
        $medicines = Medicine::orderBy('name')->get(['id', 'name', 'dosage_form', 'strength', 'unit_price']);
        
        return view('hms.inventory.purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'medicines'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        // Only draft and pending orders can be updated
        if (!in_array($purchaseOrder->status, ['draft', 'pending'])) {
            return back()->withErrors(['error' => 'Cannot edit purchase order in ' . $purchaseOrder->status . ' status']);
        }
        
        $data = $request->validate([
            'po_number' => 'required|string|unique:purchase_orders,po_number,' . $purchaseOrder->id,
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'nullable|exists:medicines,id',
            'items.*.item_name' => 'required|string',
            'items.*.item_code' => 'nullable|string',
            'items.*.description' => 'nullable|string',
            'items.*.unit_of_measure' => 'required|string',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = 0;
            $items = [];
            
            foreach ($data['items'] as $item) {
                $quantity = $item['quantity_ordered'];
                $unitPrice = $item['unit_price'];
                $taxRate = $item['tax_rate'] ?? 0;
                $discountPercent = $item['discount_percent'] ?? 0;
                
                $lineSubtotal = $quantity * $unitPrice;
                $discountAmount = $lineSubtotal * ($discountPercent / 100);
                $taxableAmount = $lineSubtotal - $discountAmount;
                $taxAmount = $taxableAmount * ($taxRate / 100);
                $lineTotal = $taxableAmount + $taxAmount;
                
                $subtotal += $lineTotal;
                
                $items[] = array_merge($item, [
                    'line_total' => $lineTotal,
                ]);
            }
            
            $taxAmount = $data['tax_amount'] ?? 0;
            $discountAmount = $data['discount_amount'] ?? 0;
            $shippingCost = $data['shipping_cost'] ?? 0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount + $shippingCost;
            
            // Update purchase order
            $purchaseOrder->update([
                'po_number' => $data['po_number'],
                'supplier_id' => $data['supplier_id'],
                'order_date' => $data['order_date'],
                'expected_delivery_date' => $data['expected_delivery_date'],
                'reference_number' => $data['reference_number'],
                'notes' => $data['notes'],
                'terms_and_conditions' => $data['terms_and_conditions'],
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
            ]);
            
            // Delete existing items and create new ones
            $purchaseOrder->items()->delete();
            
            foreach ($items as $item) {
                $purchaseOrder->items()->create($item);
            }
            
            DB::commit();
            
            return redirect()->route('hms.inventory.purchase-orders.show', $purchaseOrder)
                ->with('status', 'Purchase Order updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update purchase order: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        // Only draft orders can be deleted
        if ($purchaseOrder->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft purchase orders can be deleted']);
        }
        
        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();
        
        return redirect()->route('hms.inventory.purchase-orders.index')
            ->with('status', 'Purchase Order deleted successfully');
    }

    public function approve(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending purchase orders can be approved']);
        }
        
        $purchaseOrder->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);
        
        return back()->with('status', 'Purchase Order approved successfully');
    }

    public function submit(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft purchase orders can be submitted']);
        }
        
        $purchaseOrder->update(['status' => 'pending']);
        
        return back()->with('status', 'Purchase Order submitted for approval');
    }
}

