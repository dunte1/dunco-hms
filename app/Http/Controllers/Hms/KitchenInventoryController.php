<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\KitchenInventoryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KitchenInventoryController extends Controller
{
    public function index(): JsonResponse
    {
        $items = KitchenInventoryItem::orderBy('item_name')->paginate(20);
        return response()->json($items);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|in:staples,vegetables,proteins,dairy,spices,beverages',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|in:kg,litre,packs,boxes',
            'reorder_level' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        $data['last_restocked_at'] = now();

        KitchenInventoryItem::create($data);

        return back()->with('status', 'Kitchen inventory item added');
    }

    public function restock(Request $request, KitchenInventoryItem $item): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => 'required|numeric|min:0',
        ]);

        $item->update([
            'quantity' => $item->quantity + $data['quantity'],
            'last_restocked_at' => now(),
        ]);

        return back()->with('status', 'Item restocked successfully');
    }
}
