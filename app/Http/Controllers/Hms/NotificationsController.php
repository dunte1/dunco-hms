<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationsController extends Controller
{
    public function index(): View
    {
        // Get all notifications for the authenticated user
        $notifications = auth()->user()->notifications()->paginate(20);
        
        // Get counts
        $unreadCount = auth()->user()->unreadNotifications()->count();
        $totalCount = auth()->user()->notifications()->count();
        
        return view('hms.notifications.index', compact('notifications', 'unreadCount', 'totalCount'));
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return back()->with('success', 'Notification marked as read');
    }
    
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return back()->with('success', 'All notifications marked as read');
    }
    
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notification deleted');
    }
}
