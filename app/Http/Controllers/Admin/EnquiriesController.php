<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\View\View;

class EnquiriesController extends Controller
{
    public function index(): View
    {
        $enquiries = Enquiry::latest()->paginate(20);
        
        $totalEnquiries = Enquiry::count();
        $todayEnquiries = Enquiry::whereDate('created_at', today())->count();
        $thisMonthEnquiries = Enquiry::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        return view('admin.enquiries.index', compact('enquiries', 'totalEnquiries', 'todayEnquiries', 'thisMonthEnquiries'));
    }

    public function feedback(): View
    {
        // For now, we'll show all enquiries as feedback/complaints
        // In a real application, you might have a separate table for feedback
        $enquiries = Enquiry::latest()->paginate(20);
        
        $totalFeedback = Enquiry::count();
        $todayFeedback = Enquiry::whereDate('created_at', today())->count();
        $thisMonthFeedback = Enquiry::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        return view('hms.enquiries.feedback', compact('enquiries', 'totalFeedback', 'todayFeedback', 'thisMonthFeedback'));
    }
}


