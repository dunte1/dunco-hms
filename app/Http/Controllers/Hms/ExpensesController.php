<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{
    /**
     * Display a listing of expenses with statistics and filters
     */
    public function index(Request $request): View
    {
        $query = Expense::with('category');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('expense_number', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('expense_category_id')) {
            $query->where('expense_category_id', $request->expense_category_id);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('from_date')) {
            $query->whereDate('expense_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('expense_date', '<=', $request->to_date);
        }

        $expenses = $query->latest('expense_date')->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total_expenses' => Expense::sum('amount'),
            'today_expenses' => Expense::whereDate('expense_date', today())->sum('amount'),
            'this_month' => Expense::whereMonth('expense_date', now()->month)
                                  ->whereYear('expense_date', now()->year)
                                  ->sum('amount'),
            'count_today' => Expense::whereDate('expense_date', today())->count(),
            'count_month' => Expense::whereMonth('expense_date', now()->month)
                                  ->whereYear('expense_date', now()->year)
                                  ->count(),
            'pending_amount' => Expense::where('status', 'pending')->sum('amount'),
            'paid_amount' => Expense::where('status', 'paid')->sum('amount'),
        ];

        $categories = ExpenseCategory::orderBy('name')->get();

        return view('hms.finance.expenses.index', compact('expenses', 'stats', 'categories'));
    }

    /**
     * Show the form for creating a new expense
     */
    public function create(): View
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        $expenseNumber = 'EXP-' . date('Ymd') . '-' . str_pad(Expense::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        return view('hms.finance.expenses.create', compact('categories', 'expenseNumber'));
    }

    /**
     * Store a newly created expense in the database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_number' => 'required|string|unique:expenses,expense_number',
            'vendor_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,cheque,m_pesa',
            'reference_number' => 'nullable|string|max:255',
            'status' => 'required|in:pending,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        try {
            Expense::create($validated);

            return redirect()->route('hms.finance.expenses.index')
                           ->with('status', 'Expense recorded successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                       ->withErrors(['error' => 'Failed to create expense: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified expense
     */
    public function show(Expense $expense): View
    {
        $expense->load('category');
        return view('hms.finance.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense
     */
    public function edit(Expense $expense): View
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        return view('hms.finance.expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified expense in the database
     */
    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'vendor_name' => 'nullable|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,cheque,m_pesa',
            'reference_number' => 'nullable|string|max:255',
            'status' => 'required|in:pending,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        try {
            $expense->update($validated);

            return redirect()->route('hms.finance.expenses.index')
                           ->with('status', 'Expense updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                       ->withErrors(['error' => 'Failed to update expense: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified expense from the database
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        try {
            $expense->delete();

            return redirect()->route('hms.finance.expenses.index')
                           ->with('status', 'Expense deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete expense: ' . $e->getMessage()]);
        }
    }

    /**
     * Display expense categories
     */
    public function categories(): View
    {
        $categories = ExpenseCategory::withCount('expenses')->get();
        return view('hms.finance.expenses.categories', compact('categories'));
    }

    /**
     * Display expense entries (legacy view)
     */
    public function entries(): View
    {
        $expenses = Expense::with('category')->latest()->paginate(20);
        $categories = ExpenseCategory::all();
        
        return view('hms.finance.expenses.entries', compact('expenses', 'categories'));
    }

    /**
     * Generate expense reports
     */
    public function reports(Request $request): View
    {
        $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->endOfMonth()->format('Y-m-d');

        // Expenses by category
        $byCategory = Expense::select('expense_category_id', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->whereBetween('expense_date', [$fromDate, $toDate])
            ->groupBy('expense_category_id')
            ->with('category')
            ->get();

        // Expenses by payment method
        $byPaymentMethod = Expense::select('payment_method', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->whereBetween('expense_date', [$fromDate, $toDate])
            ->groupBy('payment_method')
            ->get();

        // Daily expenses
        $dailyExpenses = Expense::select(DB::raw('DATE(expense_date) as date'), DB::raw('SUM(amount) as total'))
            ->whereBetween('expense_date', [$fromDate, $toDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top vendors
        $topVendors = Expense::select('vendor_name', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->whereBetween('expense_date', [$fromDate, $toDate])
            ->whereNotNull('vendor_name')
            ->groupBy('vendor_name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        $stats = [
            'total' => Expense::whereBetween('expense_date', [$fromDate, $toDate])->sum('amount'),
            'count' => Expense::whereBetween('expense_date', [$fromDate, $toDate])->count(),
            'paid' => Expense::whereBetween('expense_date', [$fromDate, $toDate])->where('status', 'paid')->sum('amount'),
            'pending' => Expense::whereBetween('expense_date', [$fromDate, $toDate])->where('status', 'pending')->sum('amount'),
        ];

        return view('hms.finance.expenses.reports', compact('byCategory', 'byPaymentMethod', 'dailyExpenses', 'topVendors', 'stats', 'fromDate', 'toDate'));
    }
}
