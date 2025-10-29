<?php

namespace App\Jobs\Marketing;

use App\Models\Marketing\MarketingPost;
use App\Services\Marketing\AiContentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateDailyContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $topic = '',
        public string $platform = 'facebook'
    ) {}

    public function handle(AiContentService $aiService): void
    {
        try {
            $prompt = $this->topic 
                ? "Create a health awareness post about: {$this->topic}"
                : $this->getDailyHealthTip();

            $content = $aiService->generateContent($prompt, 'post');
            
            if ($content) {
                $hashtags = $aiService->generateHashtags($content, $this->platform);
                $cta = $aiService->generateCta($content);

                MarketingPost::create([
                    'title' => 'Daily Health Tip - ' . now()->format('M d, Y'),
                    'content' => $content,
                    'type' => 'social',
                    'platform' => $this->platform,
                    'hashtags' => implode(' ', $hashtags),
                    'cta_text' => $cta,
                    'status' => 'draft',
                    'is_ai_generated' => true,
                    'ai_model' => config('services.openrouter.model'),
                    'ai_prompt' => $prompt,
                    'created_by' => 1, // System user
                ]);

                Log::info('Daily content generated successfully', ['platform' => $this->platform]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to generate daily content', [
                'error' => $e->getMessage(),
                'platform' => $this->platform
            ]);
            throw $e;
        }
    }

    protected function getDailyHealthTip(): string
    {
        $topics = [
            'importance of regular health checkups',
            'preventing seasonal flu',
            'maintaining a healthy diet',
            'importance of exercise',
            'mental health awareness',
            'vaccination benefits',
            'hypertension prevention',
            'diabetes management',
        ];

        return 'Create an engaging social media post about: ' . $topics[array_rand($topics)];
    }
}
