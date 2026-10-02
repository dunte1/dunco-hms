<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Service::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $services = $query->orderBy('category')->orderBy('name')->paginate(20)->withQueryString();
        $categories = Service::distinct()->pluck('category')->filter()->sort()->values();

        return view('hms.pricing.services.index', compact('services', 'categories'));
    }

    public function create(): View
    {
        return view('hms.pricing.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:services,code',
            'category' => 'required|in:consultation,lab,radiology,pharmacy,procedure,bed,meal,other',
            'description' => 'nullable|string|max:500',
            'default_price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'is_active' => 'nullable|boolean',
            'is_taxable' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['currency'] = $data['currency'] ?? 'KES';
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_taxable'] = $request->boolean('is_taxable', false);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if (empty($data['code'])) {
            $prefix = strtoupper(substr($data['category'], 0, 3));
            $data['code'] = $prefix . '-' . str_pad(Service::count() + 1, 4, '0', STR_PAD_LEFT);
        }

        Service::create($data);

        return redirect()->route('hms.pricing.services.index')
            ->with('status', 'Service created successfully');
    }

    public function show(Service $service): View
    {
        return view('hms.pricing.services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        return view('hms.pricing.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:services,code,' . $service->id,
            'category' => 'required|in:consultation,lab,radiology,pharmacy,procedure,bed,meal,other',
            'description' => 'nullable|string|max:500',
            'default_price' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'is_active' => 'nullable|boolean',
            'is_taxable' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['currency'] = $data['currency'] ?? $service->currency;
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_taxable'] = $request->boolean('is_taxable', false);

        $service->update($data);

        return redirect()->route('hms.pricing.services.index')
            ->with('status', 'Service updated successfully');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('hms.pricing.services.index')
            ->with('status', 'Service deleted');
    }

    public function getPrices(Service $service)
    {
        $priceItem = $service->priceItems()->where('is_active', true)
            ->orderByDesc('effective_from')
            ->first();

        return response()->json([
            'service_id' => $service->id,
            'name' => $service->name,
            'category' => $service->category,
            'default_price' => $service->default_price,
            'current_price' => $priceItem ? $priceItem->price : $service->default_price,
        ]);
    }
}
