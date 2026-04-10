<?php

return [
    'default_limit' => (int) env('AI_BLOG_DEFAULT_LIMIT', 5),
    'per_category_limit' => (int) env('AI_BLOG_PER_CATEGORY_LIMIT', 2),
    'min_description_length' => (int) env('AI_BLOG_MIN_DESCRIPTION_LENGTH', 50),
    'long_description_length' => (int) env('AI_BLOG_LONG_DESCRIPTION_LENGTH', 220),
    'fallback_category_slug' => (string) env('AI_BLOG_FALLBACK_CATEGORY', 'news-updates'),
    'schedule_time' => (string) env('AI_BLOG_SCHEDULE_TIME', '06:00'),
    'log_channel' => (string) env('AI_BLOG_LOG_CHANNEL', 'ai_blog'),
    'log_response_body_limit' => (int) env('AI_BLOG_LOG_RESPONSE_BODY_LIMIT', 1200),

    'allowed_keywords' => array_values(array_filter(array_map(
        static fn (string $keyword): string => strtolower(trim($keyword)),
        explode(',', (string) env('AI_BLOG_ALLOWED_KEYWORDS', 'ai,tech,travel,productivity,guide,tips,trends'))
    ))),

    'score_weights' => [
        'trends' => (int) env('AI_BLOG_SCORE_TRENDS', 3),
        'reddit' => (int) env('AI_BLOG_SCORE_REDDIT', 2),
        'rss' => (int) env('AI_BLOG_SCORE_RSS', 1),
    ],

    'scoring_bonus_keywords' => array_values(array_filter(array_map(
        static fn (string $keyword): string => strtolower(trim($keyword)),
        explode(',', (string) env('AI_BLOG_BONUS_KEYWORDS', 'ai,2026'))
    ))),
    'scoring_bonus_points' => (int) env('AI_BLOG_SCORE_BONUS_KEYWORDS', 2),
    'scoring_long_description_points' => (int) env('AI_BLOG_SCORE_LONG_DESCRIPTION', 1),

    'trends' => [
        'enabled' => env('AI_BLOG_TRENDS_ENABLED', true),
        'url' => (string) env(
            'AI_BLOG_TRENDS_URL',
            'https://trends.google.com/trends/trendingsearches/daily/rss?geo=IN'
        ),
        'category' => (string) env('AI_BLOG_TRENDS_CATEGORY', 'news-updates'),
    ],

    'reddit' => [
        'enabled' => env('AI_BLOG_REDDIT_ENABLED', true),
        'user_agent' => (string) env('AI_BLOG_REDDIT_USER_AGENT', 'myblog-bot/1.0 (+https://example.com)'),
        'subreddits' => array_values(array_filter(array_map(
            static fn (string $subreddit): string => strtolower(trim($subreddit)),
            explode(',', (string) env('AI_BLOG_REDDIT_SUBREDDITS', 'technology,travel,productivity'))
        ))),
        'limit_per_subreddit' => (int) env('AI_BLOG_REDDIT_LIMIT_PER_SUBREDDIT', 8),
        'category_map' => [
            'technology' => 'technology',
            'travel' => 'travel',
            'productivity' => 'productivity',
        ],
    ],

    'feeds' => [

        [
            'topic' => 'technology',
            'category' => 'technology',
            'url' => env('AI_BLOG_TECH_FEED'),
        ],

        [
            'topic' => 'travel',
            'category' => 'travel',
            'url' => env('AI_BLOG_TRAVEL_FEED'),
        ],

        [
            'topic' => 'productivity',
            'category' => 'productivity',
            'url' => 'https://news.google.com/rss/search?q=productivity&hl=en-US&gl=US&ceid=US:en',
        ],

        [
            'topic' => 'lifestyle',
            'category' => 'life-style',
            'url' => 'https://news.google.com/rss/search?q=lifestyle&hl=en-US&gl=US&ceid=US:en',
        ],

        [
            'topic' => 'digital trends',
            'category' => 'digital-trends',
            'url' => 'https://news.google.com/rss/search?q=digital+trends&hl=en-US&gl=US&ceid=US:en',
        ],

        [
            'topic' => 'news',
            'category' => 'news-updates',
            'url' => 'https://news.google.com/rss?hl=en-US&gl=US&ceid=US:en',
        ],

        [
            'topic' => 'inspiration',
            'category' => 'creativity-inspiration',
            'url' => 'https://news.google.com/rss/search?q=inspiration&hl=en-US&gl=US&ceid=US:en',
        ],

        [
            'topic' => 'stories',
            'category' => 'stories-experiences',
            'url' => 'https://news.google.com/rss/search?q=life+stories&hl=en-US&gl=US&ceid=US:en',
        ],
    ],

    'allowed_category_slugs' => [
        'technology',
        'travel',
        'life-style',
        'digital-trends',
        'productivity',
        'news-updates',
        'stories-experiences',
        'creativity-inspiration',
    ],
];
