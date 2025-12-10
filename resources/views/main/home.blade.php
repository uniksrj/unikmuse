<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $meta_title ?? 'Unik Muse - Thoughts That Inspire' }}</title>

    <meta name="description"
        content="{{ $meta_description ?? 'Explore inspiring thoughts, blogs, guides, and stories on Unik Muse.' }}">

    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="{{ $meta_title ?? 'Unik Muse - Thoughts That Inspire' }}">
    <meta property="og:description"
        content="{{ $meta_description ?? 'Explore inspiring thoughts, blogs, guides, and stories on Unik Muse.' }}">
    <meta property="og:image" content="{{ asset('assets/snow.jpg') }}">
    <meta property="og:type" content="website">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite('resources/js/blog.js')
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
</head>

<body>
    @include('common.header')

    <div class="container">
        <div class="carousel-container">
            <div class="carousel-slide active">
                <img src="{{ asset('assets/snow.jpg') }}" alt="Snowy Mountain Landscape" loading="lazy">
                <div class="carousel-caption">
                    <h3>White Snow</h3>
                    <p>I travel not to go anywhere, but to go. I travel for travel's sake. The great affair is to move.
                    </p>
                </div>
            </div>
        </div>

        <section class="featured-section" aria-labelledby="featured-heading">
            <div class="section-header">
                <h2 id="featured-heading" class="section-title">
                    Featured Pages
                </h2>
                <p class="section-subtitle">
                    Handpicked collections of our best and most useful content.
                </p>
            </div>

            <div class="ads-block" style="margin: 20px 0; text-align:center;">
                <p style="font-size: 0.85rem; color:#777;">Advertisement</p>
            </div>

            <div class="featured-grid">

                <article class="featured-card">
                    <div class="featured-icon" aria-hidden="true">
                        <i class="fas fa-compass"></i>
                    </div>

                    <h3 class="featured-title">Travel Guides</h3>
                    <p class="featured-description">
                        Expert travel tips, destination guides, itineraries, and cultural insights
                        to help you plan your next adventure.
                    </p>

                    <a href="/guides" class="featured-link"
                        title="Explore detailed travel guides, tips, and destinations">
                        Explore Guides <i class="fas fa-arrow-right"></i>
                    </a>
                </article>

                <article class="featured-card">
                    <div class="featured-icon" aria-hidden="true">
                        <i class="fas fa-lightbulb"></i>
                    </div>

                    <h3 class="featured-title">Inspiration</h3>
                    <p class="featured-description">
                        Thought-provoking articles, stories, and ideas designed to spark creativity
                        and motivate personal growth.
                    </p>

                    <a href="/inspiration" class="featured-link" title="Read inspiring articles and creative ideas">
                        Get Inspired <i class="fas fa-arrow-right"></i>
                    </a>
                </article>

                <article class="featured-card">
                    <div class="featured-icon" aria-hidden="true">
                        <i class="fas fa-camera"></i>
                    </div>

                    <h3 class="featured-title">Photo Stories</h3>
                    <p class="featured-description">
                        Visual storytelling through stunning photography paired with deep,
                        narrative-driven experiences.
                    </p>

                    <a href="/photo-stories" class="featured-link" title="View photo stories and visual narratives">
                        View Stories <i class="fas fa-arrow-right"></i>
                    </a>
                </article>

                <article class="featured-card">
                    <div class="featured-icon" aria-hidden="true">
                        <i class="fas fa-book"></i>
                    </div>

                    <h3 class="featured-title">Collections</h3>
                    <p class="featured-description">
                        Curated collections of our most popular, evergreen, and timeless content
                        organized for easy reading.
                    </p>

                    <a href="/collections" class="featured-link" title="Browse curated content collections">
                        Browse All <i class="fas fa-arrow-right"></i>
                    </a>
                </article>

            </div>
        </section>

        <div class="page-wrapper">
            <div class="layout-container">

                <main class="content-area">

                    <h2 class="section-title">Latest From Unik Muse</h2>
                    <p class="section-subtitle">Tech • Travel • News • Stories • Guides</p>

                    <div class="ad-slot ad-top">
                        <p style="font-size: 0.85rem; color:#777;">Advertisement</p>
                    </div>

                    <div class="blog-grid">
                        @foreach ($data as $value)
                            <article class="blog-card">
                                <div class="card-image">
                                    @php
                                        $imagepath = json_decode($value->file_path, true);
                                        $img = !empty($imagepath) ? $imagepath[0] : 'uploads/no_image.jpg';
                                    @endphp
                                    <img src="{{ asset('storage/' . $img) }}" alt="{{ $value->title }}" loading="lazy">
                                </div>

                                <div class="card-content">
                                    <h3 class="card-title">{{ $value->title }}</h3>
                                    <p class="card-text">{{ Str::limit($value->description, 150) }}</p>

                                    <div class="card-meta">
                                        <a href="{{ route('post.show', $value->id) }}" class="read-more">
                                            Read More <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                        <small>
                                            <i class="far fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($value->created_date)->diffForHumans() }}
                                        </small>
                                        <p class="meta-info">
                                            {{ round(str_word_count($value->description) / 200) }} min read
                                        </p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </main>

                <aside class="sidebar">

                    <div class="sidebar-box sticky-ad">
                        <div class="ad-slot">
                            <p style="font-size: 0.85rem; color:#777;">Advertisement</p>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="sidebar-box">
                        <h3 class="sidebar-title">Categories</h3>
                        <ul class="sidebar-list">
                            <li><a href="#">Technology</a></li>
                            <li><a href="#">Travel</a></li>
                            <li><a href="#">News</a></li>
                            <li><a href="#">Lifestyle</a></li>
                            <li><a href="#">Trending</a></li>
                        </ul>
                    </div>

                    <div class="sidebar-box">
                        <h3 class="sidebar-title">Popular Posts</h3>
                        <ul class="sidebar-list">
                            <li><a href="#">Trending Tech Innovations</a></li>
                            <li><a href="#">Top Travel Destinations 2025</a></li>
                            <li><a href="#">Latest AI News</a></li>
                        </ul>
                    </div>

                </aside>

            </div>
        </div>
    </div>

    @include('common.footer')
</body>

</html>
