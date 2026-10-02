<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\PriceList;
use App\Models\PriceItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PriceListController extends Controller
{
    public function index(Request $request): View
    {
        $query = PriceList::with('items.service');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $priceLists = $query->latest()->paginate(15)->withQueryString();

        return view('hms.pricing.price-lists.index', compact('priceLists'));
    }

    public function create(): View
    {
        $services = Service::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        return view('hms.pricing.price-lists.create', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:price_lists,name',
            'description' => 'nullable|string|max:500',
            'currency' => 'nullable|string|max:10',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'items' => 'nullable|array',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.cost_price' => 'nullable|numeric|min:0',
        ]);

        $data['currency'] = $data['currency'] ?? 'KES';
        $data['is_default'] = $request->boolean('is_default');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($data['is_default']) {
            PriceList::where('is_default', true)->update(['is_default' => false]);
        }

        $priceList = PriceList::create($data);

        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                $priceList->items()->create($item);
            }
        }

        return redirect()->route('hms.pricing.price-lists.index')
            ->with('status', 'Price list created successfully');
    }

    public function show(PriceList $priceList): View
    {
        $priceList->load('items.service');
        return view('hms.pricing.price-lists.show', compact('priceList'));
    }

    public function edit(PriceList $priceList): View
    {
        $priceList->load('items.service');
        $services = Service::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        return view('hms.pricing.price-lists.edit', compact('priceList', 'services'));
    }

    public function update(Request $request, PriceList $priceList)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:price_lists,name,' . $priceList->id,
            'description' => 'nullable|string|max:500',
            'currency' => 'nullable|string|max:10',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'items' => 'nullable|array',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.cost_price' => 'nullable|numeric|min:0',
        ]);

        $data['currency'] = $data['currency'] ?? $priceList->currency;
        $data['is_default'] = $request->boolean('is_default');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($data['is_default']) {
            PriceList::where('is_default', true)->where('id', '!=', $priceList->id)->update(['is_default' => false]);
        }

        $priceList->update($data);

        if (isset($data['items'])) {
            $priceList->items()->delete();
            foreach ($data['items'] as $item) {
                $priceList->items()->create($item);
            }
        }

        return redirect()->route('hms.pricing.price-lists.index')
            ->with('status', 'Price list updated successfully');
    }

    public function destroy(PriceList $priceList)
    {
        $priceList->items()->delete();
        $priceList->delete();
        return redirect()->route('hms.pricing.price-lists.index')
            ->with('status', 'Price list deleted');
    }
}
