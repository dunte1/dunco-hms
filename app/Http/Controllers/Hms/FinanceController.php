<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class FinanceController extends Controller
{
    /**
     * Display the finance dashboard
     */
    public function index(): View
    {
        // Current month stats
        $thisMonth = now()->startOfMonth();
        
        $stats = [
            'total_income' => Income::sum('amount'),
            'total_expenses' => Expense::sum('amount'),
            'month_income' => Income::whereMonth('income_date', now()->month)
                                   ->whereYear('income_date', now()->year)
                                   ->sum('amount'),
            'month_expenses' => Expense::whereMonth('expense_date', now()->month)
                                     ->whereYear('expense_date', now()->year)
                                     ->sum('amount'),
            'today_income' => Income::whereDate('income_date', today())->sum('amount'),
            'today_expenses' => Expense::whereDate('expense_date', today())->sum('amount'),
        ];

        $stats['net_profit'] = $stats['total_income'] - $stats['total_expenses'];
        $stats['month_profit'] = $stats['month_income'] - $stats['month_expenses'];
        $stats['today_profit'] = $stats['today_income'] - $stats['today_expenses'];

        // Top income categories this month
        $topIncomeCategories = Income::select('income_category', DB::raw('SUM(amount) as total'))
            ->whereMonth('income_date', now()->month)
            ->whereYear('income_date', now()->year)
            ->groupBy('income_category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Top expense categories this month
        $topExpenseCategories = Expense::select('expense_category_id', DB::raw('SUM(amount) as total'))
            ->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->groupBy('expense_category_id')
            ->with('category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Recent transactions
        $recentIncome = Income::with('account')->latest('income_date')->limit(5)->get();
        $recentExpenses = Expense::with('category')->latest('expense_date')->limit(5)->get();

        return view('hms.finance.index', compact(
            'stats',
            'topIncomeCategories',
            'topExpenseCategories',
            'recentIncome',
            'recentExpenses'
        ));
    }

    /**
     * Display comprehensive financial reports
     */
    public function reports(Request $request): View
    {
        $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->endOfMonth()->format('Y-m-d');

        // Income & Expense Summary
        $totalIncome = Income::whereBetween('income_date', [$fromDate, $toDate])->sum('amount');
        $totalExpenses = Expense::whereBetween('expense_date', [$fromDate, $toDate])->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;

        // Income by category
        $incomeByCategory = Income::select('income_category', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->whereBetween('income_date', [$fromDate, $toDate])
            ->groupBy('income_category')
            ->get();

        // Expenses by category
        $expensesByCategory = Expense::select('expense_category_id', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->whereBetween('expense_date', [$fromDate, $toDate])
            ->groupBy('expense_category_id')
            ->with('category')
            ->get();

        // Monthly trend (last 12 months)
        $monthlyTrend = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyTrend[] = [
                'month' => $month->format('M Y'),
                'income' => Income::whereMonth('income_date', $month->month)
                                 ->whereYear('income_date', $month->year)
                                 ->sum('amount'),
                'expenses' => Expense::whereMonth('expense_date', $month->month)
                                   ->whereYear('expense_date', $month->year)
                                   ->sum('amount'),
            ];
        }

        $stats = [
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'profit_margin' => $totalIncome > 0 ? ($netProfit / $totalIncome) * 100 : 0,
        ];

        return view('hms.finance.reports', compact(
            'stats',
            'incomeByCategory',
            'expensesByCategory',
            'monthlyTrend',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Display Profit & Loss statement
     */
    public function profitLoss(Request $request): View
    {
        $fromDate = $request->from_date ?? now()->startOfYear()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->endOfYear()->format('Y-m-d');

        // Income breakdown
        $revenue = [
            'patient_services' => Income::where('income_category', 'patient_services')
                                      ->whereBetween('income_date', [$fromDate, $toDate])
                                      ->sum('amount'),
            'pharmacy_sales' => Income::where('income_category', 'pharmacy_sales')
                                     ->whereBetween('income_date', [$fromDate, $toDate])
                                     ->sum('amount'),
            'lab_tests' => Income::where('income_category', 'lab_tests')
                                ->whereBetween('income_date', [$fromDate, $toDate])
                                ->sum('amount'),
            'radiology' => Income::where('income_category', 'radiology')
                                ->whereBetween('income_date', [$fromDate, $toDate])
                                ->sum('amount'),
            'consultation_fees' => Income::where('income_category', 'consultation_fees')
                                        ->whereBetween('income_date', [$fromDate, $toDate])
                                        ->sum('amount'),
            'other' => Income::where('income_category', 'other')
                            ->whereBetween('income_date', [$fromDate, $toDate])
                            ->sum('amount'),
        ];

        $totalRevenue = array_sum($revenue);

        // Expenses breakdown by category
        $expenses = Expense::select('expense_category_id', DB::raw('SUM(amount) as total'))
            ->whereBetween('expense_date', [$fromDate, $toDate])
            ->groupBy('expense_category_id')
            ->with('category')
            ->get();

        $totalExpenses = $expenses->sum('total');
        $grossProfit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        return view('hms.finance.profit-loss', compact(
            'revenue',
            'totalRevenue',
            'expenses',
            'totalExpenses',
            'grossProfit',
            'profitMargin',
            'fromDate',
            'toDate'
        ));
    }
    
    /**
     * Download Profit & Loss Statement as PDF
     */
    public function profitLossPdf(Request $request): Response
    {
        $fromDate = $request->from_date ?? now()->startOfYear()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->endOfYear()->format('Y-m-d');

        // Income breakdown
        $revenue = [
            'patient_services' => Income::where('income_category', 'patient_services')
                                      ->whereBetween('income_date', [$fromDate, $toDate])
                                      ->sum('amount'),
            'pharmacy_sales' => Income::where('income_category', 'pharmacy_sales')
                                     ->whereBetween('income_date', [$fromDate, $toDate])
                                     ->sum('amount'),
            'lab_tests' => Income::where('income_category', 'lab_tests')
                                ->whereBetween('income_date', [$fromDate, $toDate])
                                ->sum('amount'),
            'radiology' => Income::where('income_category', 'radiology')
                                ->whereBetween('income_date', [$fromDate, $toDate])
                                ->sum('amount'),
            'consultation_fees' => Income::where('income_category', 'consultation_fees')
                                        ->whereBetween('income_date', [$fromDate, $toDate])
                                        ->sum('amount'),
            'other' => Income::where('income_category', 'other')
                            ->whereBetween('income_date', [$fromDate, $toDate])
                            ->sum('amount'),
        ];

        $totalRevenue = array_sum($revenue);

        // Expenses breakdown by category
        $expenses = Expense::select('expense_category_id', DB::raw('SUM(amount) as total'))
            ->whereBetween('expense_date', [$fromDate, $toDate])
            ->groupBy('expense_category_id')
            ->get();

        $totalExpenses = $expenses->sum('total');
        $grossProfit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        $pdf = Pdf::loadView('hms.finance.profit-loss-pdf', compact(
            'revenue',
            'totalRevenue',
            'expenses',
            'totalExpenses',
            'grossProfit',
            'profitMargin',
            'fromDate',
            'toDate'
        ));

        return $pdf->download('profit-loss-' . $fromDate . '-to-' . $toDate . '.pdf');
    }

    /**
     * Display Balance Sheet
     */
    public function balanceSheet(Request $request): View
    {
        $asOfDate = $request->as_of_date ?? now()->format('Y-m-d');

        // Assets
        $assets = Account::where('account_type', 'asset')
            ->where('is_active', true)
            ->get();

        // Liabilities
        $liabilities = Account::where('account_type', 'liability')
            ->where('is_active', true)
            ->get();

        // Equity
        $equity = Account::where('account_type', 'equity')
            ->where('is_active', true)
            ->get();

        $totalAssets = $assets->sum('current_balance');
        $totalLiabilities = $liabilities->sum('current_balance');
        $totalEquity = $equity->sum('current_balance');

        // Calculate retained earnings (profit/loss to date)
        $retainedEarnings = Income::whereDate('income_date', '<=', $asOfDate)->sum('amount')
                          - Expense::whereDate('expense_date', '<=', $asOfDate)->sum('amount');

        return view('hms.finance.balance-sheet', compact(
            'assets',
            'liabilities',
            'equity',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'retainedEarnings',
            'asOfDate'
        ));
    }

    /**
     * Display Cash Flow statement
     */
    public function cashFlow(Request $request): View
    {
        $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->endOfMonth()->format('Y-m-d');

        // Operating Activities - Cash basis
        $cashInflows = Income::whereBetween('income_date', [$fromDate, $toDate])
            ->whereIn('payment_method', ['cash', 'm_pesa', 'card'])
            ->sum('amount');

        $cashOutflows = Expense::whereBetween('expense_date', [$fromDate, $toDate])
            ->where('status', 'paid')
            ->whereIn('payment_method', ['cash', 'm_pesa', 'card'])
            ->sum('amount');

        $netCashFlow = $cashInflows - $cashOutflows;

        // Breakdown by payment method
        $inflowsByMethod = Income::select('payment_method', DB::raw('SUM(amount) as total'))
            ->whereBetween('income_date', [$fromDate, $toDate])
            ->groupBy('payment_method')
            ->get();

        $outflowsByMethod = Expense::select('payment_method', DB::raw('SUM(amount) as total'))
            ->whereBetween('expense_date', [$fromDate, $toDate])
            ->where('status', 'paid')
            ->groupBy('payment_method')
            ->get();

        return view('hms.finance.cash-flow', compact(
            'cashInflows',
            'cashOutflows',
            'netCashFlow',
            'inflowsByMethod',
            'outflowsByMethod',
            'fromDate',
            'toDate'
        ));
    }
}

