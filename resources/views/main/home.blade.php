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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        @font-face {
            font-display: swap !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite('resources/js/blog.js')
</head>

<body class="bg-unik-light text-unik-dark">
    @include('common.header')

    <div class="container-unik">
        <!-- Hero Carousel -->
        <div class="carousel-container rounded-unik-xl relative overflow-hidden shadow-unik-lg my-8 md:my-12">
            <div class="carousel-slide active">
                <img src="{{ asset('assets/snow.jpg') }}" alt="Snowy Mountain Landscape" loading="lazy"
                    class="w-full h-64 md:h-96 object-cover">
                <div
                    class="carousel-caption absolute bottom-0 bg-gradient-to-t from-unik-primary/90 via-unik-secondary/50 to-transparent p-6 md:p-8">
                    <h3 class="text-2xl md:text-3xl font-bold unik-text-light mb-2">White Snow</h3>
                    <p class="text-white text-base md:text-lg width-full opacity-90">
                        I travel not to go anywhere, but to go. I travel for travel's sake. The great affair is to move.
                    </p>
                </div>
            </div>
        </div>

        <!-- Featured Section -->
        <section class="featured-section my-12 md:my-16">
            <div class="section-header text-center mb-8 md:mb-12">
                <h2 id="featured-heading" class="section-title text-2xl md:text-3xl font-bold text-unik-primary mb-3">
                    Featured Pages
                </h2>
                <p class="section-subtitle text-unik-muted text-base md:text-lg max-w-2xl mx-auto">
                    Handpicked collections of our best and most useful content.
                </p>
            </div>

            <div class="ads-block bg-white rounded-unik-lg p-6 text-center my-8">
                <p class="text-sm text-unik-muted">Advertisement</p>
            </div>

            <!-- Featured Cards Grid -->
            <div class="featured-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 - Travel -->
                <article
                    class="featured-card bg-white rounded-unik-lg shadow-unik-md border border-unik-border hover:shadow-unik-lg transition-all duration-300 hover:-translate-y-1 relative flex flex-col h-full">
                    <div class="border-l-4 border-l-unik-primary p-6 flex-grow">
                        <div class="featured-icon w-12 h-12 bg-unik-primary/10 rounded-unik-md flex items-center justify-center mb-4"
                            aria-hidden="true">
                            <i class="fas fa-compass text-unik-primary text-xl"></i>
                        </div>

                        <h3 class="featured-title text-lg font-semibold text-unik-dark mb-3">Travel Guides</h3>
                        <p class="featured-description text-unik-muted mb-5 text-sm leading-relaxed">
                            Expert travel tips, destination guides, itineraries, and cultural insights
                            to help you plan your next adventure.
                        </p>

                        <div class="mt-auto pt-4 absolute bottom-6">
                            <a href="/guides"
                                class="featured-link inline-flex items-center text-unik-primary font-medium hover:text-unik-secondary transition-colors text-sm"
                                title="Explore detailed travel guides, tips, and destinations">
                                Explore Guides <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </article>

                <!-- Card 2 - Inspiration -->
                <article
                    class="featured-card bg-white rounded-unik-lg shadow-unik-md border border-unik-border hover:shadow-unik-lg transition-all duration-300 hover:-translate-y-1 relative flex flex-col h-full">
                    <div class="border-l-4 border-l-unik-secondary p-6 flex-grow">
                        <div class="featured-icon w-12 h-12 bg-unik-secondary/10 rounded-unik-md flex items-center justify-center mb-4"
                            aria-hidden="true">
                            <i class="fas fa-lightbulb text-unik-secondary text-xl"></i>
                        </div>

                        <h3 class="featured-title text-lg font-semibold text-unik-dark mb-3">Inspiration</h3>
                        <p class="featured-description text-unik-muted mb-5 text-sm leading-relaxed">
                            Thought-provoking articles, stories, and ideas designed to spark creativity
                            and motivate personal growth.
                        </p>

                        <div class="mt-auto pt-4 absolute bottom-6">
                            <a href="/inspiration"
                                class="featured-link inline-flex items-center text-unik-primary font-medium hover:text-unik-secondary transition-colors text-sm"
                                title="Read inspiring articles and creative ideas">
                                Get Inspired <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </article>

                <!-- Card 3 - Photo Stories -->
                <article
                    class="featured-card bg-white rounded-unik-lg shadow-unik-md border border-unik-border hover:shadow-unik-lg transition-all duration-300 hover:-translate-y-1 relative flex flex-col h-full">
                    <div class="border-l-4 border-l-unik-accent p-6 flex-grow">
                        <div class="featured-icon w-12 h-12 bg-unik-accent/10 rounded-unik-md flex items-center justify-center mb-4"
                            aria-hidden="true">
                            <i class="fas fa-camera text-unik-accent text-xl"></i>
                        </div>

                        <h3 class="featured-title text-lg font-semibold text-unik-dark mb-3">Photo Stories</h3>
                        <p class="featured-description text-unik-muted mb-5 text-sm leading-relaxed">
                            Visual storytelling through stunning photography paired with deep,
                            narrative-driven experiences.
                        </p>

                        <div class="mt-auto pt-4 absolute bottom-6">
                            <a href="/photo-stories"
                                class="featured-link inline-flex items-center text-unik-primary font-medium hover:text-unik-secondary transition-colors text-sm"
                                title="View photo stories and visual narratives">
                                View Stories <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </article>

                <!-- Card 4 - Collections -->
                <article
                    class="featured-card bg-white rounded-unik-lg shadow-unik-md border border-unik-border hover:shadow-unik-lg transition-all duration-300 hover:-translate-y-1 relative flex flex-col h-full">
                    <div class="border-l-4 border-l-unik-light p-6 flex-grow">
                        <div class="featured-icon w-12 h-12 bg-unik-light/20 rounded-unik-md flex items-center justify-center mb-4"
                            aria-hidden="true">
                            <i class="fas fa-book text-unik-secondary text-xl"></i>
                        </div>

                        <h3 class="featured-title text-lg font-semibold text-unik-dark mb-3">Collections</h3>
                        <p class="featured-description text-unik-muted mb-5 text-sm leading-relaxed">
                            Curated collections of our most popular, evergreen, and timeless content
                            organized for easy reading.
                        </p>

                        <div class="mt-auto pt-4 absolute bottom-6">
                            <a href="/collections"
                                class="featured-link inline-flex items-center text-unik-primary font-medium hover:text-unik-secondary transition-colors text-sm"
                                title="Browse curated content collections">
                                Browse All <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <!-- Main Content Area -->
        <div class="page-wrapper my-12 md:my-16">
            <div class="layout-container grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Main Content -->
                <main class="content-area lg:col-span-2">
                    <h2 class="section-title text-2xl md:text-3xl font-bold text-unik-primary mb-2">Latest From Unik
                        Muse</h2>
                    <p class="section-subtitle text-unik-muted mb-6 md:mb-8">Tech • Travel • News • Stories • Guides</p>

                    <div class="ad-slot ad-top bg-white rounded-unik-md p-6 text-center my-6 md:my-8">
                        <p class="text-sm text-unik-muted">Advertisement</p>
                    </div>

                    <!-- Blog Grid -->
                    <div class="blog-grid grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($data as $value)
                            <article
                                class="blog-card bg-white rounded-unik-lg shadow-unik-md border border-unik-border hover:shadow-unik-lg transition-all duration-300 hover:-translate-y-1 flex flex-col h-full">
                                <div class="card-image h-48 md:h-56 overflow-hidden rounded-t-unik-lg">
                                    @php
                                        $imagepath = json_decode($value->file_path, true);
                                        $img = !empty($imagepath)
                                            ? $imagepath['original']['webp']
                                            : 'uploads/no_image.jpg';
                                    @endphp
                                    <img src="{{ asset('storage/' . $img) }}" alt="{{ $value->title }}" loading="lazy"
                                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>

                                <div class="card-content p-6 flex-grow relative flex flex-col">
                                    <h3 class="card-title text-lg md:text-xl font-semibold text-unik-dark mb-3">
                                        {{ $value->title }}</h3>
                                    <p
                                        class="card-text text-unik-muted mb-4 text-sm md:text-base leading-relaxed line-clamp-3">
                                        {{ Str::limit($value->description, 150) }}</p>

                                    <div
                                        class="card-meta flex justify-between items-center mt-auto pt-4 border-t border-unik-border">
                                        <a href="{{ route('post.show', $value->id) }}"
                                            class="bg-unik-primary text-white px-4 py-2 rounded-unik-md font-medium hover:bg-unik-primary/90 transition-colors text-sm">
                                            Read More <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                        <div class="text-right">
                                            <small class="block text-unik-muted text-xs">
                                                <i class="far fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($value->created_date)->diffForHumans() }}
                                            </small>
                                            <p class="meta-info text-unik-muted text-xs mt-1">
                                                {{ round(str_word_count($value->description) / 200) }} min read
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </main>

                <!-- Sidebar -->
                <aside class="sidebar lg:col-span-1 space-y-6">
                    <div
                        class="sidebar-box sticky-ad sticky bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                        <div class="ad-slot rounded-unik-lg p-6 text-center">
                            <p class="text-sm text-unik-muted">Advertisement</p>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="sidebar-box bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                        <h3 class="sidebar-title text-lg font-semibold text-unik-dark mb-4">Categories</h3>
                        <ul class="sidebar-list space-y-2">
                            <li><a href="{{ route('category.show', 'technology') }}"
                                    class="nav-link block p-2 rounded-unik-md hover:bg-unik-primary/5 text-unik-primary transition-colors">Technology</a>
                            </li>
                            <li><a href="{{ route('category.show', 'travel') }}"
                                    class="nav-link block p-2 rounded-unik-md hover:bg-unik-secondary/5 text-unik-secondary transition-colors">Travel</a>
                            </li>
                            <li><a href="{{ route('category.show', 'news') }}"
                                    class="nav-link block p-2 rounded-unik-md hover:bg-unik-accent/5 text-unik-accent transition-colors">News</a>
                            </li>
                            <li><a href="{{ route('category.show', 'lifestyle') }}"
                                    class="nav-link block p-2 rounded-unik-md hover:bg-unik-light/20 text-unik-secondary transition-colors">Lifestyle</a>
                            </li>
                            <li><a href="{{ route('category.show', 'digital-trends') }}"
                                    class="nav-link block p-2 rounded-unik-md hover:bg-unik-primary/5 text-unik-primary transition-colors">Digital
                                    Trends</a>
                            </li>
                            <li><a href="{{ route('category.show', 'productivity') }}"
                                    class="nav-link block p-2 rounded-unik-md hover:bg-unik-primary/5 text-unik-primary transition-colors">Productivity</a>
                            </li>
                            <li><a href="{{ route('category.show', 'news-updates') }}"
                                    class="nav-link block p-2 rounded-unik-md hover:bg-unik-primary/5 text-unik-primary transition-colors">News
                                    & Updates</a>
                            </li>
                            <li><a href="{{ route('category.show', 'stories-experiences') }}"
                                    class="nav-link block p-2 rounded-unik-md hover:bg-unik-primary/5 text-unik-primary transition-colors">Stories
                                    & Experiences</a>
                            </li>
                            <li><a href="{{ route('category.show', 'creativity-inspiration') }}"
                                    class="nav-link block p-2 rounded-unik-md hover:bg-unik-primary/5 text-unik-primary transition-colors">Creativity
                                    & Inspiration</a>
                            </li>
                        </ul>
                    </div>

                    <div class="sidebar-box bg-white rounded-unik-lg shadow-unik-sm p-6 border border-unik-border">
                        <h3 class="sidebar-title text-lg font-semibold text-unik-dark mb-4">Popular Posts</h3>
                        <ul class="sidebar-list space-y-3">
                            <li>
                                <a href="#"
                                    class="nav-link flex items-start gap-3 p-2 rounded-unik-md hover:bg-unik-primary/5 transition-colors">
                                    <span class="flex-shrink-0 w-2 h-2 bg-unik-primary rounded-full mt-2"></span>
                                    <span class="text-unik-primary text-sm">Trending Tech Innovations</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="nav-link flex items-start gap-3 p-2 rounded-unik-md hover:bg-unik-secondary/5 transition-colors">
                                    <span class="flex-shrink-0 w-2 h-2 bg-unik-secondary rounded-full mt-2"></span>
                                    <span class="text-unik-secondary text-sm">Top Travel Destinations 2025</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="nav-link flex items-start gap-3 p-2 rounded-unik-md hover:bg-unik-accent/5 transition-colors">
                                    <span class="flex-shrink-0 w-2 h-2 bg-unik-accent rounded-full mt-2"></span>
                                    <span class="text-unik-accent text-sm">Latest AI News</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    @include('common.footer')
</body>

</html>
