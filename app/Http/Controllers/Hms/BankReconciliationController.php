<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\BankReconciliation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankReconciliationController extends Controller
{
    public function index(Request $request): View
    {
        $query = BankReconciliation::with(['bankAccount', 'reconciledBy'])->latest('statement_date');

        if ($request->filled('bank_account_id')) {
            $query->where('bank_account_id', $request->bank_account_id);
        }

        $reconciliations = $query->paginate(20)->withQueryString();
        $bankAccounts = BankAccount::orderBy('name')->get(['id', 'name', 'bank_name', 'account_number']);

        return view('hms.finance.bank-reconciliations.index', compact('reconciliations', 'bankAccounts'));
    }

    public function create(): View
    {
        $bankAccounts = BankAccount::where('is_active', true)->orderBy('name')->get();
        return view('hms.finance.bank-reconciliations.create', compact('bankAccounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'statement_date' => 'required|date',
            'statement_balance' => 'required|numeric',
            'book_balance' => 'required|numeric',
        ]);

        $data['difference'] = round($data['statement_balance'] - $data['book_balance'], 2);
        $data['status'] = abs($data['difference']) < 0.01 ? 'reconciled' : 'draft';
        if ($data['status'] === 'reconciled') {
            $data['reconciled_by'] = auth()->id();
            $data['reconciled_at'] = now();
        }

        BankReconciliation::create($data);

        return redirect()->route('hms.finance.bank-reconciliations.index')
            ->with('success', 'Bank reconciliation recorded.');
    }

    public function show(BankReconciliation $reconciliation): View
    {
        $reconciliation->load(['bankAccount', 'reconciledBy']);
        return view('hms.finance.bank-reconciliations.show', compact('reconciliation'));
    }

    public function markReconciled(BankReconciliation $reconciliation): RedirectResponse
    {
        if (abs((float) $reconciliation->difference) >= 0.01) {
            return back()->with('error', 'Cannot mark reconciled while difference is non-zero.');
        }

        $reconciliation->update([
            'status' => 'reconciled',
            'reconciled_by' => auth()->id(),
            'reconciled_at' => now(),
        ]);

        return redirect()->route('hms.finance.bank-reconciliations.index')
            ->with('success', 'Reconciliation marked as reconciled.');
    }
}
