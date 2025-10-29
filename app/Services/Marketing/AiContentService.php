<?php

namespace App\Services\Marketing;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiContentService
{
    protected string $apiKey;
    protected string $apiUrl;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.api_key') ?? config('services.huggingface.api_key') ?? '';
        $this->apiUrl = config('services.openrouter.url', 'https://openrouter.ai/api/v1/chat/completions');
        $this->model = config('services.openrouter.model', 'meta-llama/llama-3.1-8b-instruct:free');
    }

    /**
     * Generate marketing content using AI
     */
    public function generateContent(string $prompt, string $type = 'post', array $context = []): ?string
    {
        try {
            $fullPrompt = $this->buildPrompt($prompt, $type, $context);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $this->getSystemPrompt($type)],
                    ['role' => 'user', 'content' => $fullPrompt]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::error('AI Content Generation Failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('AI Content Service Error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Generate hashtags for social media posts
     */
    public function generateHashtags(string $content, string $platform = 'instagram'): array
    {
        $hashtags = [];
        $maxHashtags = match($platform) {
            'twitter' => 5,
            'facebook' => 10,
            'linkedin' => 5,
            default => 30, // Instagram/TikTok
        };

        // Extract keywords and generate hashtags
        $prompt = "Generate $maxHashtags relevant hashtags for this content: $content";
        $result = $this->generateContent($prompt, 'hashtags');

        if ($result) {
            preg_match_all('/#?\w+/', $result, $matches);
            $hashtags = array_slice($matches[0], 0, $maxHashtags);
        }

        return $hashtags;
    }

    /**
     * Generate CTA text
     */
    public function generateCta(string $context): ?string
    {
        $prompt = "Generate a compelling call-to-action for: $context";
        return $this->generateContent($prompt, 'cta');
    }

    /**
     * Analyze sentiment of a comment
     */
    public function analyzeSentiment(string $comment): string
    {
        $prompt = "Analyze the sentiment of this comment as 'positive', 'neutral', or 'negative': $comment";
        $result = $this->generateContent($prompt, 'sentiment');
        
        if ($result) {
            $lower = strtolower($result);
            if (str_contains($lower, 'positive')) return 'positive';
            if (str_contains($lower, 'negative')) return 'negative';
        }
        
        return 'neutral';
    }

    /**
     * Generate reply to a comment
     */
    public function generateReply(string $comment, string $sentiment = 'neutral'): ?string
    {
        $context = "A hospital's social media comment: '$comment' (sentiment: $sentiment). Generate a professional, compassionate reply.";
        return $this->generateContent($context, 'reply');
    }

    protected function buildPrompt(string $prompt, string $type, array $context = []): string
    {
        $basePrompt = $prompt;

        if (!empty($context)) {
            $contextStr = implode(', ', $context);
            $basePrompt .= "\n\nContext: $contextStr";
        }

        return $basePrompt;
    }

    protected function getSystemPrompt(string $type): string
    {
        return match($type) {
            'post' => 'You are a healthcare marketing content creator. Create engaging, informative, and compassionate social media posts for a hospital.',
            'blog' => 'You are a healthcare blogger. Write informative, well-structured blog posts about health topics.',
            'hashtags' => 'You are a social media expert. Generate relevant, trending hashtags.',
            'cta' => 'You are a marketing copywriter. Create compelling calls-to-action.',
            'sentiment' => 'You are a sentiment analysis tool. Analyze text sentiment.',
            'reply' => 'You are a hospital social media manager. Write professional, empathetic replies to comments.',
            default => 'You are a helpful assistant.',
        };
    }
}

