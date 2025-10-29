<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Services\Marketing\AiContentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AiContentController extends Controller
{
    public function __construct(
        protected AiContentService $aiService
    ) {}

    public function generateContent(Request $request): JsonResponse
    {
        $request->validate([
            'prompt' => 'required|string',
            'type' => 'required|in:post,blog,hashtags,cta',
            'context' => 'nullable|array',
        ]);

        $content = $this->aiService->generateContent(
            $request->prompt,
            $request->type,
            $request->context ?? []
        );

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate content. Please try again.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'content' => $content,
        ]);
    }

    public function generateHashtags(Request $request): JsonResponse
    {
        $request->validate([
            'content' => 'required|string',
            'platform' => 'nullable|in:facebook,instagram,twitter,linkedin,tiktok',
        ]);

        $hashtags = $this->aiService->generateHashtags(
            $request->content,
            $request->platform ?? 'instagram'
        );

        return response()->json([
            'success' => true,
            'hashtags' => $hashtags,
        ]);
    }

    public function generateCta(Request $request): JsonResponse
    {
        $request->validate([
            'context' => 'required|string',
        ]);

        $cta = $this->aiService->generateCta($request->context);

        return response()->json([
            'success' => true,
            'cta' => $cta,
        ]);
    }
}
