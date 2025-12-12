<header class="blog-header bg-unik-primary text-white shadow-unik-lg" role="banner">
    <nav class="navbar container-unik mx-auto px-4" role="navigation" aria-label="Main Navigation">
        <!-- Logo -->
        <div class="logo flex items-center">
            <a href="{{ url('/') }}" class="logo-link flex items-center gap-3">
                <div class="logo-text">
                    <img src="{{ asset('assets/unikmusewhite.png') }}" alt="Unik Muse" class="h-8 md:h-10">
                </div>
            </a>
        </div>

        <!-- Search Bar - Hidden on mobile -->
        <div class="search-container flex-grow mx-4 hidden md:block">
            <form class="search-bar w-full" action="{{ url('/') }}" method="GET" role="search"
                onsubmit="encodeSearch()">
                <label for="search-input" class="visually-hidden">Search Blog</label>
                <div class="relative w-full">
                    <input id="search-input" type="text" name="q" placeholder="Search articles..." required
                        class="w-full rounded-unik-lg px-4 py-2 border border-unik-border focus:outline-none focus:ring-2 focus:ring-unik-primary focus:border-transparent text-unik-dark">
                    <button type="submit"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-unik-secondary text-white px-4 py-1 rounded-unik-md hover:bg-unik-secondary/90 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <!-- Hamburger Menu for Mobile -->
        <div class="hamburger md:hidden" id="hamburger-btn" aria-label="Toggle navigation menu" aria-expanded="false"
            role="button" aria-controls="nav-links" tabindex="0">
            <span class="bg-white"></span>
            <span class="bg-white"></span>
            <span class="bg-white"></span>
        </div>

        <!-- Navigation Links - Fixed for responsive design -->
        <ul class="nav-links" id="nav-links">
            <!-- Mobile Search - Hidden on desktop -->
            <li class="md:hidden mb-4">
                <form class="search-bar w-full" action="{{ url('/') }}" method="GET" role="search"
                    onsubmit="encodeSearch()">
                    <label for="search-input-mobile" class="visually-hidden">Search Blog</label>
                    <div class="relative w-full">
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
                <a href="javascript:void(0)"
                    class="dropdown-toggle_box nav-link block text-white hover:text-unik-light transition-colors py-3 px-4 rounded-unik-md hover:bg-white/10 md:inline-block md:py-2 flex items-center justify-between md:justify-start gap-2"
                    id="dropdown-cat">
                    Categories <i class="fas fa-chevron-down text-sm"></i>
                </a>

                <ul class="dropdown-menu_category absolute left-0 mt-0 w-full md:w-56 bg-unik-light rounded-unik-lg shadow-unik-lg z-50 py-2 border border-unik-border md:opacity-0 md:invisible md:group-hover:opacity-100 md:group-hover:visible transition-all duration-300"
                    aria-label="Submenu">
                    <li><a href="/category/technology"
                            class="block px-4 py-3 text-unik-primary hover:bg-unik-primary/10 transition-colors">Technology</a>
                    </li>
                    <li><a href="/category/travel"
                            class="block px-4 py-3 text-unik-secondary hover:bg-unik-secondary/10 transition-colors">Travel</a>
                    </li>
                    <li><a href="/category/news"
                            class="block px-4 py-3 text-unik-accent hover:bg-unik-accent/10 transition-colors">News</a>
                    </li>
                    <li><a href="/category/lifestyle"
                            class="block px-4 py-3 text-unik-light hover:bg-unik-light/20 transition-colors text-unik-dark">Lifestyle</a>
                    </li>
                    <li><a href="/category/inspiration"
                            class="block px-4 py-3 text-unik-primary hover:bg-unik-primary/10 transition-colors">Inspiration</a>
                    </li>
                    <li><a href="/category/photo-stories"
                            class="block px-4 py-3 text-unik-accent hover:bg-unik-accent/10 transition-colors">Photo
                            Stories</a></li>
                    <li><a href="/category/guides"
                            class="block px-4 py-3 text-unik-primary hover:bg-unik-primary/10 transition-colors">Guides</a>
                    </li>
                    <li><a href="/category/collections"
                            class="block px-4 py-3 text-unik-secondary hover:bg-unik-secondary/10 transition-colors">Collections</a>
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
        });
    </script>
</header>
