<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreStock;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(): View
    {
        $stores = Store::withCount(['stockItems', 'batches', 'purchaseOrders'])
            ->with('manager')
            ->orderBy('name')
            ->get();

        $stats = [
            'total_stores' => Store::count(),
            'active_stores' => Store::active()->count(),
            'total_stock_value' => StoreStock::sum(DB::raw('quantity * average_cost')),
            'low_stock_items' => StoreStock::whereColumn('quantity', '<=', 'minimum_stock')->count(),
        ];

        return view('hms.stores.index', compact('stores', 'stats'));
    }

    public function create(): View
    {
        $managers = User::orderBy('name')->pluck('name', 'id');
        return view('hms.stores.create', compact('managers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:stores,name',
            'code' => 'required|string|unique:stores,code',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'type' => 'required|in:main,pharmacy,satellite,warehouse,emergency,ward',
            'is_main' => 'boolean',
        ]);

        if (empty($data['is_main'])) $data['is_main'] = false;
        $data['status'] = 'active';

        Store::create($data);

        return redirect()->route('hms.stores.index')->with('status', 'Store created successfully');
    }

    public function show(Store $store): View
    {
        $store->load(['manager', 'stockItems.medicine', 'batches.medicine']);

        $stockItems = $store->stockItems()
            ->with('medicine')
            ->orderBy('quantity', 'asc')
            ->paginate(20);

        $lowStockCount = $store->stockItems()->whereColumn('quantity', '<=', 'minimum_stock')->count();
        $outOfStockCount = $store->stockItems()->where('quantity', '<=', 0)->count();
        $expiringCount = $store->batches()->where('status', 'active')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->count();

        $totalValue = (float) $store->stockItems()->sum(DB::raw('quantity * average_cost'));

        return view('hms.stores.show', compact('store', 'stockItems', 'lowStockCount', 'outOfStockCount', 'expiringCount', 'totalValue'));
    }

    public function edit(Store $store): View
    {
        $managers = User::orderBy('name')->pluck('name', 'id');
        return view('hms.stores.edit', compact('store', 'managers'));
    }

    public function update(Request $request, Store $store): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:stores,name,' . $store->id,
            'code' => 'required|string|unique:stores,code,' . $store->id,
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'type' => 'required|in:main,pharmacy,satellite,warehouse,emergency,ward',
            'status' => 'required|in:active,inactive',
            'is_main' => 'boolean',
        ]);

        $store->update($data);

        return redirect()->route('hms.stores.index')->with('status', 'Store updated successfully');
    }

    public function destroy(Store $store): RedirectResponse
    {
        if ($store->is_main) {
            return back()->with('error', 'Cannot delete the main store');
        }
        if ($store->stockItems()->where('quantity', '>', 0)->exists()) {
            return back()->with('error', 'Cannot delete store with existing stock');
        }
        $store->delete();
        return redirect()->route('hms.stores.index')->with('status', 'Store deleted');
    }

    // --- STOCK MANAGEMENT ---

    public function stock(Store $store): View
    {
        $query = $store->stockItems()->with('medicine');

        if (request('search')) {
            $search = request('search');
            $query->whereHas('medicine', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        if (request('filter') === 'low') {
            $query->whereColumn('quantity', '<=', 'minimum_stock');
        } elseif (request('filter') === 'out') {
            $query->where('quantity', '<=', 0);
        }

        $stockItems = $query->orderBy('quantity', 'asc')->paginate(20);

        return view('hms.stores.stock', compact('store', 'stockItems'));
    }

    public function adjustStock(Request $request, Store $store): RedirectResponse
    {
        $data = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'adjustment' => 'required|integer',
            'reason' => 'required|string',
            'batch_number' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $store) {
            $stock = StoreStock::firstOrCreate(
                ['store_id' => $store->id, 'medicine_id' => $data['medicine_id']],
                ['quantity' => 0, 'minimum_stock' => 10, 'maximum_stock' => 1000, 'average_cost' => 0]
            );

            $oldQty = $stock->quantity;
            $stock->quantity += $data['adjustment'];
            if ($stock->quantity < 0) $stock->quantity = 0;
            $stock->save();

            StockMovement::create([
                'movement_number' => 'ADJ-' . now()->format('Ym') . '-' . str_pad(StockMovement::count() + 1, 5, '0', STR_PAD_LEFT),
                'medicine_id' => $data['medicine_id'],
                'store_id' => $store->id,
                'user_id' => auth()->id(),
                'movement_type' => 'adjustment',
                'direction' => $data['adjustment'] > 0 ? 'in' : 'out',
                'quantity' => abs($data['adjustment']),
                'stock_before' => $oldQty,
                'stock_after' => $stock->quantity,
                'batch_number' => $data['batch_number'] ?? null,
                'movement_date' => now(),
                'notes' => $data['reason'],
            ]);
        });

        return back()->with('status', 'Stock adjusted successfully');
    }

    // --- BATCH MANAGEMENT ---

    public function batches(Store $store): View
    {
        $batches = $store->batches()->with('medicine')->orderByDesc('created_at')->paginate(20);
        return view('hms.stores.batches', compact('store', 'batches'));
    }

    public function storeBatch(Request $request, Store $store): RedirectResponse
    {
        $data = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'batch_number' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'required|date|after:today',
        ]);

        DB::transaction(function () use ($data, $store) {
            $batch = MedicineBatch::create([
                ...$data,
                'store_id' => $store->id,
                'quantity_sold' => 0,
                'status' => 'active',
            ]);

            $stock = StoreStock::firstOrCreate(
                ['store_id' => $store->id, 'medicine_id' => $data['medicine_id']],
                ['quantity' => 0, 'minimum_stock' => 10, 'maximum_stock' => 1000, 'average_cost' => 0]
            );

            $oldQty = $stock->quantity;
            $stock->quantity += $data['quantity'];
            $totalCost = ($stock->average_cost * $oldQty) + ($data['unit_cost'] * $data['quantity']);
            $stock->average_cost = $stock->quantity > 0 ? $totalCost / $stock->quantity : 0;
            $stock->save();

            // Update medicine total_stock
            $medicine = Medicine::find($data['medicine_id']);
            $medicine->update(['stock_quantity' => $medicine->storeStocks()->sum('quantity')]);

            StockMovement::create([
                'movement_number' => 'REC-' . now()->format('Ym') . '-' . str_pad(StockMovement::count() + 1, 5, '0', STR_PAD_LEFT),
                'medicine_id' => $data['medicine_id'],
                'store_id' => $store->id,
                'batch_id' => $batch->id,
                'user_id' => auth()->id(),
                'movement_type' => 'receive',
                'direction' => 'in',
                'quantity' => $data['quantity'],
                'stock_before' => $oldQty,
                'stock_after' => $stock->quantity,
                'unit_cost' => $data['unit_cost'],
                'total_cost' => $data['unit_cost'] * $data['quantity'],
                'batch_number' => $data['batch_number'] ?? null,
                'expiry_date' => $data['expiry_date'],
                'movement_date' => now(),
                'notes' => "New batch received: {$data['batch_number']}",
            ]);
        });

        return redirect()->route('hms.stores.batches', $store)->with('status', 'Batch added and stock updated');
    }

    // --- INTER-STORE TRANSFER ---

    public function transfer(): View
    {
        $stores = Store::active()->orderBy('name')->get();
        $medicines = Medicine::orderBy('name')->pluck('name', 'id');

        $transfers = StockMovement::where('movement_type', 'transfer')
            ->with(['medicine', 'store', 'toStore'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('hms.stores.transfer', compact('stores', 'medicines', 'transfers'));
    }

    public function storeTransfer(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'from_store_id' => 'required|exists:stores,id',
            'to_store_id' => 'required|exists:stores,id|different:from_store_id',
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'batch_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $fromStock = StoreStock::where('store_id', $data['from_store_id'])
            ->where('medicine_id', $data['medicine_id'])
            ->first();

        if (!$fromStock || $fromStock->quantity < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock at source store'])->withInput();
        }

        DB::transaction(function () use ($data) {
            // Deduct from source
            $fromStock = StoreStock::where('store_id', $data['from_store_id'])
                ->where('medicine_id', $data['medicine_id'])
                ->first();
            $fromOld = $fromStock->quantity;
            $fromStock->quantity -= $data['quantity'];
            $fromStock->save();

            // Add to destination
            $toStock = StoreStock::firstOrCreate(
                ['store_id' => $data['to_store_id'], 'medicine_id' => $data['medicine_id']],
                ['quantity' => 0, 'minimum_stock' => 10, 'maximum_stock' => 1000, 'average_cost' => $fromStock->average_cost]
            );
            $toOld = $toStock->quantity;
            $toStock->quantity += $data['quantity'];
            $toStock->save();

            // Create outbound movement
            StockMovement::create([
                'movement_number' => 'TRF-' . now()->format('Ym') . '-' . str_pad(StockMovement::count() + 1, 5, '0', STR_PAD_LEFT),
                'medicine_id' => $data['medicine_id'],
                'store_id' => $data['from_store_id'],
                'to_store_id' => $data['to_store_id'],
                'user_id' => auth()->id(),
                'movement_type' => 'transfer',
                'direction' => 'out',
                'quantity' => $data['quantity'],
                'stock_before' => $fromOld,
                'stock_after' => $fromStock->quantity,
                'batch_number' => $data['batch_number'] ?? null,
                'movement_date' => now(),
                'notes' => $data['notes'] ?? "Transfer to " . Store::find($data['to_store_id'])->name,
            ]);

            // Create inbound movement
            StockMovement::create([
                'movement_number' => 'TRF-' . now()->format('Ym') . '-' . str_pad(StockMovement::count() + 2, 5, '0', STR_PAD_LEFT),
                'medicine_id' => $data['medicine_id'],
                'store_id' => $data['to_store_id'],
                'to_store_id' => $data['from_store_id'],
                'user_id' => auth()->id(),
                'movement_type' => 'transfer',
                'direction' => 'in',
                'quantity' => $data['quantity'],
                'stock_before' => $toOld,
                'stock_after' => $toStock->quantity,
                'batch_number' => $data['batch_number'] ?? null,
                'movement_date' => now(),
                'notes' => $data['notes'] ?? "Transfer from " . Store::find($data['from_store_id'])->name,
            ]);

            // Update medicine total_stock
            $medicine = Medicine::find($data['medicine_id']);
            $medicine->update(['stock_quantity' => $medicine->storeStocks()->sum('quantity')]);
        });

        return redirect()->route('hms.stores.transfer')->with('status', 'Transfer completed successfully');
    }

    // --- STORE REPORTS ---

    public function reports(Store $store): View
    {
        $lowStock = $store->stockItems()->whereColumn('quantity', '<=', 'minimum_stock')->with('medicine')->get();
        $expiringBatches = $store->batches()->where('status', 'active')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->with('medicine')
            ->get();
        $recentMovements = $store->outboundMovements()->with('medicine')->latest()->limit(20)->get();
        $totalValue = (float) $store->stockItems()->sum(DB::raw('quantity * average_cost'));

        return view('hms.stores.reports', compact('store', 'lowStock', 'expiringBatches', 'recentMovements', 'totalValue'));
    }
}
