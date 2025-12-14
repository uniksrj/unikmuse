import $ from 'jquery';

const slide = document.querySelector('.carousel-slide');
const caption = slide.querySelector('.carousel-caption');

const images = document.querySelectorAll('img[loading="lazy"]');
function showSlide(index) {
    slide.querySelector('img').setAttribute('src', slides[index].image);
    caption.querySelector('h3').textContent = slides[index].title;
    caption.querySelector('p').textContent = slides[index].text;
}

let currentSlide = 0;
const slides = [{
    image: "https://images.unsplash.com/photo-1526666923127-b2970f64b422?q=80&w=1472&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
    title: "SateLite View in sunrise",
    text: "Tech is best because it shows us the world in a new way."
},
{
    image: "https://images.unsplash.com/photo-1764555166588-b3cbd7adafae?q=80&w=1632&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
    title: "Sun Makes You Feel Great",
    text: "Jobs fill your pocket, but adventures fill your soul."
},
{
    image: "https://images.unsplash.com/photo-1764957078546-35495d3d2cbb?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
    title: "Peace Finds You",
    text: "Twenty years from now you will be more disappointed by the things that you didn't do than by the ones you did do."
}
];


$(document).ready(function () {

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        });
        images.forEach(img => imageObserver.observe(img));
    }

    setInterval(() => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);

    }, 5000);

    $('.loader-overlay').fadeIn();

    setTimeout(function () {
        $('.loader-overlay').fadeOut(1500);
    }, 5000);
    $(window).on('beforeunload', function () {
        $('.loader-overlay').show();
    });

    $(window).on('load', function () {
        console.log("Window loaded, stopping loader...");
        setTimeout(function () {
            $('.loader-overlay').fadeOut(1500);
        }, 500);
    });

    $('#loadMore').click(function () {
        $(this).html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

        setTimeout(() => {
            $(this).hide();
        }, 1000);
    });

    $(document).on('click', '#dropdown-cat', function (e) {
        console.log(window.innerWidth);
        if (window.innerWidth <= 768) {
            e.preventDefault();
            $(this).next('.dropdown-menu_category').slideToggle(300);
            $(this).toggleClass("show");
        }
    });
});
