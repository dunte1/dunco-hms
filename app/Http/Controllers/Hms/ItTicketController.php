<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ItTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ItTicketController extends Controller
{
    public function index(): JsonResponse
    {
        $tickets = ItTicket::with(['reportedBy', 'assignedTo'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($tickets);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:hardware,software,network,email,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $data['ticket_number'] = 'TKT-' . str_pad(ItTicket::count() + 1, 6, '0', STR_PAD_LEFT);
        $data['reported_by'] = auth()->id();
        $data['reported_at'] = now();
        $data['status'] = 'open';

        ItTicket::create($data);

        return back()->with('status', 'IT ticket created');
    }

    public function resolve(Request $request, ItTicket $ticket): RedirectResponse
    {
        $data = $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $ticket->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_notes' => $data['resolution_notes'],
        ]);

        return back()->with('status', 'Ticket resolved');
    }
}
