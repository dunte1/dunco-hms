<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountsController extends Controller
{
    public function index(Request $request): View
    {
        $query = Account::with('parentAccount');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_code', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by account type
        if ($request->filled('account_type')) {
            $query->ofType($request->account_type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }
        
        $accounts = $query->orderBy('account_code')->paginate(20)->withQueryString();
        
        // Statistics
        $stats = [
            'total_accounts' => Account::count(),
            'active_accounts' => Account::active()->count(),
            'total_assets' => Account::ofType('asset')->sum('current_balance'),
            'total_liabilities' => Account::ofType('liability')->sum('current_balance'),
            'total_revenue' => Account::ofType('revenue')->sum('current_balance'),
            'total_expenses' => Account::ofType('expense')->sum('current_balance'),
        ];
        
        return view('hms.finance.accounts.index', compact('accounts', 'stats'));
    }

    public function create(): View
    {
        $parentAccounts = Account::parents()->active()->orderBy('account_name')->get();
        return view('hms.finance.accounts.create', compact('parentAccounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'account_code' => 'required|string|unique:accounts,account_code',
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense',
            'account_category' => 'nullable|in:current_asset,fixed_asset,current_liability,long_term_liability,equity,operating_revenue,other_revenue,operating_expense,other_expense',
            'parent_account_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
            'balance_type' => 'required|in:debit,credit',
            'currency' => 'nullable|string|max:3',
            'notes' => 'nullable|string',
            'allow_manual_entry' => 'nullable|boolean',
        ]);

        $data['current_balance'] = $data['opening_balance'] ?? 0;
        $data['is_active'] = true;
        $data['is_system_account'] = false;
        $data['allow_manual_entry'] = $request->has('allow_manual_entry');
        $data['currency'] = $data['currency'] ?? 'KES';

        Account::create($data);

        return redirect()->route('hms.finance.accounts.index')
            ->with('status', 'Account created successfully');
    }

    public function show(Account $account): View
    {
        $account->load(['parentAccount', 'childAccounts', 'incomes', 'expenses']);
        
        // Recent transactions
        $recentIncomes = $account->incomes()->latest()->limit(10)->get();
        $recentExpenses = $account->expenses()->latest()->limit(10)->get();
        
        return view('hms.finance.accounts.show', compact('account', 'recentIncomes', 'recentExpenses'));
    }

    public function edit(Account $account): View
    {
        // Check if system account
        if ($account->is_system_account) {
            abort(403, 'Cannot edit system accounts');
        }
        
        $parentAccounts = Account::parents()->active()->where('id', '!=', $account->id)->orderBy('account_name')->get();
        return view('hms.finance.accounts.edit', compact('account', 'parentAccounts'));
    }

    public function update(Request $request, Account $account): RedirectResponse
    {
        // Check if system account
        if ($account->is_system_account) {
            return back()->withErrors(['error' => 'Cannot edit system accounts']);
        }
        
        $data = $request->validate([
            'account_code' => 'required|string|unique:accounts,account_code,' . $account->id,
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:asset,liability,equity,revenue,expense',
            'account_category' => 'nullable|in:current_asset,fixed_asset,current_liability,long_term_liability,equity,operating_revenue,other_revenue,operating_expense,other_expense',
            'parent_account_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'balance_type' => 'required|in:debit,credit',
            'currency' => 'nullable|string|max:3',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'allow_manual_entry' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['allow_manual_entry'] = $request->has('allow_manual_entry');

        $account->update($data);

        return redirect()->route('hms.finance.accounts.index')
            ->with('status', 'Account updated successfully');
    }

    public function destroy(Account $account): RedirectResponse
    {
        // Check if system account
        if ($account->is_system_account) {
            return back()->withErrors(['error' => 'Cannot delete system accounts']);
        }
        
        // Check if has transactions
        if ($account->incomes()->count() > 0 || $account->expenses()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete account with existing transactions. Please archive it instead.'
            ]);
        }
        
        // Check if has child accounts
        if ($account->childAccounts()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete account with child accounts.'
            ]);
        }

        $account->delete();

        return redirect()->route('hms.finance.accounts.index')
            ->with('status', 'Account deleted successfully');
    }

    public function chartOfAccounts(): View
    {
        $accounts = Account::with('childAccounts')->whereNull('parent_account_id')->orderBy('account_code')->get();
        
        return view('hms.finance.chart-of-accounts', compact('accounts'));
    }

    public function ledger(Request $request): View
    {
        $account = null;
        $transactions = collect();
        
        if ($request->filled('account_id')) {
            $account = Account::with(['incomes', 'expenses'])->findOrFail($request->account_id);
            
            // Get all transactions
            $incomes = $account->incomes()
                ->when($request->filled('from_date'), function($q) use ($request) {
                    $q->whereDate('income_date', '>=', $request->from_date);
                })
                ->when($request->filled('to_date'), function($q) use ($request) {
                    $q->whereDate('income_date', '<=', $request->to_date);
                })
                ->get()
                ->map(function($income) {
                    return [
                        'date' => $income->income_date,
                        'type' => 'Income',
                        'description' => $income->description ?? $income->income_category,
                        'reference' => $income->income_number,
                        'debit' => 0,
                        'credit' => $income->amount,
                    ];
                });
            
            $expenses = $account->expenses()
                ->when($request->filled('from_date'), function($q) use ($request) {
                    $q->whereDate('expense_date', '>=', $request->from_date);
                })
                ->when($request->filled('to_date'), function($q) use ($request) {
                    $q->whereDate('expense_date', '<=', $request->to_date);
                })
                ->get()
                ->map(function($expense) {
                    return [
                        'date' => $expense->expense_date,
                        'type' => 'Expense',
                        'description' => $expense->description ?? $expense->expense_category,
                        'reference' => $expense->expense_number ?? 'N/A',
                        'debit' => $expense->amount,
                        'credit' => 0,
                    ];
                });
            
            $transactions = $incomes->merge($expenses)->sortBy('date')->values();
        }
        
        $accounts = Account::active()->orderBy('account_name')->get();
        
        return view('hms.finance.ledger', compact('account', 'transactions', 'accounts'));
    }

    public function trialBalance(): View
    {
        $accounts = Account::active()->orderBy('account_code')->get();
        
        $totalDebit = 0;
        $totalCredit = 0;
        
        $balances = $accounts->map(function($account) use (&$totalDebit, &$totalCredit) {
            $balance = $account->current_balance;
            
            if ($account->normal_balance == 'debit') {
                $debit = abs($balance);
                $credit = 0;
                $totalDebit += $debit;
            } else {
                $debit = 0;
                $credit = abs($balance);
                $totalCredit += $credit;
            }
            
            return [
                'account' => $account,
                'debit' => $debit,
                'credit' => $credit,
            ];
        });
        
        return view('hms.finance.trial-balance', compact('balances', 'totalDebit', 'totalCredit'));
    }
}
