<?php

namespace App\Services;

use App\Models\newpost_details;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AiBlogDraftGeneratorService
{
    public function __construct(
        private readonly RssTopicFetcherService $rssTopicFetcher,
        private readonly OpenAiBlogGeneratorService $openAiGenerator,
    ) {}

    /**
     * @return array<string, int>
     */
    public function run(?int $limit = null): array
    {
        $limit = max(1, (int) ($limit ?? $this->blogConfig('default_limit', 5)));

        $topics = $this->rssTopicFetcher->fetchTrendingTopics($limit);

        $result = [
            'fetched' => count($topics),
            'created' => 0,
            'duplicates' => 0,
            'failed' => 0,
        ];

        foreach ($topics as $topic) {
            try {
                if ($this->isDuplicate((string) ($topic['title'] ?? ''), (string) ($topic['source_url'] ?? ''))) {
                    $result['duplicates']++;
                    continue;
                }

                $generated = $this->openAiGenerator->generate($topic);
                if ($generated === null) {
                    $result['failed']++;
                    Log::channel((string) $this->blogConfig('log_channel', 'ai_blog'))
                        ->warning('Draft generation returned null AI output.', [
                            'topic_title' => (string) ($topic['title'] ?? ''),
                            'source_type' => (string) ($topic['source_type'] ?? 'rss'),
                            'source_url' => (string) ($topic['source_url'] ?? ''),
                        ]);
                    continue;
                }

                if ($this->isDuplicate((string) ($generated['title'] ?? ''), (string) ($topic['source_url'] ?? ''))) {
                    $result['duplicates']++;
                    continue;
                }

                $this->storeDraft($topic, $generated);
                $result['created']++;
            } catch (\Throwable $exception) {
                $result['failed']++;
                Log::warning('Failed generating blog draft for topic', [
                    'topic' => $topic['title'] ?? null,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return $result;
    }

    private function isDuplicate(string $title, string $sourceUrl): bool
    {
        $normalizedTitle = $this->normalizeTitle($title);
        $titleSlug = Str::slug($normalizedTitle);
        $exactLowerTitle = Str::lower(trim($title));
        $normalizedSource = rtrim(Str::lower(trim($sourceUrl)), '/');

        if ($exactLowerTitle === '' && $normalizedSource === '') {
            return false;
        }

        return newpost_details::query()
            ->where(function ($query) use ($exactLowerTitle, $titleSlug, $normalizedSource) {
                $hasCondition = false;

                if ($exactLowerTitle !== '') {
                    $query->whereRaw('LOWER(title) = ?', [$exactLowerTitle]);
                    $hasCondition = true;
                }

                if ($titleSlug !== '' && Schema::hasColumn('newpost_details', 'slug')) {
                    if ($hasCondition) {
                        $query->orWhereRaw('LOWER(slug) = ?', [Str::lower($titleSlug)]);
                    } else {
                        $query->whereRaw('LOWER(slug) = ?', [Str::lower($titleSlug)]);
                    }
                    $hasCondition = true;
                }

                if ($normalizedSource !== '') {
                    $sourceVariants = array_values(array_unique([
                        $normalizedSource,
                        rtrim($normalizedSource, '/') . '/',
                    ]));

                    if ($hasCondition) {
                        $query->orWhereIn('source_url', $sourceVariants);
                    } else {
                        $query->whereIn('source_url', $sourceVariants);
                    }
                }
            })
            ->exists();
    }

    /**
     * @param array<string, mixed> $topic
     * @param array<string, string> $generated
     */
    private function storeDraft(array $topic, array $generated): newpost_details
    {
        $title = trim($generated['title']);
        $content = trim($generated['content']);
        $metaDescription = trim($generated['meta_description']);
        $categorySlug = $this->openAiGenerator->resolveCategorySlug(
            trim($generated['category_slug']),
            $topic,
            $title . ' ' . $metaDescription
        );

        $toc = $this->extractTableOfContents($content);
        $wordCount = str_word_count(strip_tags($content));

        $data = [
            'name' => 'AI Editorial Assistant',
            'title' => $title,
            'slug' => $this->generateUniqueSlug($title),
            'meta_title' => Str::limit($title, 255, ''),
            'meta_description' => $metaDescription,
            'description' => $content,
            'category' => $categorySlug,
            'source_url' => trim((string) ($topic['source_url'] ?? '')) ?: null,
            'status' => 'draft',
            'is_published' => 0,
            'is_featured' => 0,
            'active' => 1,
            'file_path' => null,
            'table_of_contents' => empty($toc) ? null : $toc,
            'reading_time' => (string) max(1, (int) ceil($wordCount / 200)),
            'word_count' => $wordCount,
            'tags' => json_encode([$categorySlug, 'ai-generated']),
            'created_date' => now(),
        ];

        if (Schema::hasColumn('newpost_details', 'source_type')) {
            $data['source_type'] = trim((string) ($topic['source_type'] ?? 'rss'));
        }

        return newpost_details::create($data);
    }

    private function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $base = $base !== '' ? $base : 'ai-generated-post';

        $slug = $base;
        $counter = 1;

        while (newpost_details::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * @return array<int, array<string, int|string>>
     */
    private function extractTableOfContents(string $html): array
    {
        if (trim($html) === '') {
            return [];
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $html);

        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//h2 | //h3 | //h4');

        if ($nodes === false) {
            return [];
        }

        $toc = [];
        $index = 1;

        foreach ($nodes as $node) {
            $text = trim($node->textContent ?? '');
            if ($text === '') {
                continue;
            }

            $slug = Str::slug($text);

            $toc[] = [
                'id' => 'section-' . $index,
                'title' => $text,
                'level' => (int) str_replace('h', '', strtolower($node->nodeName)),
                'slug' => $slug !== '' ? $slug : 'section-' . $index,
                'order' => $index,
            ];

            $index++;
        }

        return $toc;
    }

    private function normalizeTitle(string $title): string
    {
        return (string) Str::of($title)
            ->lower()
            ->replaceMatches('/[^a-z0-9\s]/', ' ')
            ->squish();
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
