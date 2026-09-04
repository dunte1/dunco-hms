<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InventoryManagementController extends Controller
{
    public function categories(): View
    {
        $categories = \App\Models\MedicineCategory::withCount('medicines')->get();
        
        $stats = [
            'total_categories' => $categories->count(),
            'total_medicines' => Medicine::count(),
        ];
        
        return view('hms.inventory.categories', compact('categories', 'stats'));
    }
    
    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:medicine_categories,name',
            'description' => 'nullable|string',
        ]);

        \App\Models\MedicineCategory::create($data);

        return redirect()->route('hms.inventory.categories')
            ->with('success', 'Category added successfully!');
    }
    
    public function updateCategory(Request $request, $id): RedirectResponse
    {
        $category = \App\Models\MedicineCategory::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:medicine_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update($data);

        return redirect()->route('hms.inventory.categories')
            ->with('success', 'Category updated successfully!');
    }
    
    public function deleteCategory($id): RedirectResponse
    {
        $category = \App\Models\MedicineCategory::findOrFail($id);
        
        if ($category->medicines()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing medicines!');
        }
        
        $category->delete();

        return redirect()->route('hms.inventory.categories')
            ->with('success', 'Category deleted successfully!');
    }

    public function suppliers(): View
    {
        $suppliers = \App\Models\Supplier::withCount('items')->get();
        
        return view('hms.inventory.suppliers', compact('suppliers'));
    }

    public function stockMovements(): View
    {
        $movements = \App\Models\StockMovement::with('medicine')->latest()->paginate(20);
        
        return view('hms.inventory.stock-movements', compact('movements'));
    }

    public function purchaseOrders(): View
    {
        $orders = \App\Models\PurchaseOrder::with('supplier')->latest()->paginate(20);
        
        return view('hms.inventory.purchase-orders', compact('orders'));
    }

    public function expiryAlerts(): View
    {
        $expiringMedicines = Medicine::with('category')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->orderBy('expiry_date')
            ->get();
        
        $stats = [
            'expiring_soon' => $expiringMedicines->where('expiry_date', '<=', now()->addDays(7))->count(),
            'expired' => Medicine::where('expiry_date', '<', now())->count(),
            'expiring_this_month' => $expiringMedicines->count(),
        ];
        
        return view('hms.inventory.expiry-alerts', compact('expiringMedicines', 'stats'));
    }
}
