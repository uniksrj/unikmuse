
<footer class="blog-footer">
    <div class="footer-content">
        <div class="footer-section about">
            <h2>About Us</h2>
            <p>
                Welcome to My Blog, where we share insightful articles, tutorials, and stories. Follow us for more
                interesting reads.
            </p>
        </div>

        <div class="footer-section links">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('/about') }}">About</a></li>
                <li><a href="{{ url('/blog') }}">Blog</a></li>
                <li><a href="{{ url('/contact') }}">Contact</a></li>
            </ul>
        </div>

        <div class="footer-section contact">
            <h2>Contact Us</h2>
            <ul>
                <li>Email: <a href="mailto:info@myblog.com">info@myblog.com</a></li>
                <li>Phone: <a href="tel:+919877654765">+91 987 7654 765</a></li>
            </ul>
        </div>

        <div class="footer-section social">
            <h2>Follow Us</h2>
            <div class="social-icons">
                <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                {{-- <a href="#" target="_blank"><i class="fab fa-twitter"></i></a> --}}
                <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                {{-- <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a> --}}
            </div>
        </div>
    </div>
    <div class="footer-disclaimer">
        <p class="footer-message">
            All content is original and follows copyright guidelines.
            Ads are served via trusted partners.
        </p>

        <div class="footer-links">
            <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>
            <a href="{{ url('/terms') }}">Terms & Conditions</a>
            <a href="{{ url('/disclaimer') }}">Disclaimer</a>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; {{ date('Y') }} Unik Muse | All rights reserved.
    </div>
</footer>
