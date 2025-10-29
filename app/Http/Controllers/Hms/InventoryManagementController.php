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
        $suppliers = [
            ['id' => 1, 'name' => 'MedSupply Co.', 'contact' => '+1234567890', 'email' => 'contact@medsupply.com', 'items' => 45],
            ['id' => 2, 'name' => 'HealthEquip Ltd.', 'contact' => '+0987654321', 'email' => 'info@healthequip.com', 'items' => 32],
        ];
        
        return view('hms.inventory.suppliers', compact('suppliers'));
    }

    public function stockMovements(): View
    {
        $movements = [
            ['id' => 1, 'item' => 'Surgical Gloves', 'type' => 'IN', 'quantity' => 100, 'date' => now()],
            ['id' => 2, 'item' => 'Syringes', 'type' => 'OUT', 'quantity' => 50, 'date' => now()->subDays(1)],
        ];
        
        return view('hms.inventory.stock-movements', compact('movements'));
    }

    public function purchaseOrders(): View
    {
        $orders = [
            ['id' => 1, 'order_number' => 'PO-001', 'supplier' => 'MedSupply Co.', 'total' => 5000, 'status' => 'pending', 'date' => now()],
            ['id' => 2, 'order_number' => 'PO-002', 'supplier' => 'HealthEquip Ltd.', 'total' => 3500, 'status' => 'completed', 'date' => now()->subDays(2)],
        ];
        
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
