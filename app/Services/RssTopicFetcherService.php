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
    public function fetchTrendingTopics(int $limit = 5): array
    {
        $feeds = config('blog_automation.feeds', []);

        if (empty($feeds)) {
            return [];
        }

        $maxPerFeed = max(1, (int) ceil($limit / max(1, count($feeds))));
        $topics = [];

        foreach ($feeds as $feed) {
            $url = trim((string) ($feed['url'] ?? ''));
            $topic = trim((string) ($feed['topic'] ?? 'technology'));

            if ($url === '') {
                continue;
            }

            try {
                $response = Http::timeout(20)->accept('application/rss+xml, application/xml, text/xml')->get($url);

                if (!$response->successful()) {
                    Log::warning('RSS fetch failed', [
                        'topic' => $topic,
                        'url' => $url,
                        'status' => $response->status(),
                    ]);
                    continue;
                }

                $parsedItems = $this->parseFeedItems($response->body(), $topic);
                $topics = array_merge($topics, array_slice($parsedItems, 0, $maxPerFeed));
            } catch (\Throwable $exception) {
                Log::warning('RSS fetch exception', [
                    'topic' => $topic,
                    'url' => $url,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        $deduped = [];
        $seen = [];

        foreach ($topics as $topicItem) {
            $hash = md5(Str::lower(($topicItem['title'] ?? '') . '|' . ($topicItem['source_url'] ?? '')));
            if (isset($seen[$hash])) {
                continue;
            }

            $seen[$hash] = true;
            $deduped[] = $topicItem;
        }

        usort($deduped, function (array $a, array $b): int {
            return strtotime((string) ($b['published_at'] ?? 'now')) <=> strtotime((string) ($a['published_at'] ?? 'now'));
        });

        return array_slice($deduped, 0, $limit);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parseFeedItems(string $xml, string $topic): array
    {
        libxml_use_internal_errors(true);
        $feed = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($feed === false || !isset($feed->channel->item)) {
            return [];
        }

        $items = [];

        foreach ($feed->channel->item as $item) {
            $title = trim((string) ($item->title ?? ''));
            $description = trim(strip_tags((string) ($item->description ?? '')));
            $description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            if ($title === '') {
                continue;
            }

            $items[] = [
                'title' => $title,
                'description' => Str::limit($description, 300),
                'source_url' => trim((string) ($item->link ?? '')),
                'topic_hint' => $topic,
                'published_at' => trim((string) ($item->pubDate ?? now()->toDateTimeString())),
            ];
        }

        return $items;
    }
}
