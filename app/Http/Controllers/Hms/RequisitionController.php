<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\Store;
use App\Models\Medicine;
use App\Models\StoreStock;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RequisitionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Requisition::with(['requestingStore', 'supplyingStore', 'requester']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('requisition_number', 'like', "%{$search}%")
                ->orWhereHas('requestingStore', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('requesting_store')) {
            $query->where('requesting_store_id', $request->requesting_store);
        }

        $requisitions = $query->orderByDesc('created_at')->paginate(20);

        $stats = [
            'total' => Requisition::count(),
            'pending' => Requisition::where('status', 'pending')->count(),
            'approved' => Requisition::where('status', 'approved')->count(),
            'fulfilled' => Requisition::where('status', 'fulfilled')->count(),
        ];

        $stores = Store::where('is_main', false)->active()->orderBy('name')->get();

        return view('hms.requisitions.index', compact('requisitions', 'stats', 'stores'));
    }

    public function create(): View
    {
        $stores = Store::where('is_main', false)->active()->orderBy('name')->get();
        $medicines = Medicine::orderBy('name')->pluck('name', 'id');

        return view('hms.requisitions.create', compact('stores', 'medicines'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'requesting_store_id' => 'required|exists:stores,id',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.reason' => 'nullable|string',
        ]);

        $supplyingStore = Store::where('is_main', true)->first();

        DB::transaction(function () use ($data, $supplyingStore) {
            $requisition = Requisition::create([
                'requesting_store_id' => $data['requesting_store_id'],
                'supplying_store_id' => $supplyingStore?->id,
                'requested_by' => auth()->id(),
                'status' => 'pending',
                'reason' => $data['reason'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                RequisitionItem::create([
                    'requisition_id' => $requisition->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity_requested' => $item['quantity'],
                    'notes' => $item['reason'] ?? null,
                ]);
            }
        });

        return redirect()->route('hms.requisitions.index')->with('status', 'Requisition created successfully');
    }

    public function show(Requisition $requisition): View
    {
        $requisition->load([
            'requestingStore', 'supplyingStore', 'requester', 'approver',
            'items.medicine',
        ]);

        $statusHistory = collect();
        if ($requisition->approved_at) {
            $statusHistory->push(['status' => 'approved', 'date' => $requisition->approved_at, 'user' => $requisition->approver]);
        }
        if ($requisition->fulfilled_at) {
            $statusHistory->push(['status' => 'fulfilled', 'date' => $requisition->fulfilled_at, 'user' => null]);
        }

        return view('hms.requisitions.show', compact('requisition', 'statusHistory'));
    }

    public function approve(Requisition $requisition): RedirectResponse
    {
        if ($requisition->status !== 'pending') {
            return back()->with('error', 'Only pending requisitions can be approved');
        }

        if (!auth()->user()->hasAnyRole(['Super Admin', 'Hospital Admin', 'Inventory Manager'])) {
            return back()->with('error', 'Only Inventory Managers or Administrators can approve requisitions');
        }

        $requisition->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        \App\Models\AuditLog::log('user', auth()->id(), 'requisition_approved', 'Requisition', $requisition->id, ['status' => 'pending'], ['status' => 'approved'], 'Requisition approved: ' . $requisition->requisition_number);

        return back()->with('status', 'Requisition approved successfully');
    }

    public function reject(Request $request, Requisition $requisition): RedirectResponse
    {
        if ($requisition->status !== 'pending') {
            return back()->with('error', 'Only pending requisitions can be rejected');
        }

        $request->validate(['rejection_reason' => 'required|string']);

        $requisition->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        \App\Models\AuditLog::log('user', auth()->id(), 'requisition_rejected', 'Requisition', $requisition->id, ['status' => 'pending'], ['status' => 'rejected'], 'Requisition rejected: ' . $requisition->requisition_number);

        return back()->with('status', 'Requisition rejected');
    }

    public function fulfill(Requisition $requisition): RedirectResponse
    {
        if ($requisition->status !== 'approved') {
            return back()->with('error', 'Only approved requisitions can be fulfilled');
        }

        $requisition->load('items.medicine');

        DB::transaction(function () use ($requisition) {
            foreach ($requisition->items as $item) {
                $fromStock = StoreStock::where('store_id', $requisition->supplying_store_id)
                    ->where('medicine_id', $item->medicine_id)
                    ->first();

                if (!$fromStock || $fromStock->quantity < $item->quantity_requested) {
                    throw new \Exception("Insufficient stock for {$item->medicine->name} at supplying store");
                }

                $fromOld = $fromStock->quantity;
                $fromStock->quantity -= $item->quantity_requested;
                $fromStock->save();

                $toStock = StoreStock::firstOrCreate(
                    ['store_id' => $requisition->requesting_store_id, 'medicine_id' => $item->medicine_id],
                    ['quantity' => 0, 'minimum_stock' => 10, 'maximum_stock' => 1000, 'average_cost' => $fromStock->average_cost]
                );
                $toOld = $toStock->quantity;
                $toStock->quantity += $item->quantity_requested;
                $toStock->average_cost = $fromStock->average_cost;
                $toStock->save();

                $item->update(['quantity_fulfilled' => $item->quantity_requested]);

                StockMovement::create([
                    'movement_number' => 'REQ-OUT-' . now()->format('Ym') . '-' . str_pad(StockMovement::count() + 1, 5, '0', STR_PAD_LEFT),
                    'medicine_id' => $item->medicine_id,
                    'store_id' => $requisition->supplying_store_id,
                    'to_store_id' => $requisition->requesting_store_id,
                    'user_id' => auth()->id(),
                    'movement_type' => 'transfer',
                    'direction' => 'out',
                    'quantity' => $item->quantity_requested,
                    'stock_before' => $fromOld,
                    'stock_after' => $fromStock->quantity,
                    'movement_date' => now(),
                    'reference_type' => Requisition::class,
                    'reference_id' => $requisition->id,
                    'notes' => "Requisition fulfillment: {$requisition->requisition_number}",
                ]);

                StockMovement::create([
                    'movement_number' => 'REQ-IN-' . now()->format('Ym') . '-' . str_pad(StockMovement::count() + 2, 5, '0', STR_PAD_LEFT),
                    'medicine_id' => $item->medicine_id,
                    'store_id' => $requisition->requesting_store_id,
                    'to_store_id' => $requisition->supplying_store_id,
                    'user_id' => auth()->id(),
                    'movement_type' => 'transfer',
                    'direction' => 'in',
                    'quantity' => $item->quantity_requested,
                    'stock_before' => $toOld,
                    'stock_after' => $toStock->quantity,
                    'movement_date' => now(),
                    'reference_type' => Requisition::class,
                    'reference_id' => $requisition->id,
                    'notes' => "Requisition fulfillment: {$requisition->requisition_number}",
                ]);
            }

            $requisition->update([
                'status' => 'fulfilled',
                'fulfilled_at' => now(),
            ]);
        });

        \App\Models\AuditLog::log('user', auth()->id(), 'requisition_fulfilled', 'Requisition', $requisition->id, ['status' => 'approved'], ['status' => 'fulfilled'], 'Requisition fulfilled: ' . $requisition->requisition_number);

        return back()->with('status', 'Requisition fulfilled successfully');
    }

    public function cancel(Requisition $requisition): RedirectResponse
    {
        if ($requisition->status !== 'pending') {
            return back()->with('error', 'Only pending requisitions can be cancelled');
        }

        $requisition->update(['status' => 'cancelled']);

        return back()->with('status', 'Requisition cancelled');
    }
}
