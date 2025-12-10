 @php
     $images = json_decode($post->file_path ?? '[]');
     $featuredImage = !empty($images) ? $images[0] : 'uploads/no_image.jpg';
 @endphp
 <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{{ $post->title }}",
  "image": "{{ asset('storage/' . $featuredImage) }}",
  "author": {
    "@type": "Person",
    "name": "{{ $post->author->name ?? 'Admin' }}"
  },
  "datePublished": "{{ $post->created_date }}",
  "description": "{{ Str::limit(strip_tags($post->description), 160) }}"
}
</script>

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>{{ $post->title }} | Unik Muse</title>

     <meta name="description" content="{{ Str::limit(strip_tags($post->description), 160) }}">

     <!-- Open Graph for Social Sharing -->
     <meta property="og:title" content="{{ $post->title }}">
     <meta property="og:description" content="{{ Str::limit(strip_tags($post->description), 160) }}">
     <meta property="og:image" content="{{ asset('storage/' . $featuredImage) }}">
     <meta property="og:type" content="article">

     <!-- Font Awesome -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

     <!-- Vite Assets -->
     @vite(['resources/css/app.css', 'resources/js/app.js'])

     <!-- Custom CSS -->
     <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
     <link rel="stylesheet" href="{{ asset('css/readmore.css') }}">
 </head>
 <div>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     @vite(['resources/css/app.css', 'resources/js/app.js'])
     <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
     <link rel="stylesheet" href="{{ asset('css/readmore.css') }}">
     @include('common.header')

     <!-- Reading Progress Bar -->
     <div class="reading-progress">
         <div class="reading-progress-bar" id="readingProgress"></div>
     </div>

     <!-- Article Header -->
     <header class="article-header">
         <div class="blog-container">
             <div class="article-header-content">
                 <h1 class="article-title">{{ $post->title }}</h1>
                 <div class="article-meta">
                     <div class="meta-item">
                         <i class="fas fa-user"></i>
                         <span>By {{ $post->author->name ?? 'Admin' }}</span>
                     </div>
                     <div class="meta-item">
                         <i class="far fa-calendar"></i>
                         <span>{{ \Carbon\Carbon::parse($post->created_date)->format('F j, Y') }}</span>
                     </div>
                     <div class="meta-item">
                         <i class="far fa-clock"></i>
                         <span>{{ $readingTime ?? 1 }} min read</span>
                     </div>
                     <div class="meta-item">
                         <i class="far fa-eye"></i>
                         <span>{{ $viewStats->total ?? '0' }} views</span>
                     </div>
                 </div>
             </div>
         </div>
     </header>

     <main class="blog-container">
         <div class="blog-layout">
             <!-- Main Article Content -->
             <div class="article-main">
                 <!-- Inline Ad - Top -->
                 <div class="ad-container ad-inline">
                     <div class="ad-label">Advertisement</div>
                     <div class="ad-content">
                         <i class="fas fa-ad fa-3x mb-3" style="color: #667eea;"></i>
                         <p class="ad-text">This space is available for advertising</p>
                         <a href="#" class="ad-cta">Learn More</a>
                     </div>
                 </div>

                 <!-- Article Content -->
                 <article class="article-content">
                     <!-- Featured Image -->

                     <img src="{{ asset('storage/' . $featuredImage) }}" alt="{{ $post->title }}"
                         class="featured-image">

                     <!-- Table of Contents -->
                     <div class="table-of-contents">
                         <h3 class="toc-title"><i class="fas fa-list me-2"></i>Table of Contents</h3>
                         <ul class="toc-list" id="tocList">
                             <!-- Generated dynamically by JavaScript -->
                         </ul>
                     </div>

                     <!-- Article Body -->
                     <div class="content-body">
                         {!! $post->description !!}
                     </div>

                     <!-- Image Gallery -->
                     @if (count($images) > 1)
                         <div class="image-gallery">
                             @foreach ($images as $index => $image)
                                 @if ($index > 0)
                                     <img src="{{ asset('storage/' . $image) }}"
                                         alt="Gallery Image {{ $index + 1 }}" class="gallery-image" loading="lazy">
                                 @endif
                             @endforeach
                         </div>
                     @endif

                     <div class="share-section">
                         <h3 style="color: white; margin-bottom: 1rem;">Share This Article</h3>

                         <div class="share-buttons">

                             <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                 class="share-btn facebook" target="_blank">
                                 <i class="fab fa-facebook-f"></i>
                             </a>

                             <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                                 class="share-btn twitter" target="_blank">
                                 <i class="fab fa-twitter"></i>
                             </a>

                             <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                                 class="share-btn linkedin" target="_blank">
                                 <i class="fab fa-linkedin-in"></i>
                             </a>

                             <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&media={{ asset('storage/' . $post->image) }}&description={{ urlencode($post->title) }}"
                                 class="share-btn pinterest" target="_blank">
                                 <i class="fab fa-pinterest-p"></i>
                             </a>

                             <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                                 class="share-btn whatsapp" target="_blank">
                                 <i class="fab fa-whatsapp"></i>
                             </a>

                         </div>
                     </div>

                     {{-- <div class="author-section">
                         <div class="author-info">
                             <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name ?? 'Admin') }}&background=667eea&color=fff&size=100"
                                 alt="{{ $post->author->name ?? 'Admin' }}" class="author-avatar">
                             <div class="author-details">
                                 <h4>About the Author</h4>
                                 <p class="author-bio">
                                     {{ $post->author->bio ?? 'Experienced writer and blogger with a passion for sharing knowledge and insights.' }}
                                 </p>
                             </div>
                         </div>
                     </div> --}}
                     <div class="author-section">
                         <div class="author-info">
                             <img src="https://ui-avatars.com/api/?name=Suraj+Magar&background=667eea&color=fff&size=100"
                                 alt="Your Name" class="author-avatar">

                             <div class="author-details">
                                 <h4>About the Author</h4>
                                 <p class="author-bio">
                                     I'm Suraj Magar, a tech enthusiast and content creator who writes about the latest
                                     technology,
                                     gadgets, smartphones, and trending topics with simple and clear explanations.
                                 </p>
                             </div>
                         </div>
                     </div>
                 </article>

                 <!-- Inline Ad - Middle -->
                 <div class="ad-container ad-inline">
                     <div class="ad-label">Sponsored Content</div>
                     <div class="ad-content">
                         <i class="fas fa-bullhorn fa-3x mb-3" style="color: #764ba2;"></i>
                         <p class="ad-text">Promote your business here</p>
                         <a href="#" class="ad-cta">Get Started</a>
                     </div>
                 </div>

                 <section class="related-posts">
                     <h2 class="section-title">Related Articles</h2>
                     <div class="related-grid">
                         @foreach ($relatedPosts as $related)
                             <article class="related-card">
                                 <img src="{{ asset('storage/' . (json_decode($related->file_path ?? '[]')[0] ?? 'uploads/no_image.jpg')) }}"
                                     alt="{{ $related->title }}">
                                 <div class="related-card-content">
                                     <h4>{{ Str::limit($related->title, 60) }}</h4>
                                     <p>{{ Str::limit($related->description, 100) }}</p>
                                     <a href="{{ url('/posts/' . $related->id) }}" class="ad-cta"
                                         style="padding: 0.4rem 1rem; font-size: 0.9rem;">
                                         Related Topic
                                     </a>
                                 </div>
                             </article>
                         @endforeach
                     </div>
                 </section>

                 <!-- Comments Section -->
                 <section class="comments-section">
                     <h2 class="section-title">Comments ({{ 'Comment data here' }})</h2>

                     <!-- Comment Form -->
                     <div class="comment-form">
                         <h3>Leave a Comment</h3>
                         <form id="commentForm">
                             <div class="mb-3">
                                 <input type="text" class="form-control" placeholder="Your Name" required>
                             </div>
                             <div class="mb-3">
                                 <input type="email" class="form-control" placeholder="Your Email" required>
                             </div>
                             <div class="mb-3">
                                 <textarea class="form-control" rows="4" placeholder="Your Comment" required></textarea>
                             </div>
                             <button type="submit" class="ad-cta">Post Comment</button>
                         </form>
                     </div>

                     <!-- Comments List -->
                     <div class="comments-list">
                         @foreach ($comments as $comment)
                             <div class="comment-item" style="border-bottom: 1px solid #e9ecef; padding: 1.5rem 0;">
                                 <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                     <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->name) }}&background=667eea&color=fff&size=40"
                                         alt="{{ $comment->name }}" style="border-radius: 50%; margin-right: 1rem;">
                                     <div>
                                         <strong>{{ $comment->name }}</strong>
                                         <small style="color: #6c757d; margin-left: 1rem;">
                                             {{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}
                                         </small>
                                     </div>
                                 </div>
                                 <p>{{ $comment->content }}</p>
                             </div>
                         @endforeach
                     </div>
                 </section>
             </div>

             <!-- Sidebar with Ads -->
             <aside class="sidebar">
                 <!-- Sidebar Ad 1 -->
                 <div class="ad-container">
                     <div class="ad-label">Advertisement</div>
                     <div class="ad-content">
                         <i class="fas fa-chart-line fa-3x mb-3" style="color: #27ae60;"></i>
                         <p class="ad-text">Boost Your Online Presence</p>
                         <a href="#" class="ad-cta">Get Premium Ad</a>
                     </div>
                 </div>

                 <!-- Newsletter Signup -->
                 <div class="ad-container"
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                     <div class="ad-content">
                         <i class="fas fa-envelope fa-3x mb-3"></i>
                         <h4 style="color: white; margin-bottom: 1rem;">Subscribe to Newsletter</h4>
                         <p style="color: rgba(255,255,255,0.9); margin-bottom: 1rem;">
                             Get the latest articles delivered to your inbox
                         </p>
                         <form style="width: 100%;">
                             <input type="email" placeholder="Your Email"
                                 style="width: 100%; padding: 0.75rem; border: none; border-radius: 5px; margin-bottom: 1rem;">
                             <button type="submit"
                                 style="background: white; color: #667eea; border: none; padding: 0.75rem 1.5rem; border-radius: 25px; font-weight: 600; cursor: pointer;">
                                 Subscribe
                             </button>
                         </form>
                     </div>
                 </div>

                 <!-- Sidebar Ad 2 -->
                 <div class="ad-container">
                     <div class="ad-label">Sponsored</div>
                     <div class="ad-content">
                         <i class="fas fa-shopping-bag fa-3x mb-3" style="color: #e74c3c;"></i>
                         <p class="ad-text">Discover Amazing Products</p>
                         <a href="#" class="ad-cta">Shop Now</a>
                     </div>
                 </div>

                 <!-- Popular Posts -->
                 <div class="ad-container" style="border: none; box-shadow: none;">
                     <h4 style="color: #2c3e50; margin-bottom: 1.5rem;">Popular Posts</h4>
                     @foreach ($popularPosts as $popular)
                         <a href="{{ url('/posts/' . $popular->id) }}"
                             style="display: flex; align-items: center; margin-bottom: 1rem; text-decoration: none; color: #333; padding: 0.75rem; border-radius: 8px; transition: all 0.3s ease;"
                             onmouseover="this.style.background='#f8f9fa'"
                             onmouseout="this.style.background='transparent'">
                             <img src="{{ asset('storage/' . (json_decode($popular->file_path ?? '[]')[0] ?? 'uploads/no_image.jpg')) }}"
                                 alt="{{ $popular->title }}"
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; margin-right: 1rem;">
                             <span>{{ Str::limit($popular->title, 40) }}</span>
                         </a>
                     @endforeach
                 </div>

                 <!-- Sidebar Ad 3 -->
                 <div class="ad-container">
                     <div class="ad-label">Advertisement</div>
                     <div class="ad-content">
                         <i class="fas fa-rocket fa-3x mb-3" style="color: #f39c12;"></i>
                         <p class="ad-text">Grow Your Business</p>
                         <a href="#" class="ad-cta">Start Free Trial</a>
                     </div>
                 </div>
             </aside>
         </div>
     </main>

     <!-- Inline Ad - Bottom -->
     <div class="blog-container">
         <div class="ad-container ad-inline" style="margin: 4rem auto;">
             <div class="ad-label">Advertisement</div>
             <div class="ad-content">
                 <div style="display: flex; align-items: center; gap: 2rem;">
                     <i class="fas fa-star fa-3x" style="color: #ffd700;"></i>
                     <div style="text-align: left;">
                         <h4 style="color: #2c3e50; margin-bottom: 0.5rem;">Premium Advertising Space</h4>
                         <p class="ad-text" style="margin-bottom: 0;">Reach thousands of readers with your message</p>
                     </div>
                 </div>
                 <a href="#" class="ad-cta" style="margin-top: 1.5rem;">Contact Us</a>
             </div>
         </div>
     </div>

     @include('common.footer')

     <script>
         // Reading Progress Bar
         window.addEventListener('scroll', function() {
             const article = document.querySelector('.article-content');
             const totalHeight = article.offsetHeight;
             const windowHeight = window.innerHeight;
             const scrollPosition = window.scrollY;

             const progress = (scrollPosition / (totalHeight - windowHeight)) * 100;
             document.getElementById('readingProgress').style.width = Math.min(progress, 100) + '%';
         });

         // Generate Table of Contents
         document.addEventListener('DOMContentLoaded', function() {
             const contentBody = document.querySelector('.content-body');
             const headings = contentBody.querySelectorAll('h2, h3');
             const tocList = document.getElementById('tocList');

             headings.forEach((heading, index) => {
                 const id = 'heading-' + index;
                 heading.id = id;

                 const listItem = document.createElement('li');
                 listItem.className = 'toc-item';

                 const link = document.createElement('a');
                 link.href = '#' + id;
                 link.className = 'toc-link';
                 link.innerHTML = `<i class="fas fa-chevron-right"></i> ${heading.textContent}`;

                 listItem.appendChild(link);
                 tocList.appendChild(listItem);
             });

             // Smooth scroll for TOC links
             document.querySelectorAll('.toc-link').forEach(link => {
                 link.addEventListener('click', function(e) {
                     e.preventDefault();
                     const targetId = this.getAttribute('href');
                     const targetElement = document.querySelector(targetId);

                     window.scrollTo({
                         top: targetElement.offsetTop - 100,
                         behavior: 'smooth'
                     });
                 });
             });

             // Image gallery lightbox
             document.querySelectorAll('.gallery-image').forEach(img => {
                 img.addEventListener('click', function() {
                     const lightbox = document.createElement('div');
                     lightbox.style.position = 'fixed';
                     lightbox.style.top = '0';
                     lightbox.style.left = '0';
                     lightbox.style.width = '100%';
                     lightbox.style.height = '100%';
                     lightbox.style.background = 'rgba(0,0,0,0.9)';
                     lightbox.style.display = 'flex';
                     lightbox.style.alignItems = 'center';
                     lightbox.style.justifyContent = 'center';
                     lightbox.style.zIndex = '9999';

                     const enlargedImg = document.createElement('img');
                     enlargedImg.src = this.src;
                     enlargedImg.style.maxWidth = '90%';
                     enlargedImg.style.maxHeight = '90%';
                     enlargedImg.style.objectFit = 'contain';

                     lightbox.appendChild(enlargedImg);
                     document.body.appendChild(lightbox);

                     lightbox.addEventListener('click', function() {
                         document.body.removeChild(lightbox);
                     });
                 });
             });

             // Comment form submission
             document.getElementById('commentForm').addEventListener('submit', function(e) {
                 e.preventDefault();
                 alert('Comment submitted! In a real application, this would be processed by your server.');
                 this.reset();
             });
         });

         // Calculate reading time (helper function)
         function calculateReadingTime(text) {
             const wordsPerMinute = 200;
             const words = text.split(/\s+/).length;
             return Math.ceil(words / wordsPerMinute);
         }
     </script>
 </div>
 <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{{ $post->title }}",
  "image": "{{ asset('storage/' . $featuredImage) }}",
  "author": {
    "@type": "Person",
    "name": "{{ $post->author->name ?? 'Admin' }}"
  },
  "datePublished": "{{ $post->created_date }}",
  "description": "{{ Str::limit(strip_tags($post->description), 160) }}"
}
</script>
