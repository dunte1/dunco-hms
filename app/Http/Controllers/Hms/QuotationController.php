<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Rfq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function store(Request $request, Rfq $rfq): RedirectResponse
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'valid_until' => 'required|date|after:today',
            'quoted_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.delivery_days' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $quotation = Quotation::create([
                'rfq_id' => $rfq->id,
                'supplier_id' => $data['supplier_id'],
                'total_amount' => $data['total_amount'],
                'currency' => $data['currency'] ?? 'KES',
                'valid_until' => $data['valid_until'],
                'status' => 'received',
                'quoted_by' => $data['quoted_by'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_description' => $item['product_description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['quantity'] * $item['unit_price'],
                    'delivery_days' => $item['delivery_days'] ?? null,
                ]);
            }

            if ($rfq->status === 'draft') {
                $rfq->update(['status' => 'sent']);
            }

            DB::commit();

            \App\Models\AuditLog::log('user', auth()->id(), 'quotation_submitted', 'Quotation', $quotation->id, null, ['status' => 'received'], 'Quotation submitted for RFQ: ' . $rfq->rfq_number);

            return back()->with('status', 'Quotation submitted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to submit quotation: ' . $e->getMessage()]);
        }
    }

    public function accept(Quotation $quotation): RedirectResponse
    {
        if ($quotation->status !== 'received') {
            return back()->with('error', 'Only received quotations can be accepted');
        }

        $quotation->update(['status' => 'accepted']);

        $quotation->rfq()->update(['status' => 'closed']);

        \App\Models\AuditLog::log('user', auth()->id(), 'quotation_accepted', 'Quotation', $quotation->id, ['status' => 'received'], ['status' => 'accepted'], 'Quotation accepted');

        return back()->with('status', 'Quotation accepted');
    }

    public function reject(Quotation $quotation): RedirectResponse
    {
        if ($quotation->status !== 'received') {
            return back()->with('error', 'Only received quotations can be rejected');
        }

        $quotation->update(['status' => 'rejected']);

        \App\Models\AuditLog::log('user', auth()->id(), 'quotation_rejected', 'Quotation', $quotation->id, ['status' => 'received'], ['status' => 'rejected'], 'Quotation rejected');

        return back()->with('status', 'Quotation rejected');
    }
}
