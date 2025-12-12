<footer class="blog-footer bg-unik-primary text-white">
    <div class="footer-content container-unik mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About Section -->
            <div class="footer-section about">
                <h2 class="text-xl font-bold text-unik-light mb-4">About Us</h2>
                <p class="text-white/90 leading-relaxed">
                    Welcome to Unik Muse, where we share insightful articles, tutorials, and stories that inspire.
                    Follow us for thought-provoking content that sparks creativity.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="footer-section links">
                <h2 class="text-xl font-bold text-unik-light mb-4">Quick Links</h2>
                <ul class="space-y-2">
                    <li><a href="{{ url('/') }}"
                            class="text-white/80 hover:text-unik-light hover:underline transition-colors">Home</a></li>
                    <li><a href="{{ url('/about') }}"
                            class="text-white/80 hover:text-unik-light hover:underline transition-colors">About</a></li>
                    <li><a href="{{ url('/blog') }}"
                            class="text-white/80 hover:text-unik-light hover:underline transition-colors">Blog</a></li>
                    <li><a href="{{ url('/contact') }}"
                            class="text-white/80 hover:text-unik-light hover:underline transition-colors">Contact</a>
                    </li>
                </ul>
            </div>

            <!-- Contact Section -->
            <div class="footer-section contact">
                <h2 class="text-xl font-bold text-unik-light mb-4">Contact Us</h2>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-envelope text-unik-accent mt-1"></i>
                        <a href="mailto:info@unikmuse.com"
                            class="text-white/80 hover:text-unik-light hover:underline transition-colors">info@unikmuse.com</a>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-phone text-unik-accent mt-1"></i>
                        <a href="tel:+919877654765"
                            class="text-white/80 hover:text-unik-light hover:underline transition-colors">+91 987 7654
                            765</a>
                    </li>
                </ul>
            </div>

            <!-- Social Media -->
            <div class="footer-section social">
                <h2 class="text-xl font-bold text-unik-light mb-4">Follow Us</h2>
                <div class="social-icons flex gap-4">
                    <a href="#" target="_blank"
                        class="w-10 h-10 bg-white/10 hover:bg-unik-secondary rounded-full flex items-center justify-center transition-colors">
                        <i class="fab fa-facebook-f text-white"></i>
                    </a>
                    <a href="#" target="_blank"
                        class="w-10 h-10 bg-white/10 hover:bg-unik-accent rounded-full flex items-center justify-center transition-colors">
                        <i class="fab fa-instagram text-white"></i>
                    </a>
                </div>

                <!-- Newsletter Subscription -->
                <div class="mt-6">
                    <p class="text-white/90 text-sm mb-2">Subscribe to our newsletter</p>
                    <form class="flex gap-2">
                        <input type="email" placeholder="Your email"
                            class="flex-grow px-3 py-2 rounded-unik-md border border-unik-border focus:outline-none focus:ring-2 focus:ring-unik-accent text-unik-dark">
                        <button type="submit"
                            class="bg-unik-secondary hover:bg-unik-secondary/90 text-white px-4 py-2 rounded-unik-md transition-colors">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Disclaimer Section -->
    <div class="footer-disclaimer bg-unik-primary/90 border-t border-unik-border">
        <div class="container-unik mx-auto px-4 py-6">
            <p class="footer-message text-center text-white/80 text-sm mb-4">
                All content is original and follows copyright guidelines.
                Ads are served via trusted partners.
            </p>

            <div class="footer-links flex flex-wrap justify-center gap-4 md:gap-6">
                <a href="{{ url('/privacy-policy') }}"
                    class="text-white/80 hover:text-unik-light hover:underline text-sm transition-colors">Privacy
                    Policy</a>
                <span class="text-white/50">•</span>
                <a href="{{ url('/terms') }}"
                    class="text-white/80 hover:text-unik-light hover:underline text-sm transition-colors">Terms &
                    Conditions</a>
                <span class="text-white/50">•</span>
                <a href="{{ url('/disclaimer') }}"
                    class="text-white/80 hover:text-unik-light hover:underline text-sm transition-colors">Disclaimer</a>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom bg-unik-dark text-center py-4">
        <div class="container-unik mx-auto px-4">
            <p class="text-white/90 text-sm">
                &copy; {{ date('Y') }} Unik Muse | All rights reserved.
            </p>
            <p class="text-white/70 text-xs mt-1">
                Made with <i class="fas fa-heart text-unik-accent"></i> for inspiring minds
            </p>
        </div>
    </div>
</footer>
