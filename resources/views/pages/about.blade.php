    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/readmore.css') }}">
    @include('common.header')
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .about-hero {
            padding: 80px 0 40px;
            text-align: center;
        }

        .about-hero h1 {
            font-size: 48px;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .about-hero p {
            font-size: 20px;
            color: #7f8c8d;
            max-width: 700px;
            margin: 0 auto 40px;
        }

        .highlight {
            color: #3498db;
            font-weight: 600;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            padding: 60px 0;
        }

        @media (max-width: 768px) {
            .about-content {
                grid-template-columns: 1fr;
            }
        }

        .about-text {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .about-text h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 32px;
            position: relative;
            padding-bottom: 10px;
        }

        .about-text h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: #3498db;
        }

        .about-text p {
            margin-bottom: 20px;
            color: #555;
            font-size: 17px;
        }

        .mission {
            background-color: white;
            padding: 60px 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 50px;
        }

        .mission h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }

        .mission-content {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        @media (max-width: 768px) {
            .mission-content {
                flex-direction: column;
            }
        }

        .mission-icon {
            font-size: 100px;
            color: #3498db;
            flex-shrink: 0;
        }

        .mission-text {
            font-size: 18px;
            color: #555;
            line-height: 1.7;
        }

        .values {
            padding: 60px 0;
        }

        .values h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 50px;
            font-size: 32px;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .value-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s;
        }

        .value-card:hover {
            transform: translateY(-10px);
        }

        .value-icon {
            font-size: 40px;
            color: #3498db;
            margin-bottom: 20px;
        }

        .value-card h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 22px;
        }

        .author-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 60px 0;
            margin: 60px 0;
            border-radius: 10px;
        }

        .author-container {
            display: flex;
            align-items: center;
            gap: 50px;
        }

        @media (max-width: 768px) {
            .author-container {
                flex-direction: column;
                text-align: center;
            }
        }

        .author-img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .author-info h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 32px;
        }

        .author-info p {
            color: #555;
            font-size: 17px;
            margin-bottom: 15px;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #2c3e50;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .social-links a:hover {
            background-color: #3498db;
        }

        /* CTA Section */
        .cta-section {
            text-align: center;
            padding: 60px 0;
            background-color: #2c3e50;
            color: white;
            border-radius: 10px;
            margin-bottom: 50px;
        }

        .cta-section h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }

        .cta-button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 15px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 18px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .cta-button:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
        }
    </style>
    <div class="container about-page">
        <section class="about-hero">
            <div class="container">
                <h1>About Unik Muse</h1>
                <p>Welcome to a space where <span class="highlight">unique perspectives</span> meet <span
                        class="highlight">inspiring ideas</span>. We believe every thought has the power to spark
                    something
                    extraordinary.</p>
            </div>
        </section>

        <!-- About Content -->
        <section class="container about-content">
            <div class="about-text">
                <h2>Our Story</h2>
                <p>Unik Muse began as a simple idea in 2020: to create a digital sanctuary for curious minds. What
                    started
                    as a personal blog has grown into a community of thinkers, creators, and lifelong learners.</p>
                <p>Our name reflects our mission—<strong>"Unik"</strong> for the distinctive perspectives we share, and
                    <strong>"Muse"</strong> for the inspiration we aim to provide. We believe that in a world of endless
                    information, what's often missing is the unique angle, the fresh take, the thoughtful pause.
                </p>
                <p>Today, we publish weekly articles exploring everything from creativity and technology to mindfulness
                    and
                    personal growth. Each piece is carefully crafted to not just inform, but to inspire action and
                    reflection.</p>
            </div>
            <div class="about-text">
                <h2>What We Believe</h2>
                <p>We operate on a few core principles that guide everything we publish:</p>
                <p><strong>Depth over breadth:</strong> We'd rather explore one idea thoroughly than skim ten. Our
                    articles
                    are researched, thoughtful, and substantive.</p>
                <p><strong>Quality over quantity:</strong> We publish less frequently than many blogs, but each article
                    receives careful attention and multiple rounds of editing.</p>
                <p><strong>Conversation over monologue:</strong> We see our articles as starting points for discussion,
                    not
                    final pronouncements. Your perspective in the comments is valued.</p>
                <p><strong>Practical inspiration:</strong> We aim to provide ideas you can actually use, not just
                    admire.
                    Each article includes actionable takeaways.</p>
            </div>
        </section>

        <!-- Mission Section -->
        <section class="container mission">
            <h2>Our Mission</h2>
            <div class="mission-content">
                <div class="mission-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="mission-text">
                    <p>To create a digital space that nurtures intellectual curiosity and inspires meaningful action. We
                        aim
                        to bridge the gap between information and insight, offering content that doesn't just fill time
                        but
                        enriches it.</p>
                    <p>In a world of clickbait and superficial content, we're building an oasis of thoughtful discourse.
                        We
                        want our readers to leave each article not just knowing something new, but seeing something
                        differently.</p>
                    <p>Our commitment is to produce content that stands the test of time—articles you might bookmark,
                        share
                        with friends, or return to months later when you need that perspective again.</p>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="container values">
            <h2>Our Core Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Unique Perspective</h3>
                    <p>We seek out angles and insights you won't find elsewhere, valuing original thought over echo
                        chambers.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Authenticity</h3>
                    <p>We write with genuine voice and conviction, sharing real experiences and honest reflections.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Community</h3>
                    <p>We believe ideas grow best in conversation. Our readers are collaborators in this journey.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3>Growth Mindset</h3>
                    <p>We're committed to learning, evolving, and improving—both in our content and as individuals.</p>
                </div>
            </div>
        </section>

        {{-- <!-- Author Section -->
        <section class="author-section">
            <div class="container author-container">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80"
                    alt="Alex Morgan" class="author-img">
                <div class="author-info">
                    <h2>Meet the Founder</h2>
                    <p><strong>Alex Morgan</strong> started Unik Muse after a decade working in content creation and
                        digital
                        media. Frustrated by the shallow nature of much online content, Alex wanted to build a space for
                        substantive, inspiring writing.</p>
                    <p>When not writing or editing for Unik Muse, Alex can be found hiking, reading philosophy, or
                        experimenting with new forms of digital storytelling. Alex believes that the best ideas often
                        come
                        from unexpected connections between seemingly unrelated fields.</p>
                    <p>"My goal with Unik Muse is simple: to create content that makes people pause, think, and see
                        their
                        world a little differently."</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-medium"></i></a>
                    </div>
                </div>
            </div>
        </section> --}}

        <!-- CTA Section -->
        <section class="container cta-section">
            <h2>Join Our Community</h2>
            <p>Subscribe to our newsletter to receive weekly inspiration, exclusive content, and updates on new articles
                directly in your inbox.</p>
            <a href="/subscribe.html" class="cta-button">Subscribe Now</a>
        </section>
    </div>
    @include('common.footer')
