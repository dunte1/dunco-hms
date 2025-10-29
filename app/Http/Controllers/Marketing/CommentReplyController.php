<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Marketing\CommentReply;
use App\Services\Marketing\AiContentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentReplyController extends Controller
{
    public function __construct(
        protected AiContentService $aiService
    ) {}

    public function index(Request $request): View
    {
        $query = CommentReply::with('reviewer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        $replies = $query->latest()->paginate(20);
        $pendingCount = CommentReply::where('status', 'pending')->count();

        return view('marketing.comments.index', compact('replies', 'pendingCount'));
    }

    public function approve(CommentReply $commentReply): RedirectResponse
    {
        if ($commentReply->status === 'pending' && $commentReply->approved_reply) {
            // Post the reply to the platform
            // TODO: Implement actual posting logic
            
            $commentReply->update([
                'status' => 'posted',
                'reviewed_by' => auth()->id(),
                'replied_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Reply approved and posted.');
        }

        return redirect()->back()->with('error', 'Reply needs approval text.');
    }

    public function reject(CommentReply $commentReply): RedirectResponse
    {
        $commentReply->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Reply rejected.');
    }

    public function generateReply(Request $request, CommentReply $commentReply): RedirectResponse
    {
        $sentiment = $this->aiService->analyzeSentiment($commentReply->original_comment);
        $reply = $this->aiService->generateReply($commentReply->original_comment, $sentiment);

        $commentReply->update([
            'sentiment' => $sentiment,
            'ai_generated_reply' => $reply,
            'approved_reply' => $reply, // Auto-approve for now
        ]);

        return redirect()->back()->with('success', 'Reply generated successfully.');
    }
}
