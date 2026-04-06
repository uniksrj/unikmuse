{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}
<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
<header class="blog-header bg-unik-primary text-white shadow-unik-lg" role="banner">
    <nav class="navbar container-unik mx-auto px-6" role="navigation" aria-label="Main Navigation">
        <!-- Logo -->
        <div class="logo flex items-center">
            <a href="{{ url('/') }}" class="logo-link flex items-center gap-3">
                <div class="logo-text">
                    <img src="{{ asset('assets/unikmusewhite.webp') }}" alt="Unik Muse" class="h-10 md:h-10">
                </div>
            </a>
        </div>

        <!-- Search Bar - Visible on Home Page Only -->
        @if (request()->is('/'))
            <div class="search-container flex-grow mx-4 hidden md:block">
                <form class="search-bar w-full" action="{{ url('/') }}" method="GET" role="search"
                    onsubmit="encodeSearch()">
                    <label for="search-input" class="visually-hidden">Search Blog</label>
                    <div class="relative w-full">
                        <input id="search-input" type="text" name="q" placeholder="Search articles..." required
                            class="w-full rounded-unik-lg px-4 py-2 pr-14 hidden md:block border border-unik-border focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent unik-text-dark">
                        <button type="submit"
                            class="bg-[#4E56C0] absolute hidden md:flex items-center justify-center right-2 top-1/2 -translate-y-1/2 text-white px-3 py-1 rounded-lg transition-colors"
                            aria-label="Search">
                            <i class="fas fa-search hover:text-unik-accent"></i>
                        </button>
                    </div>
                </form>
            </div>
        @endif
        <!-- Hamburger Menu for Mobile -->
        <div class="hamburger md:hidden mx-4" id="hamburger-btn" aria-label="Toggle navigation menu"
            aria-expanded="false" role="button" aria-controls="nav-links" tabindex="0">
            <span class="bg-white"></span>
            <span class="bg-white"></span>
            <span class="bg-white"></span>
        </div>

        <ul class="nav-links mb-0 py-2" id="nav-links">
            <li class="md:hidden mb-4">
                <form class="search-bar w-full" action="{{ url('/') }}" method="GET" role="search"
                    onsubmit="encodeSearch()">
                    <label for="search-input-mobile" class="visually-hidden">Search Blog</label>
                    <div class="relative w-full hidden md:block">
                        <input id="search-input-mobile" type="text" name="q" placeholder="Search articles..."
                            required
                            class="w-full rounded-unik-lg px-4 py-2 border border-unik-border focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent text-unik-dark">
                        <button type="submit"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-unik-secondary text-white px-4 py-1 rounded-unik-md hover:bg-unik-secondary/90 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </li>

            <li><a href="{{ url('/') }}"
                    class="nav-link block text-white hover:text-unik-light transition-colors py-3 px-4 rounded-unik-md hover:bg-white/10 md:inline-block md:py-2"
                    aria-current="{{ request()->is('/') ? 'page' : false }}">Home</a></li>
            <li><a href="{{ url('/blog') }}"
                    class="nav-link block text-white hover:text-unik-light transition-colors py-3 px-4 rounded-unik-md hover:bg-white/10 md:inline-block md:py-2">Blog</a>
            </li>

            <!-- Categories Dropdown with fixed hover -->
            <li class="dropdown relative group">
                <a href="{{ url('/categories') }}"
                    class="dropdown-toggle_box nav-link block text-white hover:text-unik-light transition-colors py-3 px-4 rounded-unik-md hover:bg-white/10 md:inline-block md:py-2 flex items-center justify-between md:justify-start gap-2"
                    id="dropdown-cat">
                    Categories <i class="fas fa-chevron-down text-sm"></i>
                </a>

                <ul id="category-menu"
                    class="dropdown-menu_category relative md:absolute left-0 mt-0 w-full md:w-56 bg-unik-primary rounded-unik-lg shadow-unik-lg z-50 py-2 hidden md:opacity-0 md:invisible md:group-hover:opacity-100 md:group-hover:visible transition-all duration-300"
                    aria-label="Submenu">
                    <li><a href="{{ route('category.show', 'technology') }}"
                            class="block px-4 py-3 text-unik-primary hover:bg-unik-light/20 transition-colors">Technology</a>
                    </li>
                    <li><a href="{{ route('category.show', 'travel') }}"
                            class="block px-4 py-3 text-unik-secondary hover:bg-unik-light/20 transition-colors">Travel</a>
                    </li>
                    <li><a href="{{ route('category.show', 'life-style') }}"
                            class="block px-4 py-3 text-unik-light hover:bg-unik-light/20 transition-colors text-unik-dark">Lifestyle</a>
                    </li>
                    <li><a href="{{ route('category.show', 'digital-trends') }}"
                            class="block px-4 py-3 text-unik-primary hover:bg-unik-light/20 transition-colors">Digital
                            Trends</a>
                    </li>
                    <li><a href="{{ route('category.show', 'productivity') }}"
                            class="block px-4 py-3 text-unik-accent hover:bg-unik-light/20 transition-colors">Productivity
                        </a></li>
                    <li><a href="{{ route('category.show', 'news-updates') }}"
                            class="block px-4 py-3 text-unik-primary hover:bg-unik-light/20 transition-colors">News
                            & Updates</a>
                    </li>
                    <li><a href="{{ route('category.show', 'stories-experiences') }}"
                            class="block px-4 py-3 text-unik-secondary hover:bg-unik-light/20 transition-colors">Stories
                            & Experiences</a>
                    </li>
                    <li><a href="{{ route('category.show', 'creativity-inspiration') }}"
                            class="block px-4 py-3 text-unik-secondary hover:bg-unik-light/20 transition-colors">Creativity
                            & Inspiration</a>
                    </li>
                </ul>
            </li>

            <li><a href="{{ url('/about') }}"
                    class="nav-link block text-white hover:text-unik-light transition-colors py-3 px-4 rounded-unik-md hover:bg-white/10 md:inline-block md:py-2">About</a>
            </li>
            <li><a href="{{ url('/contact') }}"
                    class="nav-link block text-white hover:text-unik-light transition-colors py-3 px-4 rounded-unik-md hover:bg-white/10 md:inline-block md:py-2">Contact</a>
            </li>
        </ul>
    </nav>

    <script>
        function encodeSearch() {
            let input = document.getElementById("search-input");
            input.value = btoa(input.value);
        }
        const hamburger = document.getElementById('hamburger-btn');
        const navLinks = document.getElementById('nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');

            if (navLinks.classList.contains('active')) {
                document.body.style.overflow = "hidden";
            } else {
                document.body.style.overflow = "auto";
            }
        });
    </script>
</header>

@if (!empty($breadcrumbs))
    <nav aria-label="Breadcrumb">
        <div class="bg-white border-b border-unik-border">
            <div class="container-unik py-4 flex items-center space-x-2 text-sm">
                @foreach ($breadcrumbs as $crumb)
                    <a href="{{ $crumb['url'] ?? 'javascript:void(0)' }}"
                        class="flex items-center text-unik-primary hover:text-[#424242] transition-colors">
                        <i class="fas {{ $crumb['icon'] ?? 'fa-circle' }} mr-1"></i> {{ $crumb['label'] }}
                    </a>
                    @if (!$loop->last)
                        <i class="fas fa-chevron-right text-unik-muted text-xs mx-2"></i>
                    @endif
                @endforeach
            </div>
        </div>
    </nav>
@endif

@if (!empty($breadcrumbs))
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    @foreach ($breadcrumbs as $index => $crumb)
    {
      "@type": "ListItem",
      "position": {{ $index + 1 }},
      "name": "{{ $crumb['label'] }}"
      @if(!empty($crumb['url']))
      ,"item": "{{ $crumb['url'] }}"
      @endif
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
@endif
