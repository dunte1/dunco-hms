<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuppliersController extends Controller
{
    public function index(Request $request): View
    {
        $query = Supplier::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('supplier_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by supplier type
        if ($request->filled('supplier_type')) {
            $query->ofType($request->supplier_type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $suppliers = $query->latest()->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total_suppliers' => Supplier::count(),
            'active_suppliers' => Supplier::active()->count(),
            'blocked_suppliers' => Supplier::where('status', 'blocked')->count(),
            'total_credit_limit' => Supplier::active()->sum('credit_limit'),
            'total_outstanding' => Supplier::active()->sum('outstanding_balance'),
        ];
        
        return view('hms.inventory.suppliers.index', compact('suppliers', 'stats'));
    }

    public function create(): View
    {
        return view('hms.inventory.suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'supplier_code' => 'required|string|unique:suppliers,supplier_code',
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:50',
            'supplier_type' => 'required|in:medicines,equipment,consumables,food,general',
            'payment_terms' => 'required|in:cash,credit_7,credit_15,credit_30,credit_60,credit_90',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_branch' => 'nullable|string|max:255',
        ]);

        $data['country'] = $data['country'] ?? 'Kenya';
        $data['credit_limit'] = $data['credit_limit'] ?? 0;
        $data['outstanding_balance'] = 0;
        $data['status'] = 'active';

        Supplier::create($data);

        return redirect()->route('hms.inventory.suppliers.index')
            ->with('status', 'Supplier created successfully');
    }

    public function show(Supplier $supplier): View
    {
        $supplier->load('purchaseOrders');
        
        // Recent purchase orders
        $recentOrders = $supplier->purchaseOrders()
            ->latest()
            ->limit(10)
            ->get();
        
        return view('hms.inventory.suppliers.show', compact('supplier', 'recentOrders'));
    }

    public function edit(Supplier $supplier): View
    {
        return view('hms.inventory.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $data = $request->validate([
            'supplier_code' => 'required|string|unique:suppliers,supplier_code,' . $supplier->id,
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:50',
            'supplier_type' => 'required|in:medicines,equipment,consumables,food,general',
            'payment_terms' => 'required|in:cash,credit_7,credit_15,credit_30,credit_60,credit_90',
            'credit_limit' => 'nullable|numeric|min:0',
            'outstanding_balance' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,blocked',
            'notes' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_branch' => 'nullable|string|max:255',
        ]);

        $supplier->update($data);

        return redirect()->route('hms.inventory.suppliers.index')
            ->with('status', 'Supplier updated successfully');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        // Check if supplier has purchase orders
        if ($supplier->purchaseOrders()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete supplier with existing purchase orders. Please archive it instead.'
            ]);
        }

        $supplier->delete();

        return redirect()->route('hms.inventory.suppliers.index')
            ->with('status', 'Supplier deleted successfully');
    }
}

