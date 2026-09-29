<?php

namespace App\Http\Controllers\Telemedicine;

use App\Http\Controllers\Controller;
use App\Models\TeleParticipant;
use App\Models\TelemedicineSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeleParticipantController extends Controller
{
    public function store(Request $request, TelemedicineSession $session): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'role' => 'required|string|in:host,participant,observer',
        ]);

        $participant = TeleParticipant::create([
            'tele_session_id' => $session->id,
            'user_id' => $data['user_id'] ?? null,
            'role' => $data['role'],
            'status' => 'invited',
        ]);

        return response()->json([
            'success' => true,
            'data' => $participant,
            'message' => 'Participant added to session'
        ], 201);
    }
}
