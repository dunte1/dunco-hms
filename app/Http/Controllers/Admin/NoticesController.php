<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticesController extends Controller
{
    public function index(): View
    {
        $notices = Notice::latest()->paginate(20);
        
        $totalNotices = Notice::count();
        $publishedNotices = Notice::whereNotNull('published_at')->count();
        $todayNotices = Notice::whereDate('created_at', today())->count();
        
        return view('admin.notices.index', compact('notices', 'totalNotices', 'publishedNotices', 'todayNotices'));
    }

    public function staff(): View
    {
        // Get all notices for staff
        $notices = Notice::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(20);
        
        $totalNotices = Notice::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();
        $todayNotices = Notice::whereDate('published_at', today())->count();
        $thisMonthNotices = Notice::whereNotNull('published_at')
            ->whereMonth('published_at', now()->month)
            ->whereYear('published_at', now()->year)
            ->count();
        
        return view('hms.notices.staff', compact('notices', 'totalNotices', 'todayNotices', 'thisMonthNotices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:150',
            'body' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);
        Notice::create($data);
        return back()->with('status', __('Notice created'));
    }
}


