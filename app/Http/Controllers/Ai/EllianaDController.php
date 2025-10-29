<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Services\EllianaDAssistantService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EllianaDController extends Controller
{
    protected $assistant;
    
    public function __construct(EllianaDAssistantService $assistant)
    {
        $this->assistant = $assistant;
    }
    
    /**
     * Show Elliana D interface
     */
    public function index()
    {
        return view('hms.ai.elliana-d');
    }
    
    /**
     * Handle chat messages
     */
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);
        
        $userId = auth()->id();
        $message = $request->input('message');
        
        $response = $this->assistant->processMessage($message, $userId);
        
        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }
    
    /**
     * Get conversation history (if needed)
     */
    public function history(): JsonResponse
    {
        // Could implement conversation history storage later
        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }
}

