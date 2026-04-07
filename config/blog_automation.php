<?php

return [
    'default_limit' => (int) env('AI_BLOG_DEFAULT_LIMIT', 5),

    'feeds' => [
        [
            'topic' => 'technology',
            'url' => env('AI_BLOG_TECH_FEED', 'https://news.google.com/rss/search?q=technology&hl=en-US&gl=US&ceid=US:en'),
        ],
        [
            'topic' => 'travel',
            'url' => env('AI_BLOG_TRAVEL_FEED', 'https://news.google.com/rss/search?q=travel&hl=en-US&gl=US&ceid=US:en'),
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
