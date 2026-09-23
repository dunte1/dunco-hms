<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\StockAdjustment;
use App\Models\Store;
use App\Models\Medicine;
use App\Models\StoreStock;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockAdjustmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = StockAdjustment::with(['store', 'medicine', 'requester', 'approver']);

        if ($request->filled('store')) {
            $query->where('store_id', $request->store);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('adjustment_type')) {
            $query->where('adjustment_type', $request->adjustment_type);
        }

        $adjustments = $query->orderByDesc('created_at')->paginate(20);

        $stats = [
            'total' => StockAdjustment::count(),
            'pending' => StockAdjustment::where('status', 'pending')->count(),
            'approved_today' => StockAdjustment::where('status', 'approved')
                ->whereDate('approved_at', today())->count(),
        ];

        $stores = Store::active()->orderBy('name')->get();
        $medicines = Medicine::orderBy('name')->pluck('name', 'id');

        return view('hms.stock-adjustments.index', compact('adjustments', 'stats', 'stores', 'medicines'));
    }

    public function create(): View
    {
        $stores = Store::active()->orderBy('name')->get();
        $medicines = Medicine::orderBy('name')->pluck('name', 'id');

        return view('hms.stock-adjustments.create', compact('stores', 'medicines'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'medicine_id' => 'required|exists:medicines,id',
            'quantity_adjustment' => 'required|integer|not_in:0',
            'adjustment_type' => 'required|in:correction,write_off,damage,expiry',
            'reason' => 'required|string',
        ]);

        $stock = StoreStock::where('store_id', $data['store_id'])
            ->where('medicine_id', $data['medicine_id'])
            ->first();

        $stockBefore = $stock?->quantity ?? 0;
        $stockAfter = $stockBefore + $data['quantity_adjustment'];

        if ($stockAfter < 0) {
            return back()->withErrors(['quantity_adjustment' => 'Adjustment would result in negative stock'])->withInput();
        }

        StockAdjustment::create([
            'store_id' => $data['store_id'],
            'medicine_id' => $data['medicine_id'],
            'requested_by' => auth()->id(),
            'quantity_adjustment' => $data['quantity_adjustment'],
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'adjustment_type' => $data['adjustment_type'],
            'status' => 'pending',
            'reason' => $data['reason'],
        ]);

        return redirect()->route('hms.stock-adjustments.index')->with('status', 'Stock adjustment request submitted');
    }

    public function approve(StockAdjustment $stockAdjustment): RedirectResponse
    {
        if ($stockAdjustment->status !== 'pending') {
            return back()->with('error', 'Only pending adjustments can be approved');
        }

        if (!auth()->user()->hasAnyRole(['Super Admin', 'Hospital Admin', 'Inventory Manager'])) {
            return back()->with('error', 'Only Inventory Managers or Administrators can approve stock adjustments');
        }

        DB::transaction(function () use ($stockAdjustment) {
            $stock = StoreStock::firstOrCreate(
                ['store_id' => $stockAdjustment->store_id, 'medicine_id' => $stockAdjustment->medicine_id],
                ['quantity' => 0, 'minimum_stock' => 10, 'maximum_stock' => 1000, 'average_cost' => 0]
            );

            $oldQty = $stock->quantity;
            $stock->quantity = max(0, $stock->quantity + $stockAdjustment->quantity_adjustment);
            $stock->save();

            StockMovement::create([
                'movement_number' => 'ADJ-' . now()->format('Ym') . '-' . str_pad(StockMovement::count() + 1, 5, '0', STR_PAD_LEFT),
                'medicine_id' => $stockAdjustment->medicine_id,
                'store_id' => $stockAdjustment->store_id,
                'user_id' => auth()->id(),
                'movement_type' => 'adjustment',
                'direction' => $stockAdjustment->quantity_adjustment > 0 ? 'in' : 'out',
                'quantity' => abs($stockAdjustment->quantity_adjustment),
                'stock_before' => $oldQty,
                'stock_after' => $stock->quantity,
                'movement_date' => now(),
                'reference_type' => StockAdjustment::class,
                'reference_id' => $stockAdjustment->id,
                'notes' => "Stock adjustment {$stockAdjustment->adjustment_number}: {$stockAdjustment->reason}",
            ]);

            $stockAdjustment->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'stock_before' => $oldQty,
                'stock_after' => $stock->quantity,
            ]);
        });

        \App\Models\AuditLog::log('user', auth()->id(), 'stock_adjustment_approved', 'StockAdjustment', $stockAdjustment->id, ['status' => 'pending'], $stockAdjustment->toArray(), 'Stock adjustment approved: ' . $stockAdjustment->adjustment_number);

        return back()->with('status', 'Stock adjustment approved and applied');
    }

    public function reject(Request $request, StockAdjustment $stockAdjustment): RedirectResponse
    {
        if ($stockAdjustment->status !== 'pending') {
            return back()->with('error', 'Only pending adjustments can be rejected');
        }

        $request->validate(['rejection_reason' => 'required|string']);

        $stockAdjustment->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('status', 'Stock adjustment rejected');
    }
}
