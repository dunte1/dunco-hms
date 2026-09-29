<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\SupplierInvoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierInvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = SupplierInvoice::with(['supplier', 'verifier']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                ->orWhereHas('supplier', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderByDesc('created_at')->paginate(20);

        $stats = [
            'total' => SupplierInvoice::count(),
            'received' => SupplierInvoice::where('status', 'received')->count(),
            'verified' => SupplierInvoice::where('status', 'verified')->count(),
            'paid' => SupplierInvoice::where('status', 'paid')->count(),
        ];

        return view('hms.procurement.supplier-invoices.index', compact('invoices', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice_number' => 'required|string|unique:supplier_invoices,invoice_number',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'grn_id' => 'nullable|exists:goods_received_notes,id',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string',
        ]);

        $invoice = SupplierInvoice::create([
            ...$data,
            'tax_amount' => $data['tax_amount'] ?? 0,
            'status' => 'received',
        ]);

        \App\Models\AuditLog::log('user', auth()->id(), 'supplier_invoice_created', 'SupplierInvoice', $invoice->id, null, ['status' => 'received'], 'Supplier invoice created: ' . $invoice->invoice_number);

        return back()->with('status', 'Supplier invoice recorded successfully');
    }

    public function verify(SupplierInvoice $invoice): RedirectResponse
    {
        if ($invoice->status !== 'received') {
            return back()->with('error', 'Only received invoices can be verified');
        }

        $invoice->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        \App\Models\AuditLog::log('user', auth()->id(), 'supplier_invoice_verified', 'SupplierInvoice', $invoice->id, ['status' => 'received'], ['status' => 'verified'], 'Supplier invoice verified: ' . $invoice->invoice_number);

        return back()->with('status', 'Supplier invoice verified');
    }
}
