<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\StockIssue;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockIssueController extends Controller
{
    public function index(Request $request): View
    {
        $query = StockIssue::with(['store', 'issuedToUser']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('issue_number', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        $issues = $query->orderByDesc('created_at')->paginate(20);

        $stats = [
            'total' => StockIssue::count(),
            'pending' => StockIssue::where('status', 'pending')->count(),
            'issued' => StockIssue::where('status', 'issued')->count(),
            'returned' => StockIssue::where('status', 'returned')->count(),
        ];

        $stores = Store::active()->orderBy('name')->get();

        return view('hms.stores.issues.index', compact('issues', 'stats', 'stores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'issued_to_user_id' => 'required|exists:users,id',
            'issued_to_department' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $issue = StockIssue::create([
            'store_id' => $data['store_id'],
            'issued_to_user_id' => $data['issued_to_user_id'],
            'issued_to_department' => $data['issued_to_department'] ?? null,
            'items' => $data['items'],
            'status' => 'issued',
            'issued_at' => now(),
            'notes' => $data['notes'] ?? null,
        ]);

        \App\Models\AuditLog::log('user', auth()->id(), 'stock_issued', 'StockIssue', $issue->id, null, ['status' => 'issued'], 'Stock issued: ' . $issue->issue_number);

        return back()->with('status', 'Stock issue recorded successfully');
    }
}
