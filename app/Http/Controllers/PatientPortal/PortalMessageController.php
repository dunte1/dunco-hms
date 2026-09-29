<?php

namespace App\Http\Controllers\PatientPortal;

use App\Http\Controllers\Controller;
use App\Models\PortalMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PortalMessageController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'parent_id' => 'nullable|exists:portal_messages,id',
        ]);

        $message = PortalMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $data['receiver_id'],
            'subject' => $data['subject'] ?? null,
            'body' => $data['body'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'data' => $message,
            'message' => 'Message sent successfully'
        ], 201);
    }

    public function inbox(Request $request): JsonResponse
    {
        $userId = Auth::id();

        $messages = PortalMessage::with(['sender', 'receiver', 'replies'])
            ->where('receiver_id', $userId)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function markRead(PortalMessage $message): JsonResponse
    {
        $message->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read'
        ]);
    }
}
