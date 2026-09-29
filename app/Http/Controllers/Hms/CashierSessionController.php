<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CashierSession;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CashierSessionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Check if user already has an open session
        $existingSession = CashierSession::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($existingSession) {
            return back()->withErrors(['error' => 'You already have an open cashier session. Please close it first.']);
        }

        $data = $request->validate([
            'opening_balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $session = CashierSession::open(
            auth()->id(),
            $data['opening_balance'],
            $data['notes'] ?? null
        );

        return back()->with('status', 'Cashier session opened successfully');
    }

    public function close(Request $request): RedirectResponse
    {
        $session = CashierSession::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$session) {
            return back()->withErrors(['error' => 'No open cashier session found.']);
        }

        $data = $request->validate([
            'closing_balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $result = $session->reconcile(
            $data['closing_balance'],
            $data['notes'] ?? null
        );

        $message = $result['is_balanced']
            ? 'Cashier session closed. Balance is correct.'
            : 'Cashier session closed. Variance: $' . number_format($result['variance'], 2);

        return back()->with('status', $message);
    }
}
