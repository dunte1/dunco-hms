<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackagesController extends Controller
{
    public function index(Request $request): View
    {
        $query = Package::with('items');
        
        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }
        
        $packages = $query->orderBy('name')->paginate(12);
        
        // Statistics
        $stats = [
            'total_packages' => Package::count(),
            'active_packages' => Package::where('is_active', true)->count(),
            'total_value' => Package::where('is_active', true)->sum('price'),
            'avg_package_price' => Package::where('is_active', true)->avg('price'),
        ];
        
        return view('hms.packages.index', compact('packages', 'stats'));
    }

    public function create(): View
    {
        return view('hms.packages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'inclusions' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|string',
            'items.*.item_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $package = Package::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'duration_days' => $data['duration_days'],
            'inclusions' => $data['inclusions'],
            'terms_conditions' => $data['terms_conditions'],
        ]);

        // Create package items
        foreach ($data['items'] as $item) {
            $package->items()->create([
                'item_type' => $item['item_type'],
                'item_name' => $item['item_name'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('hms.packages.index')->with('status', 'Package created');
    }

    public function show(Package $package): View
    {
        $package->load('items');
        return view('hms.packages.show', compact('package'));
    }

    public function edit(Package $package): View
    {
        $package->load('items');
        return view('hms.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'inclusions' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|string',
            'items.*.item_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $package->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'duration_days' => $data['duration_days'],
            'inclusions' => $data['inclusions'],
            'terms_conditions' => $data['terms_conditions'],
            'is_active' => $request->has('is_active'),
        ]);

        // Delete existing items and recreate
        $package->items()->delete();
        
        foreach ($data['items'] as $item) {
            $package->items()->create([
                'item_type' => $item['item_type'],
                'item_name' => $item['item_name'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('hms.packages.index')->with('status', 'Package updated successfully');
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();
        return redirect()->route('hms.packages.index')->with('status', 'Package deleted successfully');
    }
}
