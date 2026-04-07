<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OpenAiBlogGeneratorService
{
    /**
     * @return array<int, string>
     */
    public function allowedCategorySlugs(): array
    {
        return config('blog_automation.allowed_category_slugs', []);
    }

    /**
     * @param array<string, mixed> $topic
     * @return array<string, string>|null
     */
    public function generate(array $topic): ?array
    {
        $apiKey = trim((string) config('services.openai.api_key'));
        if ($apiKey === '') {
            Log::warning('OpenAI API key missing. Skipping AI generation.');
            return null;
        }

        $allowed = $this->allowedCategorySlugs();

        $systemPrompt = "You are a professional SEO blog writer. Return only valid JSON. Do not wrap in markdown."
            . " JSON keys required: title, content, meta_description, category_slug."
            . " content must be original, 800-1200 words, engaging intro and conclusion, and valid HTML using <h2>, <p>, <ul> when useful."
            . " category_slug must be exactly one from: " . implode(', ', $allowed) . ".";

        $userPrompt = [
            'topic_title' => (string) ($topic['title'] ?? ''),
            'topic_description' => (string) ($topic['description'] ?? ''),
            'topic_hint' => (string) ($topic['topic_hint'] ?? 'technology'),
            'rules' => [
                'Do not copy source text. Write original content.',
                'Use practical headings and readable paragraphs.',
                'Meta description should be around 140-160 characters.',
                'category_slug must be the best semantic match from allowed values.',
            ],
        ];

        try {
            $response = Http::timeout(120)
                ->withToken($apiKey)
                ->acceptJson()
                ->post(rtrim((string) config('services.openai.base_url', 'https://api.openai.com/v1'), '/') . '/chat/completions', [
                    'model' => (string) config('services.openai.model', 'gpt-4o-mini'),
                    'temperature' => 0.7,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => json_encode($userPrompt, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)],
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning('OpenAI request failed', [
                    'status' => $response->status(),
                    'body' => Str::limit($response->body(), 500),
                ]);
                return null;
            }

            $payload = $response->json();
            $content = data_get($payload, 'choices.0.message.content');

            if (!is_string($content) || trim($content) === '') {
                Log::warning('OpenAI returned empty content', ['response' => $payload]);
                return null;
            }

            $decoded = $this->decodeJson($content);
            if ($decoded === null) {
                Log::warning('OpenAI returned invalid JSON payload', ['content' => Str::limit($content, 500)]);
                return null;
            }

            $normalizedCategory = $this->resolveCategorySlug(
                (string) ($decoded['category_slug'] ?? ''),
                $topic,
                trim(((string) ($decoded['title'] ?? '')) . ' ' . ((string) ($decoded['meta_description'] ?? '')))
            );

            $generated = [
                'title' => trim((string) ($decoded['title'] ?? '')),
                'content' => trim((string) ($decoded['content'] ?? '')),
                'meta_description' => trim((string) ($decoded['meta_description'] ?? '')),
                'category_slug' => $normalizedCategory,
            ];

            if ($generated['title'] === '' || $generated['content'] === '' || $generated['meta_description'] === '') {
                Log::warning('OpenAI payload missing required keys', ['payload' => $decoded]);
                return null;
            }

            return $generated;
        } catch (\Throwable $exception) {
            Log::warning('OpenAI generation exception', ['error' => $exception->getMessage()]);
            return null;
        }
    }

    /**
     * @param array<string, mixed> $topic
     */
    public function resolveCategorySlug(string $candidate, array $topic, string $text = ''): string
    {
        $allowed = $this->allowedCategorySlugs();
        $candidate = Str::lower(trim($candidate));

        if (in_array($candidate, $allowed, true)) {
            return $candidate;
        }

        $textToAnalyze = Str::lower(trim($text . ' ' . (($topic['title'] ?? '') . ' ' . ($topic['description'] ?? ''))));

        $keywordMap = [
            'travel' => ['travel', 'trip', 'tour', 'destination', 'vacation', 'hotel', 'flight', 'itinerary', 'backpacking'],
            'digital-trends' => ['gadget', 'wearable', 'smartphone', 'trend', 'future tech', 'social media', 'viral', 'digital trend'],
            'productivity' => ['productivity', 'habit', 'focus', 'time management', 'workflow', 'deep work', 'efficiency'],
            'news-updates' => ['breaking', 'latest', 'news', 'update', 'announced', 'report', 'headline'],
            'stories-experiences' => ['story', 'experience', 'journey', 'lessons learned', 'personal'],
            'creativity-inspiration' => ['creative', 'inspiration', 'art', 'motivation', 'idea'],
            'life-style' => ['lifestyle', 'wellness', 'health', 'daily life', 'routine', 'mindfulness'],
            'technology' => ['ai', 'technology', 'programming', 'software', 'developer', 'machine learning', 'cloud', 'cybersecurity'],
        ];

        foreach ($keywordMap as $slug => $keywords) {
            foreach ($keywords as $keyword) {
                if (Str::contains($textToAnalyze, $keyword)) {
                    return $slug;
                }
            }
        }

        return (($topic['topic_hint'] ?? 'technology') === 'travel') ? 'travel' : 'technology';
    }

    /**
     * @return array<string, mixed>|null
     */
    private function decodeJson(string $raw): ?array
    {
        $clean = trim($raw);
        $clean = preg_replace('/^```json\s*/i', '', $clean) ?? $clean;
        $clean = preg_replace('/```$/', '', $clean) ?? $clean;

        $decoded = json_decode($clean, true);
        return is_array($decoded) ? $decoded : null;
    }
}
