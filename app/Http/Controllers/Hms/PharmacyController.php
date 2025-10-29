<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\MedicineCategory;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class PharmacyController extends Controller
{
    public function index(): View
    {
        // Calculate real pharmacy statistics
        $stats = [
            ['label' => 'Total Prescriptions', 'value' => Prescription::count()],
            ['label' => 'Dispensed Today', 'value' => Prescription::whereDate('prescription_date', today())->where('status', 'dispensed')->count()],
            ['label' => 'Stock Items', 'value' => Medicine::count()],
            ['label' => 'Low Stock Items', 'value' => Medicine::whereRaw('stock_quantity <= minimum_stock')->count()],
        ];

        // Get recent prescriptions
        $recentPrescriptions = Prescription::with(['patient', 'doctor', 'items.medicine'])
            ->latest()
            ->take(10)
            ->get();

        // Get low stock medicines
        $lowStockMedicines = Medicine::with('category')
            ->whereRaw('stock_quantity <= minimum_stock')
            ->get();

        return view('hms.pharmacy.index', compact('stats', 'recentPrescriptions', 'lowStockMedicines'));
    }

    // Medicine Management
    public function medicines(): View
    {
        $medicines = Medicine::with('category')
            ->latest()
            ->paginate(20);
        
        $categories = MedicineCategory::all();
        
        return view('hms.pharmacy.medicines.index', compact('medicines', 'categories'));
    }

    public function createMedicine(): View
    {
        $categories = MedicineCategory::all();
        return view('hms.pharmacy.medicines.create', compact('categories'));
    }

    public function storeMedicine(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:medicine_categories,id',
            'manufacturer' => 'required|string|max:255',
            'dosage_form' => 'required|string|max:100',
            'strength' => 'required|string|max:100',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expiry_date' => 'required|date|after:today',
            'description' => 'nullable|string',
        ]);

        Medicine::create($validated);

        return redirect()->route('hms.pharmacy.medicines')
            ->with('success', 'Medicine added successfully.');
    }

    public function editMedicine(Medicine $medicine): View
    {
        $categories = MedicineCategory::all();
        return view('hms.pharmacy.medicines.edit', compact('medicine', 'categories'));
    }

    public function updateMedicine(Request $request, Medicine $medicine): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:medicine_categories,id',
            'manufacturer' => 'required|string|max:255',
            'dosage_form' => 'required|string|max:100',
            'strength' => 'required|string|max:100',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'expiry_date' => 'required|date|after:today',
            'description' => 'nullable|string',
        ]);

        $medicine->update($validated);

        return redirect()->route('hms.pharmacy.medicines')
            ->with('success', 'Medicine updated successfully.');
    }

    public function destroyMedicine(Medicine $medicine): RedirectResponse
    {
        $medicine->delete();
        return redirect()->route('hms.pharmacy.medicines')
            ->with('success', 'Medicine deleted successfully.');
    }

    // Prescription Management
    public function prescriptions(): View
    {
        $prescriptions = Prescription::with(['patient', 'doctor', 'items.medicine'])
            ->latest()
            ->paginate(20);
        
        return view('hms.pharmacy.prescriptions.index', compact('prescriptions'));
    }

    public function showPrescription(Prescription $prescription): View
    {
        $prescription->load(['patient', 'doctor', 'items.medicine']);
        return view('hms.pharmacy.prescriptions.show', compact('prescription'));
    }

    public function dispensePrescription(Prescription $prescription): RedirectResponse
    {
        DB::transaction(function () use ($prescription) {
            // Update prescription status
            $prescription->update(['status' => 'dispensed']);

            // Update medicine stock quantities
            foreach ($prescription->items as $item) {
                $medicine = $item->medicine;
                $newStock = $medicine->stock_quantity - $item->quantity;
                
                if ($newStock < 0) {
                    throw new \Exception("Insufficient stock for {$medicine->name}");
                }
                
                $medicine->update(['stock_quantity' => $newStock]);
            }
        });

        return redirect()->route('hms.pharmacy.prescriptions')
            ->with('success', 'Prescription dispensed successfully.');
    }

    // Inventory Management
    public function inventory(): View
    {
        $medicines = Medicine::with('category')
            ->orderBy('stock_quantity', 'asc')
            ->paginate(20);
        
        $lowStockCount = Medicine::whereRaw('stock_quantity <= minimum_stock')->count();
        $expiringSoon = Medicine::where('expiry_date', '<=', now()->addDays(30))->count();
        
        return view('hms.pharmacy.inventory', compact('medicines', 'lowStockCount', 'expiringSoon'));
    }

    public function updateStock(Request $request, Medicine $medicine): RedirectResponse
    {
        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $medicine->update($validated);

        return redirect()->route('hms.pharmacy.inventory')
            ->with('success', 'Stock updated successfully.');
    }

    // Reports
    public function reports(): View
    {
        $monthlyPrescriptions = Prescription::selectRaw('MONTH(prescription_date) as month, COUNT(*) as count')
            ->whereYear('prescription_date', now()->year)
            ->groupBy('month')
            ->get();

        $topMedicines = Medicine::withCount('prescriptionItems')
            ->orderBy('prescription_items_count', 'desc')
            ->take(10)
            ->get();

        $lowStockMedicines = Medicine::with('category')
            ->whereRaw('stock_quantity <= minimum_stock')
            ->get();

        return view('hms.pharmacy.reports', compact('monthlyPrescriptions', 'topMedicines', 'lowStockMedicines'));
    }
}


