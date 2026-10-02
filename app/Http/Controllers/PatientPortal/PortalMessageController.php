<?php

namespace App\Http\Controllers\PatientPortal;

use App\Http\Controllers\Controller;
use App\Models\PortalMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PortalMessageController extends Controller
{
    private function portalAccountId()
    {
        return session('patient_portal_user');
    }

    public function send(Request $request): JsonResponse
    {
        $accountId = $this->portalAccountId();
        if (!$accountId) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $data = $request->validate([
            'receiver_id' => 'required|integer',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'parent_id' => 'nullable|integer',
        ]);

        if (!User::whereKey($data['receiver_id'])->exists()) {
            return response()->json(['success' => false, 'message' => 'Receiver not found'], 422);
        }

        if (!empty($data['parent_id'])) {
            $parent = PortalMessage::find($data['parent_id']);
            if (!$parent) {
                return response()->json(['success' => false, 'message' => 'Parent message not found'], 422);
            }
            $allowed = ((int) $parent->sender_id === (int) $accountId)
                || ((int) $parent->receiver_id === (int) $accountId);
            if (!$allowed) {
                return response()->json(['success' => false, 'message' => 'Not authorized for this thread'], 403);
            }
        }

        $message = PortalMessage::create([
            'sender_id' => $accountId,
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
        $accountId = $this->portalAccountId();
        if (!$accountId) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $messages = PortalMessage::with(['sender', 'receiver', 'replies'])
            ->where(function ($q) use ($accountId) {
                $q->where('receiver_id', $accountId)
                  ->orWhere('sender_id', $accountId);
            })
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
        $accountId = $this->portalAccountId();
        if (!$accountId) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        if ((int) $message->receiver_id !== (int) $accountId) {
            return response()->json(['success' => false, 'message' => 'Not authorized'], 403);
        }

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
