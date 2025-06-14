let currentSlideIndex = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.nav-dot');
const totalSlides = slides.length;

function showSlide(index) {
    const slider = document.getElementById('slider');
    slider.style.transform = `translateX(-${index * 100}%)`;
    
    // Update active dot
    dots.forEach(dot => dot.classList.remove('active'));
    dots[index].classList.add('active');
}

function changeSlide(direction) {
    currentSlideIndex += direction;
    if (currentSlideIndex >= totalSlides) {
        currentSlideIndex = 0;
    } else if (currentSlideIndex < 0) {
        currentSlideIndex = totalSlides - 1;
    }
    showSlide(currentSlideIndex);
}

function currentSlide(index) {
    currentSlideIndex = index - 1;
    showSlide(currentSlideIndex);
}

// Auto-slide functionality
function autoSlide() {
    changeSlide(1);
}

// Start auto-slide
let slideInterval = setInterval(autoSlide, 5000);

// Pause auto-slide on hover
const sliderWrapper = document.querySelector('.slider-wrapper');
sliderWrapper.addEventListener('mouseenter', () => {
    clearInterval(slideInterval);
});

sliderWrapper.addEventListener('mouseleave', () => {
    slideInterval = setInterval(autoSlide, 5000);
});

// Touch/swipe support for mobile
let startX = 0;
let endX = 0;

sliderWrapper.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
});

sliderWrapper.addEventListener('touchend', (e) => {
    endX = e.changedTouches[0].clientX;
    handleSwipe();
});

function handleSwipe() {
    const threshold = 50;
    const diff = startX - endX;
    
    if (Math.abs(diff) > threshold) {
        if (diff > 0) {
            changeSlide(1); // Swipe left - next slide
        } else {
            changeSlide(-1); // Swipe right - previous slide
        }
    }
}

// Initialize slider when page loads
document.addEventListener('DOMContentLoaded', () => {
    showSlide(0);
});