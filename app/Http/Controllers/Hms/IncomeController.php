<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Account;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Income::with(['account', 'patient', 'recorder']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('income_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }
        
        // Filter by account
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        
        // Filter by category
        if ($request->filled('income_category')) {
            $query->category($request->income_category);
        }
        
        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->paymentMethod($request->payment_method);
        }
        
        // Filter by date range
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->dateRange($request->from_date, $request->to_date);
        }
        
        $incomes = $query->latest('income_date')->paginate(20)->withQueryString();
        
        // Statistics
        $stats = [
            'total_income' => Income::sum('amount'),
            'today_income' => Income::today()->sum('amount'),
            'this_month' => Income::thisMonth()->sum('amount'),
            'count_today' => Income::today()->count(),
            'count_month' => Income::thisMonth()->count(),
        ];
        
        $accounts = Account::active()->ofType('revenue')->orderBy('account_name')->get();
        
        return view('hms.finance.income.index', compact('incomes', 'stats', 'accounts'));
    }

    public function create(): View
    {
        $accounts = Account::active()->ofType('revenue')->orderBy('account_name')->get();
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        
        // Generate income number
        $lastIncome = Income::latest()->first();
        $nextNumber = $lastIncome ? intval(substr($lastIncome->income_number, 3)) + 1 : 1;
        $incomeNumber = 'IN-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        
        return view('hms.finance.income.create', compact('accounts', 'patients', 'incomeNumber'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'income_number' => 'required|string|unique:incomes,income_number',
            'account_id' => 'required|exists:accounts,id',
            'income_category' => 'required|in:patient_services,pharmacy_sales,lab_tests,radiology,consultation_fees,admission_fees,surgery_fees,ambulance_services,other',
            'source' => 'nullable|string|max:255',
            'patient_id' => 'nullable|exists:patients,id',
            'amount' => 'required|numeric|min:0',
            'income_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,cheque,m_pesa,insurance',
            'reference_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_recurring' => 'nullable|boolean',
            'recurring_frequency' => 'nullable|in:daily,weekly,monthly,yearly',
        ]);

        $data['recorded_by'] = Auth::id();
        $data['is_recurring'] = $request->has('is_recurring');

        Income::create($data);

        return redirect()->route('hms.finance.income.index')
            ->with('status', 'Income recorded successfully');
    }

    public function show(Income $income): View
    {
        $income->load(['account', 'patient', 'recorder', 'invoice', 'payment']);
        
        return view('hms.finance.income.show', compact('income'));
    }

    public function edit(Income $income): View
    {
        $accounts = Account::active()->ofType('revenue')->orderBy('account_name')->get();
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        
        return view('hms.finance.income.edit', compact('income', 'accounts', 'patients'));
    }

    public function update(Request $request, Income $income): RedirectResponse
    {
        $data = $request->validate([
            'income_number' => 'required|string|unique:incomes,income_number,' . $income->id,
            'account_id' => 'required|exists:accounts,id',
            'income_category' => 'required|in:patient_services,pharmacy_sales,lab_tests,radiology,consultation_fees,admission_fees,surgery_fees,ambulance_services,other',
            'source' => 'nullable|string|max:255',
            'patient_id' => 'nullable|exists:patients,id',
            'amount' => 'required|numeric|min:0',
            'income_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,cheque,m_pesa,insurance',
            'reference_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_recurring' => 'nullable|boolean',
            'recurring_frequency' => 'nullable|in:daily,weekly,monthly,yearly',
        ]);

        $data['is_recurring'] = $request->has('is_recurring');

        $income->update($data);

        return redirect()->route('hms.finance.income.index')
            ->with('status', 'Income updated successfully');
    }

    public function destroy(Income $income): RedirectResponse
    {
        $income->delete();

        return redirect()->route('hms.finance.income.index')
            ->with('status', 'Income deleted successfully');
    }

    public function reports(Request $request): View
    {
        $fromDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->endOfMonth()->format('Y-m-d'));
        
        $incomes = Income::with('account')
            ->dateRange($fromDate, $toDate)
            ->get();
        
        // Group by category
        $byCategory = $incomes->groupBy('income_category')->map(function($items) {
            return [
                'count' => $items->count(),
                'total' => $items->sum('amount'),
            ];
        });
        
        // Group by payment method
        $byPaymentMethod = $incomes->groupBy('payment_method')->map(function($items) {
            return [
                'count' => $items->count(),
                'total' => $items->sum('amount'),
            ];
        });
        
        // Daily breakdown
        $dailyBreakdown = $incomes->groupBy(function($item) {
            return $item->income_date->format('Y-m-d');
        })->map(function($items) {
            return $items->sum('amount');
        });
        
        $totalIncome = $incomes->sum('amount');
        
        return view('hms.finance.income.reports', compact('byCategory', 'byPaymentMethod', 'dailyBreakdown', 'totalIncome', 'fromDate', 'toDate'));
    }
}
