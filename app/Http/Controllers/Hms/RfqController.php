<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Rfq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RfqController extends Controller
{
    public function index(Request $request): View
    {
        $query = Rfq::with(['requisition', 'issuer', 'quotations.supplier']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('rfq_number', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rfqs = $query->orderByDesc('created_at')->paginate(20);

        $stats = [
            'total' => Rfq::count(),
            'draft' => Rfq::where('status', 'draft')->count(),
            'sent' => Rfq::where('status', 'sent')->count(),
            'closed' => Rfq::where('status', 'closed')->count(),
        ];

        return view('hms.procurement.rfqs.index', compact('rfqs', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requisition_id' => 'nullable|exists:requisitions,id',
            'issued_date' => 'required|date',
            'closing_date' => 'nullable|date|after_or_equal:issued_date',
        ]);

        $rfq = Rfq::create([
            ...$data,
            'status' => 'draft',
            'issued_by' => auth()->id(),
        ]);

        \App\Models\AuditLog::log('user', auth()->id(), 'rfq_created', 'Rfq', $rfq->id, null, ['status' => 'draft'], 'RFQ created: ' . $rfq->rfq_number);

        return back()->with('status', 'RFQ created successfully');
    }

    public function close(Rfq $rfq): RedirectResponse
    {
        if ($rfq->status !== 'sent') {
            return back()->with('error', 'Only sent RFQs can be closed');
        }

        $rfq->update(['status' => 'closed']);

        \App\Models\AuditLog::log('user', auth()->id(), 'rfq_closed', 'Rfq', $rfq->id, ['status' => 'sent'], ['status' => 'closed'], 'RFQ closed: ' . $rfq->rfq_number);

        return back()->with('status', 'RFQ closed successfully');
    }
}
