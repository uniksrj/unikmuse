<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RssTopicFetcherService
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchTrendingTopics(?int $limit = null): array
    {
        $limit = max(1, (int) ($limit ?? $this->blogConfig('default_limit', 5)));
        $allTopics = [];

        $allTopics = array_merge($allTopics, $this->fetchRssTopics($limit));
        $allTopics = array_merge($allTopics, $this->fetchGoogleTrendsTopics($limit));
        $allTopics = array_merge($allTopics, $this->fetchRedditTopics($limit));

        if (empty($allTopics)) {
            return [];
        }

        return $this->curateTopics($allTopics, $limit);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchRssTopics(int $limit): array
    {
        $feeds = $this->blogConfig('feeds', []);
        if (!is_array($feeds) || empty($feeds)) {
            return [];
        }

        $maxPerFeed = max(1, (int) ceil($limit / max(1, count($feeds))));
        $topics = [];

        foreach ($feeds as $feed) {
            $url = trim((string) ($feed['url'] ?? ''));
            $topic = trim((string) ($feed['topic'] ?? 'technology'));
            $category = trim((string) ($feed['category'] ?? 'technology'));

            if ($url === '') {
                continue;
            }

            $parsedItems = $this->fetchRssFromUrl($url, $topic, $category, 'rss');
            $topics = array_merge($topics, array_slice($parsedItems, 0, $maxPerFeed));
        }

        return $topics;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchGoogleTrendsTopics(int $limit): array
    {
        if (!$this->blogConfig('trends.enabled', true)) {
            return [];
        }

        $url = trim((string) $this->blogConfig('trends.url', ''));
        if ($url === '') {
            return [];
        }

        $category = trim((string) $this->blogConfig('trends.category', 'news-updates'));
        return array_slice($this->fetchRssFromUrl($url, 'trends', $category, 'trends'), 0, max(1, $limit));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchRedditTopics(int $limit): array
    {
        if (!$this->blogConfig('reddit.enabled', true)) {
            return [];
        }

        $subreddits = $this->blogConfig('reddit.subreddits', ['technology', 'travel', 'productivity']);
        if (!is_array($subreddits) || empty($subreddits)) {
            return [];
        }

        $topics = [];
        $userAgent = trim((string) $this->blogConfig('reddit.user_agent', 'myblog-bot/1.0 (+https://example.com)'));
        $configLimit = max(1, (int) $this->blogConfig('reddit.limit_per_subreddit', 8));
        $perSubredditLimit = min($configLimit, max(1, (int) ceil($limit / max(1, count($subreddits)))));

        foreach ($subreddits as $subreddit) {
            $subreddit = strtolower(trim((string) $subreddit));
            if ($subreddit === '') {
                continue;
            }

            $endpoint = "https://www.reddit.com/r/{$subreddit}/top.json";

            try {
                $response = Http::timeout(20)
                    ->acceptJson()
                    ->withHeaders([
                        'User-Agent' => $userAgent,
                    ])
                    ->get($endpoint, [
                        'limit' => $perSubredditLimit,
                        't' => 'day',
                    ]);

                if (!$response->successful()) {
                    Log::warning('Reddit fetch failed', [
                        'subreddit' => $subreddit,
                        'status' => $response->status(),
                    ]);
                    continue;
                }

                $children = data_get($response->json(), 'data.children', []);
                if (!is_array($children)) {
                    continue;
                }

                foreach ($children as $child) {
                    $post = data_get($child, 'data', []);
                    if (!is_array($post)) {
                        continue;
                    }

                    $title = trim((string) data_get($post, 'title', ''));
                    if ($title === '') {
                        continue;
                    }

                    $selfText = trim(strip_tags((string) data_get($post, 'selftext', '')));
                    $description = html_entity_decode($selfText, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                    if ($description === '') {
                        $description = "Top Reddit discussion from r/{$subreddit}: {$title}. Community members are sharing practical insights and current perspectives.";
                    }

                    $permalink = trim((string) data_get($post, 'permalink', ''));
                    $directUrl = trim((string) data_get($post, 'url_overridden_by_dest', data_get($post, 'url', '')));
                    $sourceUrl = $permalink !== '' ? 'https://www.reddit.com' . $permalink : $directUrl;

                    $topics[] = [
                        'title' => $title,
                        'description' => Str::limit($description, 500),
                        'source_url' => $sourceUrl,
                        'source_type' => 'reddit',
                        'category_slug' => $this->resolveCategorySlug((string) $this->blogConfig("reddit.category_map.{$subreddit}", $subreddit), $title . ' ' . $description),
                        'topic_hint' => $subreddit,
                        'published_at' => (int) data_get($post, 'created_utc', 0) > 0
                            ? date('Y-m-d H:i:s', (int) data_get($post, 'created_utc'))
                            : now()->toDateTimeString(),
                    ];
                }
            } catch (\Throwable $exception) {
                Log::warning('Reddit fetch exception', [
                    'subreddit' => $subreddit,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return $topics;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchRssFromUrl(
        string $url,
        string $topicHint,
        string $categorySlug,
        string $sourceType
    ): array {
        try {
            $response = Http::timeout(20)
                ->accept('application/rss+xml, application/xml, text/xml')
                ->get($url);

            if (!$response->successful()) {
                Log::warning('RSS/Trends fetch failed', [
                    'source_type' => $sourceType,
                    'url' => $url,
                    'status' => $response->status(),
                ]);
                return [];
            }

            return $this->parseFeedItems($response->body(), $topicHint, $categorySlug, $sourceType);
        } catch (\Throwable $exception) {
            Log::warning('RSS/Trends fetch exception', [
                'source_type' => $sourceType,
                'url' => $url,
                'error' => $exception->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseFeedItems(string $xml, string $topicHint, string $categorySlug, string $sourceType): array
    {
        libxml_use_internal_errors(true);
        $feed = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($feed === false) {
            return [];
        }

        $nodes = [];
        if (isset($feed->channel->item)) {
            $nodes = $feed->channel->item;
        } elseif (isset($feed->entry)) {
            $nodes = $feed->entry;
        }

        if (empty($nodes)) {
            return [];
        }

        $items = [];

        foreach ($nodes as $item) {
            $title = trim((string) ($item->title ?? ''));
            $description = trim(strip_tags((string) ($item->description ?? '')));
            $description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            if ($description === '' && isset($item->summary)) {
                $description = trim(strip_tags((string) $item->summary));
            }

            if ($description === '' && isset($item->content)) {
                $description = trim(strip_tags((string) $item->content));
            }

            if ($description === '') {
                $description = "Latest {$sourceType} topic: {$title}.";
            }

            if ($title === '') {
                continue;
            }

            $link = trim((string) ($item->link ?? ''));
            if ($link === '' && isset($item->link)) {
                $attributes = $item->link->attributes();
                $link = trim((string) ($attributes['href'] ?? ''));
            }

            $items[] = [
                'title' => $title,
                'description' => Str::limit($description, 500),
                'source_url' => $link,
                'source_type' => $sourceType,
                'category_slug' => $this->resolveCategorySlug($categorySlug, $title . ' ' . $description),
                'topic_hint' => $topicHint,
                'published_at' => trim((string) ($item->pubDate ?? now()->toDateTimeString())),
            ];
        }

        return $items;
    }

    /**
     * @param array<int, array<string, mixed>> $topics
     * @return array<int, array<string, mixed>>
     */
    private function curateTopics(array $topics, int $limit): array
    {
        $seenUrls = [];
        $seenTitles = [];
        $filtered = [];
        $minDescriptionLength = max(1, (int) $this->blogConfig('min_description_length', 50));

        foreach ($topics as $topic) {
            $title = trim((string) ($topic['title'] ?? ''));
            $description = trim((string) ($topic['description'] ?? ''));
            $sourceUrl = trim((string) ($topic['source_url'] ?? ''));
            $sourceUrlKey = $this->normalizeSourceUrl($sourceUrl);

            if ($title === '' || mb_strlen($description) < $minDescriptionLength) {
                continue;
            }

            $keywordText = Str::lower($title . ' ' . $description);
            if (!$this->containsAllowedKeyword($keywordText)) {
                continue;
            }

            $normalizedTitle = $this->normalizeTitle($title);

            if ($sourceUrlKey !== '' && isset($seenUrls[$sourceUrlKey])) {
                continue;
            }

            if ($normalizedTitle !== '' && isset($seenTitles[$normalizedTitle])) {
                continue;
            }

            if ($sourceUrlKey !== '') {
                $seenUrls[$sourceUrlKey] = true;
            }
            if ($normalizedTitle !== '') {
                $seenTitles[$normalizedTitle] = true;
            }

            $categorySlug = $this->resolveCategorySlug(
                (string) ($topic['category_slug'] ?? ''),
                $keywordText
            );

            $topic['category_slug'] = $categorySlug;
            $topic['score'] = $this->calculateScore($topic);
            $filtered[] = $topic;
        }

        usort($filtered, function (array $a, array $b): int {
            $scoreCompare = ((int) ($b['score'] ?? 0)) <=> ((int) ($a['score'] ?? 0));
            if ($scoreCompare !== 0) {
                return $scoreCompare;
            }

            return strtotime((string) ($b['published_at'] ?? 'now')) <=> strtotime((string) ($a['published_at'] ?? 'now'));
        });

        $selected = [];
        $categoryCounts = [];
        $perCategoryLimit = max(1, (int) $this->blogConfig('per_category_limit', 2));

        foreach ($filtered as $topic) {
            $categorySlug = (string) ($topic['category_slug'] ?? $this->fallbackCategorySlug());
            $count = (int) ($categoryCounts[$categorySlug] ?? 0);

            if ($count >= $perCategoryLimit) {
                continue;
            }

            $selected[] = $topic;
            $categoryCounts[$categorySlug] = $count + 1;

            if (count($selected) >= $limit) {
                break;
            }
        }

        return $selected;
    }

    private function containsAllowedKeyword(string $text): bool
    {
        $keywords = $this->blogConfig('allowed_keywords', []);
        if (!is_array($keywords) || empty($keywords)) {
            return true;
        }

        foreach ($keywords as $keyword) {
            $keyword = strtolower(trim((string) $keyword));
            if ($keyword === '') {
                continue;
            }

            if (Str::contains($text, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed> $topic
     */
    private function calculateScore(array $topic): int
    {
        $sourceType = strtolower(trim((string) ($topic['source_type'] ?? 'rss')));
        $scoreWeights = $this->blogConfig('score_weights', []);
        $score = is_array($scoreWeights) ? (int) ($scoreWeights[$sourceType] ?? 0) : 0;

        $text = Str::lower(trim((string) ($topic['title'] ?? '') . ' ' . (string) ($topic['description'] ?? '')));
        $bonusKeywords = $this->blogConfig('scoring_bonus_keywords', ['ai', '2026']);
        $bonusPoints = (int) $this->blogConfig('scoring_bonus_points', 2);

        if (is_array($bonusKeywords)) {
            foreach ($bonusKeywords as $keyword) {
                $keyword = strtolower(trim((string) $keyword));
                if ($keyword === '') {
                    continue;
                }

                if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/i', $text) === 1) {
                    $score += $bonusPoints;
                    break;
                }
            }
        }

        $longDescriptionLength = max(1, (int) $this->blogConfig('long_description_length', 220));
        if (mb_strlen((string) ($topic['description'] ?? '')) >= $longDescriptionLength) {
            $score += (int) $this->blogConfig('scoring_long_description_points', 1);
        }

        return $score;
    }

    private function resolveCategorySlug(string $candidate, string $text = ''): string
    {
        $allowed = $this->allowedCategorySlugs();
        $candidate = strtolower(trim($candidate));

        if ($candidate !== '' && in_array($candidate, $allowed, true)) {
            return $candidate;
        }

        $keywordMap = [
            'travel' => ['travel', 'trip', 'tour', 'destination', 'vacation', 'flight'],
            'productivity' => ['productivity', 'habit', 'focus', 'workflow', 'time management'],
            'digital-trends' => ['trend', 'viral', 'digital', 'social media', 'future'],
            'news-updates' => ['news', 'update', 'headline', 'breaking'],
            'stories-experiences' => ['story', 'experience', 'journey', 'lessons'],
            'creativity-inspiration' => ['creative', 'inspiration', 'motivation', 'idea'],
            'life-style' => ['lifestyle', 'wellness', 'health', 'routine'],
            'technology' => ['ai', 'technology', 'software', 'developer', 'machine learning', 'cybersecurity'],
        ];

        $text = strtolower(trim($text));
        foreach ($keywordMap as $slug => $keywords) {
            foreach ($keywords as $keyword) {
                if (Str::contains($text, strtolower($keyword)) && in_array($slug, $allowed, true)) {
                    return $slug;
                }
            }
        }

        return $this->fallbackCategorySlug();
    }

    private function fallbackCategorySlug(): string
    {
        $fallback = strtolower(trim((string) $this->blogConfig('fallback_category_slug', 'news-updates')));
        $allowed = $this->allowedCategorySlugs();

        if (in_array($fallback, $allowed, true)) {
            return $fallback;
        }

        return $allowed[0] ?? 'news-updates';
    }

    /**
     * @return array<int, string>
     */
    private function allowedCategorySlugs(): array
    {
        $allowed = $this->blogConfig('allowed_category_slugs', []);
        if (!is_array($allowed) || empty($allowed)) {
            return ['news-updates'];
        }

        return array_values(array_map(
            static fn ($slug) => strtolower(trim((string) $slug)),
            $allowed
        ));
    }

    private function normalizeTitle(string $title): string
    {
        return (string) Str::of($title)
            ->lower()
            ->replaceMatches('/[^a-z0-9\s]/', ' ')
            ->squish();
    }

    private function normalizeSourceUrl(string $url): string
    {
        return rtrim(Str::lower(trim($url)), '/');
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
