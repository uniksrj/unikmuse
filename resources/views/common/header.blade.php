<style>
    /* .logo-text {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.5px;
        line-height: 1;
        padding-bottom: 4px;
        border-bottom: 1px solid #ffffff;
    }

    .logo-slogan {
        font-size: 0.6rem;
        color: #ffffff;
        letter-spacing: 1px;
        margin-top: 4px;
        font-style: italic;
        font-family: 'Georgia', serif;
        font-weight: 500;
        text-align: center;
    } */
</style>

<header class="blog-header" role="banner">
    <nav class="navbar" role="navigation" aria-label="Main Navigation">
        <!-- Logo -->
        <div class="logo">
            <a href="{{ url('/') }}" class="logo-link">
                <div class="logo-text">
                    <img src="{{ asset('assets/unikmusewhite.png') }}" alt="Unik Muse">
                </div>
                {{-- <div class="logo-slogan">Thoughts That Inspire</div> --}}
            </a>
        </div>

        <div class="hamburger" id="hamburger-btn" aria-label="Toggle navigation menu" aria-expanded="false"
            aria-expanded="false" role="button" aria-controls="nav-links" tabindex="0">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <ul class="nav-links" id="nav-links">
            <li><a href="{{ url('/') }}" aria-current="{{ request()->is('/') ? 'page' : false }}">Home</a></li>            
            <li><a href="{{ url('/blog') }}">Blog</a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropdown-toggle_box" id="dropdown-cat">Categories <i class="fas fa-chevron-down"></i></a>

                <ul class="dropdown-menu_category" aria-label="Submenu">
                    <li><a href="/category/technology">Technology</a></li>
                    <li><a href="/category/travel">Travel</a></li>
                    <li><a href="/category/news">News</a></li>
                    <li><a href="/category/lifestyle">Lifestyle</a></li>
                    <li><a href="/category/inspiration">Inspiration</a></li>
                    <li><a href="/category/photo-stories">Photo Stories</a></li>
                    <li><a href="/category/guides">Guides</a></li>
                    <li><a href="/category/collections">Collections</a></li>
                </ul>
            </li>
            <li><a href="{{ url('/about') }}">About</a></li>
            <li><a href="{{ url('/contact') }}">Contact</a></li>
        </ul>

        <form class="search-bar" action="{{ url('/') }}" method="GET" role="search" onsubmit="encodeSearch()">
            <label for="search-input" class="visually-hidden">Search Blog</label>
            <input id="search-input" type="text" name="q" placeholder="Search..." required>
            <button type="submit">Go</button>
        </form>
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
