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

        /* Contact Hero */
        .contact-hero {
            padding: 80px 0 40px;
            text-align: center;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1) 0%, rgba(41, 128, 185, 0.05) 100%);
            margin-bottom: 60px;
        }

        .contact-hero h1 {
            font-size: 48px;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .contact-hero p {
            font-size: 20px;
            color: #7f8c8d;
            max-width: 700px;
            margin: 0 auto 20px;
        }

        .highlight {
            color: #3498db;
            font-weight: 600;
        }

        /* Contact Container */
        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            padding: 0 0 80px;
        }

        @media (max-width: 992px) {
            .contact-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }
        }

        /* Contact Info */
        .contact-info {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .contact-info h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 32px;
            position: relative;
            padding-bottom: 15px;
        }

        .contact-info h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: #3498db;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .info-icon {
            width: 50px;
            height: 50px;
            background-color: rgba(52, 152, 219, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .info-icon i {
            font-size: 20px;
            color: #3498db;
        }

        .info-content h3 {
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 20px;
        }

        .info-content p {
            color: #7f8c8d;
            margin-bottom: 5px;
        }

        .info-content a {
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s;
        }

        .info-content a:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        /* Social Links */
        .social-section {
            margin-top: 40px;
        }

        .social-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 22px;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background-color: #2c3e50;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background-color: #3498db;
            transform: translateY(-3px);
        }

        /* Contact Form */
        .contact-form {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .contact-form h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 32px;
            position: relative;
            padding-bottom: 15px;
        }

        .contact-form h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: #3498db;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            font-family: inherit;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 576px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Submit Button */
        .submit-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(41, 128, 185, 0.2);
        }

        .submit-btn i {
            margin-right: 10px;
        }

        /* Form Status Messages */
        .form-status {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }

        .form-status.success {
            background-color: rgba(46, 204, 113, 0.1);
            color: #27ae60;
            border: 1px solid #27ae60;
            display: block;
        }

        .form-status.error {
            background-color: rgba(231, 76, 60, 0.1);
            color: #c0392b;
            border: 1px solid #c0392b;
            display: block;
        }

        /* FAQ Section */
        .faq-section {
            padding: 80px 0;
            /* background-color: #f5f7fa; */
            margin-top: 40px;
        }

        .faq-section h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 50px;
            font-size: 36px;
        }

        .faq-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            background: white;
            margin-bottom: 15px;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .faq-question {
            padding: 20px;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
        }

        .faq-question:hover {
            background-color: #f9f9f9;
        }

        .faq-question i {
            transition: transform 0.3s;
        }

        .faq-question.active i {
            transform: rotate(180deg);
        }

        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease-out;
        }

        .faq-answer.active {
            padding: 20px;
            max-height: 500px;
        }

        .faq-answer p {
            color: #555;
            line-height: 1.7;
        }

        /* Map Section */
        .map-section {
            padding: 40px 0;
        }

        .map-container {
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .map-placeholder {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .map-placeholder i {
            font-size: 60px;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .map-placeholder h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
    </style>
    <div class="container">
        <!-- Contact Hero -->
        <section class="contact-hero">
            <div class="container">
                <h1>Get In Touch</h1>
                <p>We'd love to hear from you! Whether you have questions, feedback, or collaboration ideas, <span
                        class="highlight">your thoughts inspire us</span>.</p>
            </div>
        </section>

        <!-- Contact Container -->
        <div class="container contact-container">
            <!-- Contact Information -->
            <div class="contact-info">
                <h2>Contact Information</h2>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <h3>Our Location</h3>
                        <p>666 Virtual City</p>
                        <p>Creative District, Muse City 10001</p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <h3>Email Us</h3>
                        <p>General Inquiries: <a href="mailto:hello@unikmuse.com">hello@unikmuse.com</a></p>
                        <p>Collaborations: <a href="mailto:collab@unikmuse.com">collab@unikmuse.com</a></p>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <h3>Call Us</h3>
                        <p>Phone: <a href="tel:+XXXXXXXXXX">+91 (XXX) XXX-XXXX</a></p>
                        <p>Hours: Monday-Friday, 9am-5pm EST</p>
                    </div>
                </div>

                <div class="social-section">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        {{-- <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a> --}}
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        {{-- <a href="#" title="Pinterest"><i class="fab fa-pinterest-p"></i></a> --}}
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form">
                <h2>Send a Message</h2>

                <div id="formStatus" class="form-status"></div>

                <form id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Your Name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="your@email.com" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" class="form-control"
                            placeholder="What is this regarding?" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="" disabled selected>Select a category</option>
                            <option value="general">General Inquiry</option>
                            <option value="feedback">Website Feedback</option>
                            <option value="collaboration">Collaboration Idea</option>
                            <option value="guest-post">Guest Post Proposal</option>
                            <option value="technical">Technical Issue</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Your Message *</label>
                        <textarea id="message" name="message" class="form-control" placeholder="What would you like to share with us?"
                            required></textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>

        <!-- Map Section -->
        <section class="container map-section">
            <div class="map-container">
                <div class="map-placeholder">
                    <i class="fas fa-map-marked-alt"></i>
                    <h3>Our Virtual Headquarters</h3>
                    <p>Unik Muse operates as a digital-first publication with contributors from around the world.</p>
                    <p>Our main coordination hub is based in Muse City, but our ideas know no borders.</p>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="faq-section">
            <div class="container">
                <h2>Frequently Asked Questions</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>How long does it take to get a response?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>We strive to respond to all inquiries within 24-48 hours during weekdays. For more
                                complex
                                questions or collaboration proposals, it may take up to 3-5 business days as we give
                                each
                                idea the consideration it deserves.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Do you accept guest posts?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Yes! We welcome thoughtful guest contributions that align with our mission of providing
                                unique perspectives and inspiration. Please use the "Guest Post Proposal" category when
                                submitting your idea through our contact form, and include links to your previous work
                                or
                                writing samples.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Can I suggest a topic for the blog?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>Absolutely! We value our readers' insights and regularly consider topic suggestions.
                                While we
                                can't guarantee every suggestion will become a post, we read and consider all ideas. The
                                most successful suggestions are those that offer a unique angle on a subject rather than
                                covering well-trodden ground.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <span>Do you offer advertising or sponsored content?</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>We carefully select a limited number of partnerships with brands and organizations that
                                align
                                with our values. All sponsored content is clearly marked as such and maintains the same
                                quality standards as our regular posts. For partnership inquiries, please select
                                "Collaboration Idea" in the contact form.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            // Form Submission Handler
            document.getElementById('contactForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form elements
                const form = e.target;
                const formStatus = document.getElementById('formStatus');

                // Simple validation
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const subject = document.getElementById('subject').value.trim();
                const category = document.getElementById('category').value;
                const message = document.getElementById('message').value.trim();

                if (!name || !email || !subject || !category || !message) {
                    showFormStatus('Please fill in all required fields.', 'error');
                    return;
                }

                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showFormStatus('Please enter a valid email address.', 'error');
                    return;
                }

                // In a real application, you would send the form data to a server here
                // For this demo, we'll simulate a successful submission

                // Show loading state
                const submitBtn = form.querySelector('.submit-btn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                submitBtn.disabled = true;

                // Simulate API call
                setTimeout(() => {
                    // Show success message
                    showFormStatus('Thank you for your message! We\'ll get back to you within 24-48 hours.',
                        'success');

                    // Reset form
                    form.reset();

                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 1500);
            });

            function showFormStatus(message, type) {
                const formStatus = document.getElementById('formStatus');
                formStatus.textContent = message;
                formStatus.className = 'form-status ' + type;

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    formStatus.style.display = 'none';
                }, 5000);
            }

            // FAQ Toggle Functionality
            document.querySelectorAll('.faq-question').forEach(question => {
                question.addEventListener('click', () => {
                    const answer = question.nextElementSibling;
                    const isActive = question.classList.contains('active');

                    // Close all other FAQs
                    document.querySelectorAll('.faq-question').forEach(q => {
                        q.classList.remove('active');
                    });
                    document.querySelectorAll('.faq-answer').forEach(a => {
                        a.classList.remove('active');
                    });

                    // Open clicked FAQ if it wasn't already active
                    if (!isActive) {
                        question.classList.add('active');
                        answer.classList.add('active');
                    }
                });
            });

            // Add a simple animation to form inputs on focus
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });
        </script>
    </div>
@include('common.footer')