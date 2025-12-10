@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Discover expert insights, technology updates, travel guides, news breakdowns, and inspirational stories. Explore curated articles across multiple categories including Tech, Travel, Lifestyle, Creativity, and Productivity.">

    <meta property="og:title" content="Unik Muse Blog – Insights, Stories, Trends & Expert Opinions">
    <meta property="og:description"
        content="Discover expert insights, technology updates, travel guides, news breakdowns, and inspirational stories.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/blog-og-image.jpg') }}">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Unik Muse Blog",
        "description": "Your destination for meaningful content across technology, travel, digital culture, creativity, and productivity.",
        "url": "{{ url('/') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/blog') }}?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <title>Unik Muse Blog – Insights, Stories, Trends & Expert Opinions</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/readmore.css') }}">
    <link rel="stylesheet" href="{{ asset('css/blogpage.css') }}">

</head>

<body>
    @include('common.header')
    <section class="hero">
        <div class="container">
            <h1>Explore Ideas That Inspire and Inform</h1>
            <p>At Unik Muse, we curate stories, guides, tutorials, and insights across technology, travel, culture,
                creativity, and modern digital life. Stay updated and discover new perspectives every day.</p>
        </div>
    </section>

    <div class="container">
        <!-- Featured Posts -->
        <section class="section">
            <h2 class="section-title">Featured Articles</h2>
            <p class="section-subtitle">Handpicked stories that are currently trending, most loved, or carry meaningful
                insights.</p>
            <div class="featured-grid">
                @foreach ($featuredPosts as $post)
                    <article class="featured-card" itemscope itemtype="https://schema.org/BlogPosting">
                        <img src="{{ asset('storage/' .$post['thumbnail']['original']['webp']) }}" alt="{{ $post['title'] }}" class="featured-img"
                            itemprop="image">
                        <div class="featured-content">
                            <span class="featured-category">{{ $post['category'] }}</span>
                            <h3 class="featured-title" itemprop="headline">
                                {{ $post['title'] }}
                            </h3>
                            <p class="featured-excerpt" itemprop="description">
                                {{ Str::limit($post['excerpt'], 120) }}
                            </p>
                            <a href="#" class="read-more" itemprop="mainEntityOfPage">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <meta itemprop="datePublished"
                            content="{{ Carbon::parse($post['published_at'])->toIso8601String() }}">
                        <meta itemprop="dateModified"
                            content="{{ Carbon::parse($post['updated_at'])->toIso8601String() }}">
                        <meta itemprop="author" content="Unik Muse">
                        <meta itemprop="publisher" content="Unik Muse">
                    </article>
                @endforeach
            </div>
        </section>

        <!-- Category Filters -->
        <section class="section">
            <h2 class="section-title">Browse by Category</h2>
            <p class="section-subtitle">Explore posts based on your interests</p>

            <div class="category-filters">
                <button class="category-btn {{ !$category ? 'active' : '' }}"
                    onclick="window.location='{{ route('blog.index') }}'">
                    All Categories
                </button>
                @foreach ($categories as $catName => $count)
                    <button class="category-btn {{ $category == $catName ? 'active' : '' }}"
                        onclick="window.location='{{ route('blog.index', ['category' => $catName]) }}'">
                        {{ $catName }}
                    </button>
                @endforeach
            </div>
        </section>

        <div class="main-layout">
            <!-- Main Content -->
            <main>
                <section class="section">
                    <h2 class="section-title">Latest Posts</h2>
                    <p class="section-subtitle">Browse the newest articles across all categories</p>

                    <div class="blog-grid">
                        @foreach ($posts as $post)
                            <article class="blog-card" itemscope itemtype="https://schema.org/BlogPosting">                                
                                <img src="{{ asset('storage/' . $post['thumbnail']['original']['webp']) }}" alt="{{ $post['title'] }}"
                                    class="blog-img" itemprop="image">
                                <div class="blog-content">
                                    <div class="blog-meta">
                                        <span class="blog-category">{{ $post['category'] }}</span>
                                        <span>{{ $post['estimated_reading_time'] }} min read</span>
                                    </div>
                                    <h3 class="blog-title" itemprop="headline">
                                        {{ $post['title'] }}
                                    </h3>
                                    <p class="blog-excerpt" itemprop="description">
                                        {{ Str::limit($post['excerpt'], 150) }}
                                    </p>
                                    <div class="blog-meta">
                                        <span itemprop="datePublished">
                                            {{ Carbon::parse($post['published_at'])->format('M d, Y') }}
                                        </span>
                                        <a href="#" class="read-more">
                                            Read More <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                                <meta itemprop="dateModified"
                                    content="{{ Carbon::parse($post['updated_at'])->toIso8601String() }}">
                                <meta itemprop="author" content="Unik Muse">
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    {{-- <div class="pagination">
                        @if ($posts->onFirstPage())
                            <span class="disabled">◀ Previous</span>
                        @else
                            <a href="{{ $posts->previousPageUrl() }}">◀ Previous</a>
                        @endif

                        @foreach (range(1, $posts->lastPage()) as $page)
                            @if ($page == $posts->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $posts->url($page) }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($posts->hasMorePages())
                            <a href="{{ $posts->nextPageUrl() }}">Next ▶</a>
                        @else
                            <span class="disabled">Next ▶</span>
                        @endif
                    </div> --}}
                </section>
            </main>

            <aside class="sidebar">
                <!-- Categories -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Categories</h3>
                    <ul class="category-list">
                        @foreach ($categories as $catName => $count)
                            <li>
                                <a href="{{ route('blog.index', ['category' => $catName]) }}">
                                    {{ $catName }}
                                    <span class="category-count">{{ $count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Search -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Search Articles</h3>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search for articles...">
                    </div>
                </div>

                <!-- Popular Posts -->
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Most Popular</h3>
                    @foreach ($popularPosts as $popular)
                        <div class="popular-post">
                            <img src="{{ asset('storage/' . $popular['thumbnail']['original']['webp']) }}" alt="{{ $popular['title'] }}"
                                class="popular-img">
                            <div>
                                <h4 class="popular-title">
                                    {{ Str::limit($popular['title'], 50) }}
                                </h4>
                                <div class="popular-meta">
                                    {{ $popular['category'] }} • {{ $popular['estimated_reading_time'] }} min read
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Newsletter -->
                <div class="sidebar-section">
                    <div class="newsletter-box">
                        <h3 class="newsletter-title">Subscribe to Our Newsletter</h3>
                        <p class="newsletter-text">Get the latest insights delivered to your inbox</p>
                        <form id="newsletter-form">
                            <input type="email" class="newsletter-input" placeholder="Your email address" required>
                            <button type="submit" class="newsletter-btn">Subscribe</button>
                        </form>
                    </div>
                </div>

                <!-- AdSense Placeholder -->
                <div class="sidebar-section">
                    <div class="adsense-placeholder"
                        style="background: #f3f4f6; height: 250px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                        <span style="color: #9ca3af;">AdSense Advertisement</span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
    @include('common.footer')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newsletterForm = document.getElementById('newsletter-form');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const email = this.querySelector('input[type="email"]').value;

                    alert('Thank you for subscribing with: ' + email);
                    this.reset();
                });
            }

            const searchInput = document.querySelector('.search-box input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const query = this.value.trim();
                        if (query) {
                            window.location.href = '{{ route('blog.index') }}?search=' +
                                encodeURIComponent(query);
                        }
                    }
                });
            }

            const blogCards = document.querySelectorAll('.blog-card');
            blogCards.forEach(card => {
                const title = card.querySelector('.blog-title').textContent;
                const description = card.querySelector('.blog-excerpt').textContent;
                const datePublished = card.querySelector('meta[itemprop="datePublished"]')?.getAttribute(
                    'content');
                const dateModified = card.querySelector('meta[itemprop="dateModified"]')?.getAttribute(
                    'content');

                if (title && description) {
                    const schema = {
                        "@context": "https://schema.org",
                        "@type": "BlogPosting",
                        "headline": title.trim(),
                        "description": description.trim(),
                        "datePublished": datePublished,
                        "dateModified": dateModified,
                        "author": {
                            "@type": "Organization",
                            "name": "Unik Muse"
                        },
                        "publisher": {
                            "@type": "Organization",
                            "name": "Unik Muse",
                            "logo": {
                                "@type": "ImageObject",
                                "url": "{{ url('/') }}/logo.png"
                            }
                        }
                    };

                    const script = document.createElement('script');
                    script.type = 'application/ld+json';
                    script.textContent = JSON.stringify(schema);
                    document.head.appendChild(script);
                }
            });
        });
    </script>
</body>

</html>
