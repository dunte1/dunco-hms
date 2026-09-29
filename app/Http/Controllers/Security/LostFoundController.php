<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\LostFoundItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LostFoundController extends Controller
{
    public function index(): View
    {
        $items = LostFoundItem::with('foundBy')
            ->latest('date_found')
            ->paginate(20);

        return view('hms.security.lost-found.index', compact('items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'item_description' => 'required|string',
            'location_found' => 'required|string|max:255',
            'date_found' => 'required|date',
            'storage_location' => 'nullable|string|max:255',
        ]);

        $data['found_by'] = auth()->id();
        $data['status'] = 'unclaimed';

        LostFoundItem::create($data);

        return redirect()->route('lost-found.index')
            ->with('success', 'Lost and found item recorded successfully.');
    }

    public function claim(Request $request, LostFoundItem $item): RedirectResponse
    {
        $data = $request->validate([
            'claimed_by_name' => 'required|string|max:255',
            'claimed_by_id_number' => 'nullable|string|max:50',
        ]);

        $item->update([
            'claimed_by_name' => $data['claimed_by_name'],
            'claimed_by_id_number' => $data['claimed_by_id_number'] ?? null,
            'claimed_at' => now(),
            'status' => 'claimed',
        ]);

        return redirect()->route('lost-found.index')
            ->with('success', 'Item marked as claimed successfully.');
    }
}
