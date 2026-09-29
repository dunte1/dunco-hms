<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ControlledDrugRegister;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ControlledDrugController extends Controller
{
    public function index(Request $request): View
    {
        $query = ControlledDrugRegister::with(['medicine', 'performedBy', 'witnessedBy']);

        if ($request->filled('medicine_id')) {
            $query->where('medicine_id', $request->medicine_id);
        }

        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        $registers = $query->latest()->paginate(20)->withQueryString();

        return view('hms.pharmacy.controlled-drugs.index', compact('registers'));
    }

    public function recordTransaction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'batch_id' => 'nullable|exists:medicine_batches,id',
            'transaction_type' => 'required|in:received,dispensed,wasted,returned,stock_check',
            'quantity' => 'required|numeric|min:0.01',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|integer',
            'witnessed_by' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $medicine = Medicine::findOrFail($validated['medicine_id']);
        $lastBalance = ControlledDrugRegister::where('medicine_id', $validated['medicine_id'])
            ->latest('id')
            ->value('balance_after') ?? 0;

        $quantity = $validated['quantity'];
        $balanceAfter = match ($validated['transaction_type']) {
            'received', 'returned' => $lastBalance + $quantity,
            'dispensed', 'wasted' => $lastBalance - $quantity,
            'stock_check' => $quantity,
        };

        if ($balanceAfter < 0) {
            return back()->with('error', 'Insufficient stock. Current balance is ' . $lastBalance . '.');
        }

        ControlledDrugRegister::create([
            ...$validated,
            'balance_after' => $balanceAfter,
            'performed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Controlled drug transaction recorded successfully.');
    }
}
