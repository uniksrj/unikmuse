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
        $allowed = $this->blogConfig('allowed_category_slugs', []);
        if (!is_array($allowed) || empty($allowed)) {
            return ['news-updates'];
        }

        return array_values(array_map(
            static fn ($slug) => Str::lower(trim((string) $slug)),
            $allowed
        ));
    }

    /**
     * @param array<string, mixed> $topic
     * @return array<string, string>|null
     */
    public function generate(array $topic): ?array
    {
        $requestId = (string) Str::uuid();
        $logger = $this->aiLogger();
        $apiKey = trim((string) config('services.openai.api_key'));
        if ($apiKey === '') {
            $logger->warning('OpenAI API key missing. Skipping AI generation.', [
                'request_id' => $requestId,
                'stage' => 'draft_generation',
            ]);
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
            'source_type' => (string) ($topic['source_type'] ?? 'rss'),
            'category_slug_hint' => (string) ($topic['category_slug'] ?? ''),
            'rules' => [
                'Do not copy source text. Write original content.',
                'Use practical headings and readable paragraphs.',
                'Meta description should be around 140-160 characters.',
                'category_slug must be the best semantic match from allowed values.',
            ],
        ];

        try {
            $model = (string) config('services.openai.draft_model', config('services.openai.model', 'gpt-4o-mini'));
            $logger->info('Starting AI draft generation request.', [
                'request_id' => $requestId,
                'stage' => 'draft_generation',
                'model' => $model,
                'topic_title' => (string) ($topic['title'] ?? ''),
                'source_type' => (string) ($topic['source_type'] ?? 'rss'),
                'source_url' => (string) ($topic['source_url'] ?? ''),
            ]);

            $decoded = $this->requestJsonCompletion(
                $systemPrompt,
                json_encode($userPrompt, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                $model,
                0.7,
                120,
                [
                    'request_id' => $requestId,
                    'stage' => 'draft_generation',
                    'topic_title' => (string) ($topic['title'] ?? ''),
                ]
            );

            if ($decoded === null) {
                $logger->warning('OpenAI draft generation returned null payload.', [
                    'request_id' => $requestId,
                    'stage' => 'draft_generation',
                    'topic_title' => (string) ($topic['title'] ?? ''),
                ]);
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
                $logger->warning('OpenAI payload missing required keys for draft generation.', [
                    'request_id' => $requestId,
                    'stage' => 'draft_generation',
                    'topic_title' => (string) ($topic['title'] ?? ''),
                    'payload_keys' => array_keys($decoded),
                ]);
                return null;
            }

            $logger->info('AI draft generation completed successfully.', [
                'request_id' => $requestId,
                'stage' => 'draft_generation',
                'generated_title' => $generated['title'],
                'category_slug' => $generated['category_slug'],
            ]);

            return $generated;
        } catch (\Throwable $exception) {
            $logger->error('OpenAI draft generation exception.', [
                'request_id' => $requestId,
                'stage' => 'draft_generation',
                'error' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
            return null;
        }
    }

    /**
     * Final publishing step: rewrite draft into high-quality SEO article.
     *
     * @return array<string, string>|null
     */
    public function optimizeForPublishing(string $title, string $draftContent, string $category): ?array
    {
        $requestId = (string) Str::uuid();
        $logger = $this->aiLogger();
        $apiKey = trim((string) config('services.openai.api_key'));
        if ($apiKey === '') {
            $logger->warning('OpenAI API key missing. Skipping final publish optimization.', [
                'request_id' => $requestId,
                'stage' => 'publish_optimization',
                'title' => $title,
            ]);
            return null;
        }

        $systemPrompt = 'You are an expert SEO content writer and blog strategist.';

        $userPrompt = "This is the FINAL publishing step. Transform the given draft into a high-quality, SEO-optimized article suitable for ranking on Google.\n\n"
            . "========================================\n"
            . "INPUT\n"
            . "========================================\n"
            . "Blog Title: {$title}\n"
            . "Draft Content: {$draftContent}\n"
            . "Category: {$category}\n\n"
            . "========================================\n"
            . "OBJECTIVE\n"
            . "========================================\n"
            . "Rewrite and enhance the blog to:\n"
            . "- Rank on search engines\n"
            . "- Engage human readers\n"
            . "- Provide real value (not generic AI content)\n\n"
            . "========================================\n"
            . "CONTENT REQUIREMENTS\n"
            . "========================================\n"
            . "- Length: 1000-1500 words\n"
            . "- Use clean HTML formatting: <h1>, <h2>, <h3>, <p>, <ul>, <ol>\n"
            . "- Strong introduction in first 2-3 lines\n"
            . "- Keyword-rich headings\n"
            . "- Short readable paragraphs\n"
            . "- Bullet points where useful\n"
            . "- Real-world examples or practical insights\n"
            . "- Clear and satisfying conclusion\n\n"
            . "========================================\n"
            . "SEO OPTIMIZATION\n"
            . "========================================\n"
            . "- Naturally include primary keyword from title\n"
            . "- Add related keywords (LSI)\n"
            . "- Avoid keyword stuffing\n"
            . "- Optimize readability\n"
            . "- Optimize for featured snippets and People Also Ask\n\n"
            . "========================================\n"
            . "FAQ SECTION (MANDATORY)\n"
            . "========================================\n"
            . "At the end add <h2>Frequently Asked Questions</h2> and include 3-5 relevant FAQs.\n\n"
            . "========================================\n"
            . "META DESCRIPTION\n"
            . "========================================\n"
            . "Generate a compelling 150-160 character SEO-friendly meta description.\n\n"
            . "========================================\n"
            . "OUTPUT FORMAT (STRICT JSON)\n"
            . "========================================\n"
            . "Return ONLY valid JSON with keys: title, content, meta_description.\n"
            . "Do NOT output markdown.";

        $model = (string) config(
            'services.openai.publish_model',
            config('services.openai.draft_model', config('services.openai.model', 'gpt-4o-mini'))
        );

        $logger->info('Starting AI publish optimization request.', [
            'request_id' => $requestId,
            'stage' => 'publish_optimization',
            'model' => $model,
            'title' => $title,
            'category' => $category,
        ]);

        $decoded = $this->requestJsonCompletion(
            $systemPrompt,
            $userPrompt,
            $model,
            0.65,
            180,
            [
                'request_id' => $requestId,
                'stage' => 'publish_optimization',
                'title' => $title,
            ]
        );

        if ($decoded === null) {
            $logger->warning('OpenAI publish optimization returned null payload.', [
                'request_id' => $requestId,
                'stage' => 'publish_optimization',
                'title' => $title,
            ]);
            return null;
        }

        $optimized = [
            'title' => trim((string) ($decoded['title'] ?? $title)),
            'content' => trim((string) ($decoded['content'] ?? '')),
            'meta_description' => trim((string) ($decoded['meta_description'] ?? '')),
        ];

        if ($optimized['content'] === '') {
            $logger->warning('Final publish optimization returned empty content.', [
                'request_id' => $requestId,
                'stage' => 'publish_optimization',
                'title' => $title,
            ]);
            return null;
        }

        if (!Str::contains(Str::lower($optimized['content']), '<h1')) {
            $optimized['content'] = '<h1>' . e($optimized['title']) . '</h1>' . $optimized['content'];
        }

        $optimized['content'] = $this->ensureFaqSection($optimized['content'], $optimized['title']);

        if ($optimized['meta_description'] === '') {
            $optimized['meta_description'] = Str::limit(trim(strip_tags($optimized['content'])), 160, '');
        }

        if (Str::length($optimized['meta_description']) > 160) {
            $optimized['meta_description'] = trim(Str::limit($optimized['meta_description'], 160, ''));
        }

        $logger->info('AI publish optimization completed successfully.', [
            'request_id' => $requestId,
            'stage' => 'publish_optimization',
            'optimized_title' => $optimized['title'],
        ]);

        return $optimized;
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

        $fallback = Str::lower(trim((string) $this->blogConfig('fallback_category_slug', 'news-updates')));
        if (in_array($fallback, $allowed, true)) {
            return $fallback;
        }

        return $allowed[0] ?? 'news-updates';
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

    /**
     * @return array<string, mixed>|null
     */
    private function requestJsonCompletion(
        string $systemPrompt,
        string $userPrompt,
        string $model,
        float $temperature,
        int $timeoutSeconds,
        array $context = []
    ): ?array {
        $logger = $this->aiLogger();
        $logContext = array_merge([
            'model' => $model,
            'temperature' => $temperature,
            'timeout_seconds' => $timeoutSeconds,
        ], $context);

        try {
            $logger->info('Sending OpenAI API request.', $logContext);

            $response = Http::timeout($timeoutSeconds)
                ->withToken((string) config('services.openai.api_key'))
                ->acceptJson()
                ->post(rtrim((string) config('services.openai.base_url', 'https://api.openai.com/v1'), '/') . '/chat/completions', [
                    'model' => $model,
                    'temperature' => $temperature,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                ]);

            if (!$response->successful()) {
                $logger->warning('OpenAI request failed.', array_merge($logContext, [
                    'status' => $response->status(),
                    'body' => Str::limit(
                        $response->body(),
                        (int) $this->blogConfig('log_response_body_limit', 1200)
                    ),
                ]));
                return null;
            }

            $payload = $response->json();
            $content = data_get($payload, 'choices.0.message.content');

            if (!is_string($content) || trim($content) === '') {
                $logger->warning('OpenAI returned empty content.', array_merge($logContext, [
                    'response' => $payload,
                ]));
                return null;
            }

            $decoded = $this->decodeJson($content);
            if ($decoded === null) {
                $logger->warning('OpenAI returned invalid JSON payload.', array_merge($logContext, [
                    'content' => Str::limit(
                        $content,
                        (int) $this->blogConfig('log_response_body_limit', 1200)
                    ),
                ]));
                return null;
            }

            $logger->info('OpenAI API request succeeded.', array_merge($logContext, [
                'finish_reason' => data_get($payload, 'choices.0.finish_reason'),
                'usage' => data_get($payload, 'usage'),
            ]));

            return $decoded;
        } catch (\Throwable $exception) {
            $logger->error('OpenAI request exception.', array_merge($logContext, [
                'error' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]));
            return null;
        }
    }

    private function ensureFaqSection(string $content, string $title): string
    {
        if (Str::contains(Str::lower($content), 'frequently asked questions')) {
            return $content;
        }

        $topic = trim($title) !== '' ? $title : 'this topic';

        return rtrim($content) . "\n"
            . '<h2>Frequently Asked Questions</h2>'
            . '<h3>What is the main benefit of ' . e($topic) . '?</h3>'
            . '<p>The main benefit is practical clarity: readers can make better decisions faster by understanding what works, why it works, and how to apply it correctly.</p>'
            . '<h3>How can beginners apply these ideas effectively?</h3>'
            . '<p>Start with one actionable step, measure the result, and iterate weekly. This creates momentum and avoids overwhelm.</p>'
            . '<h3>What common mistake should readers avoid?</h3>'
            . '<p>Avoid copying tactics without context. Choose strategies that fit your goals, resources, and audience.</p>';
    }

    private function aiLogger()
    {
        return Log::channel((string) $this->blogConfig('log_channel', 'ai_blog'));
    }

    private function blogConfig(string $key, mixed $default = null): mixed
    {
        $blogValue = config("blog.{$key}");
        if ($blogValue !== null) {
            return $blogValue;
        }

        return config("blog_automation.{$key}", $default);
    }
}
