<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Stocktake;
use App\Models\StocktakeItem;
use App\Models\Store;
use App\Models\StoreStock;
use App\Models\StockAdjustment;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StocktakeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Stocktake::with(['store', 'performer', 'approver']);

        if ($request->filled('store')) {
            $query->where('store_id', $request->store);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $stocktakes = $query->orderByDesc('created_at')->paginate(20);

        $stats = [
            'total' => Stocktake::count(),
            'in_progress' => Stocktake::where('status', 'in_progress')->count(),
            'completed' => Stocktake::where('status', 'completed')->count(),
            'approved' => Stocktake::where('status', 'approved')->count(),
        ];

        $stores = Store::active()->orderBy('name')->get();

        return view('hms.stocktakes.index', compact('stocktakes', 'stats', 'stores'));
    }

    public function create(): View
    {
        $stores = Store::active()->orderBy('name')->get();

        return view('hms.stocktakes.create', compact('stores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'stocktake_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            $stocktake = Stocktake::create([
                'store_id' => $data['store_id'],
                'performed_by' => auth()->id(),
                'status' => 'in_progress',
                'stocktake_date' => $data['stocktake_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $stockItems = StoreStock::where('store_id', $data['store_id'])
                ->where('quantity', '>', 0)
                ->get();

            foreach ($stockItems as $stock) {
                StocktakeItem::create([
                    'stocktake_id' => $stocktake->id,
                    'medicine_id' => $stock->medicine_id,
                    'system_quantity' => $stock->quantity,
                    'physical_quantity' => null,
                    'variance' => null,
                ]);
            }
        });

        return redirect()->route('hms.stocktakes.index')->with('status', 'Stocktake created successfully');
    }

    public function show(Stocktake $stocktake): View
    {
        $stocktake->load(['store', 'performer', 'approver', 'items.medicine']);

        return view('hms.stocktakes.show', compact('stocktake'));
    }

    public function updateItems(Request $request, Stocktake $stocktake): RedirectResponse
    {
        if ($stocktake->status !== 'in_progress') {
            return back()->with('error', 'Can only update items for in-progress stocktakes');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:stocktake_items,id',
            'items.*.physical_quantity' => 'required|integer|min:0',
            'items.*.remarks' => 'nullable|string',
        ]);

        foreach ($request->items as $item) {
            StocktakeItem::where('id', $item['id'])
                ->where('stocktake_id', $stocktake->id)
                ->update([
                    'physical_quantity' => $item['physical_quantity'],
                    'remarks' => $item['remarks'] ?? null,
                ]);
        }

        return back()->with('status', 'Stocktake items updated successfully');
    }

    public function complete(Stocktake $stocktake): RedirectResponse
    {
        if ($stocktake->status !== 'in_progress') {
            return back()->with('error', 'Only in-progress stocktakes can be completed');
        }

        $hasNull = $stocktake->items()->whereNull('physical_quantity')->exists();
        if ($hasNull) {
            return back()->with('error', 'All items must have physical quantity recorded');
        }

        $stocktake->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('status', 'Stocktake completed successfully');
    }

    public function approve(Stocktake $stocktake): RedirectResponse
    {
        if ($stocktake->status !== 'completed') {
            return back()->with('error', 'Only completed stocktakes can be approved');
        }

        if (!auth()->user()->hasAnyRole(['Super Admin', 'Hospital Admin', 'Inventory Manager'])) {
            return back()->with('error', 'Only Inventory Managers or Administrators can approve stocktakes');
        }

        $stocktake->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Stocktake approved successfully');
    }

    public function adjust(Stocktake $stocktake): RedirectResponse
    {
        if ($stocktake->status !== 'approved') {
            return back()->with('error', 'Only approved stocktakes can be adjusted');
        }

        $varianceItems = $stocktake->items()->where('variance', '!=', 0)->whereNotNull('variance')->get();

        if ($varianceItems->isEmpty()) {
            return back()->with('error', 'No items with variance to adjust');
        }

        DB::transaction(function () use ($stocktake, $varianceItems) {
            foreach ($varianceItems as $item) {
                $stock = StoreStock::where('store_id', $stocktake->store_id)
                    ->where('medicine_id', $item->medicine_id)
                    ->first();

                $stockBefore = $stock?->quantity ?? 0;
                $stockAfter = $stockBefore + $item->variance;

                if ($stock) {
                    $stock->quantity = max(0, $stockAfter);
                    $stock->save();
                } else {
                    $stock = StoreStock::create([
                        'store_id' => $stocktake->store_id,
                        'medicine_id' => $item->medicine_id,
                        'quantity' => max(0, $item->variance),
                        'minimum_stock' => 10,
                        'maximum_stock' => 1000,
                        'average_cost' => 0,
                    ]);
                    $stockAfter = $stock->quantity;
                }

                StockAdjustment::create([
                    'store_id' => $stocktake->store_id,
                    'medicine_id' => $item->medicine_id,
                    'stocktake_id' => $stocktake->id,
                    'requested_by' => auth()->id(),
                    'quantity_adjustment' => $item->variance,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'adjustment_type' => 'correction',
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'reason' => "Stocktake adjustment (variance: {$item->variance})" . ($item->remarks ? " - {$item->remarks}" : ''),
                ]);

                StockMovement::create([
                    'movement_number' => 'STK-ADJ-' . now()->format('Ym') . '-' . str_pad(StockMovement::count() + 1, 5, '0', STR_PAD_LEFT),
                    'medicine_id' => $item->medicine_id,
                    'store_id' => $stocktake->store_id,
                    'user_id' => auth()->id(),
                    'movement_type' => 'adjustment',
                    'direction' => $item->variance > 0 ? 'in' : 'out',
                    'quantity' => abs($item->variance),
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'movement_date' => now(),
                    'reference_type' => Stocktake::class,
                    'reference_id' => $stocktake->id,
                    'notes' => "Stocktake adjustment: {$stocktake->stocktake_number}",
                ]);
            }

            $stocktake->update(['status' => 'adjusted']);
        });

        return back()->with('status', 'Stock adjustment applied successfully');
    }
}
