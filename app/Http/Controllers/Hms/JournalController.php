<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\FiscalPeriod;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index(Request $request): View
    {
        $query = JournalEntry::with(['fiscalPeriod', 'postedBy', 'reversedBy']);

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $entries = $query->latest('date')->paginate(20)->withQueryString();

        return view('hms.finance.journal.index', compact('entries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:500',
            'fiscal_period_id' => 'required|exists:fiscal_periods,id',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'nullable|numeric|min:0',
            'lines.*.credit' => 'nullable|numeric|min:0',
            'lines.*.description' => 'nullable|string|max:500',
        ]);

        $period = FiscalPeriod::findOrFail($data['fiscal_period_id']);
        if ($period->status !== 'open') {
            return back()->withErrors(['fiscal_period_id' => 'Cannot create entries in a closed period.']);
        }

        $totalDebit = collect($data['lines'])->sum('debit');
        $totalCredit = collect($data['lines'])->sum('credit');

        if (abs($totalDebit - $totalCredit) >= 0.01) {
            return back()->withErrors(['lines' => 'Total debits must equal total credits.'])
                ->withInput();
        }

        $entry = DB::transaction(function () use ($data) {
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateEntryNumber(),
                'date' => $data['date'],
                'description' => $data['description'],
                'fiscal_period_id' => $data['fiscal_period_id'],
                'status' => 'draft',
            ]);

            foreach ($data['lines'] as $line) {
                $entry->lines()->create([
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'] ?? 0,
                    'credit' => $line['credit'] ?? 0,
                    'description' => $line['description'] ?? null,
                ]);
            }

            return $entry;
        });

        return redirect()->route('hms.journal.show', $entry)
            ->with('status', 'Journal entry created successfully.');
    }

    public function show(JournalEntry $entry): View
    {
        $entry->load(['lines.account', 'fiscalPeriod', 'postedBy', 'reversedBy']);

        return view('hms.finance.journal.show', compact('entry'));
    }

    public function post(JournalEntry $entry): RedirectResponse
    {
        if ($entry->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft entries can be posted.']);
        }

        $entry->post(auth()->id());

        return back()->with('status', 'Journal entry posted successfully.');
    }

    public function reverse(JournalEntry $entry): RedirectResponse
    {
        if ($entry->status !== 'posted') {
            return back()->withErrors(['error' => 'Only posted entries can be reversed.']);
        }

        $reversedEntry = $entry->reverse(auth()->id());

        return redirect()->route('hms.journal.show', $reversedEntry)
            ->with('status', 'Journal entry reversed successfully.');
    }

    public function closePeriod(FiscalPeriod $period): RedirectResponse
    {
        if ($period->status !== 'open') {
            return back()->withErrors(['error' => 'Period is not open.']);
        }

        $hasDrafts = JournalEntry::where('fiscal_period_id', $period->id)
            ->where('status', 'draft')
            ->exists();

        if ($hasDrafts) {
            return back()->withErrors(['error' => 'Cannot close period with pending draft entries.']);
        }

        $period->close(auth()->id());

        return back()->with('status', 'Fiscal period closed successfully.');
    }
}
