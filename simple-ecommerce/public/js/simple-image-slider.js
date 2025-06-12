// Simple Image Slider JavaScript với hiệu ứng trượt
// Namespace để tránh xung đột với slider khác
const SimpleImageSlider = {
    sliders: {},
    autoplayIntervals: {},
    
    // Initialize slider when document is ready
    init: function() {
        this.initializeSliders();
        this.addSmoothTransitions();
        this.bindKeyboardEvents();
    }
};

SimpleImageSlider.initializeSliders = function() {
    const sliders = document.querySelectorAll('.simple-image-slider');
    
    sliders.forEach((slider) => {
        const sliderId = slider.id;
        this.sliders[sliderId] = { currentIndex: 0 };
        
        // Initialize first slide position
        this.updateSliderPosition(sliderId);
        
        // Setup autoplay if enabled
        const autoplay = slider.dataset.autoplay === 'true';
        const speed = parseInt(slider.dataset.speed) || 3000;
        
        if (autoplay) {
            this.autoplayIntervals[sliderId] = setInterval(() => {
                this.moveSlide(1, sliderId);
            }, speed);
            
            // Pause on hover
            slider.addEventListener('mouseenter', () => {
                clearInterval(this.autoplayIntervals[sliderId]);
            });
            
            // Resume on mouse leave
            slider.addEventListener('mouseleave', () => {
                this.autoplayIntervals[sliderId] = setInterval(() => {
                    this.moveSlide(1, sliderId);
                }, speed);
            });
        }
        
        // Add touch/swipe support for mobile
        this.addTouchSupport(slider);
    });
};

SimpleImageSlider.moveSlide = function(direction, targetSliderId = null) {
    const sliderId = targetSliderId || this.getCurrentSliderId();
    if (!sliderId || !this.sliders[sliderId]) return;
    
    const slider = document.getElementById(sliderId);
    const slides = slider.querySelectorAll('.slide');
    const totalSlides = slides.length;
    
    if (totalSlides === 0) return;
    
    // Calculate new index
    this.sliders[sliderId].currentIndex += direction;
    
    if (this.sliders[sliderId].currentIndex >= totalSlides) {
        this.sliders[sliderId].currentIndex = 0;
    } else if (this.sliders[sliderId].currentIndex < 0) {
        this.sliders[sliderId].currentIndex = totalSlides - 1;
    }
    
    // Update slider position
    this.updateSliderPosition(sliderId);
    
    // Update dots
    this.updateDots(sliderId);
};

SimpleImageSlider.goToSlide = function(slideIndex, targetSliderId = null) {
    const sliderId = targetSliderId || this.getCurrentSliderId();
    if (!sliderId || !this.sliders[sliderId]) return;
    
    const slider = document.getElementById(sliderId);
    const slides = slider.querySelectorAll('.slide');
    const totalSlides = slides.length;
    
    if (slideIndex < 0 || slideIndex >= totalSlides) return;
    
    this.sliders[sliderId].currentIndex = slideIndex;
    
    // Update slider position
    this.updateSliderPosition(sliderId);
    
    // Update dots
    this.updateDots(sliderId);
};

SimpleImageSlider.updateSliderPosition = function(sliderId) {
    const slider = document.getElementById(sliderId);
    if (!slider || !this.sliders[sliderId]) return;
    
    const slidesWrapper = slider.querySelector('.slides-wrapper');
    if (!slidesWrapper) return;
    
    const translateX = -this.sliders[sliderId].currentIndex * 100;
    slidesWrapper.style.transform = `translateX(${translateX}%)`;
};

SimpleImageSlider.getCurrentSliderId = function() {
    const activeSlider = document.querySelector('.simple-image-slider');
    return activeSlider ? activeSlider.id : null;
};

SimpleImageSlider.updateDots = function(sliderId) {
    const slider = document.getElementById(sliderId);
    if (!slider || !this.sliders[sliderId]) return;
    
    const dots = slider.querySelectorAll('.dot');
    
    dots.forEach((dot, index) => {
        if (index === this.sliders[sliderId].currentIndex) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
};

SimpleImageSlider.addTouchSupport = function(slider) {
    let startX = 0;
    let endX = 0;
    let isMoving = false;
    
    slider.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        isMoving = false;
    });
    
    slider.addEventListener('touchmove', (e) => {
        if (!isMoving) {
            isMoving = true;
        }
    });
    
    slider.addEventListener('touchend', (e) => {
        if (!isMoving) return;
        
        endX = e.changedTouches[0].clientX;
        this.handleSwipe(slider.id, startX, endX);
    });
};

SimpleImageSlider.handleSwipe = function(sliderId, startX, endX) {
    const threshold = 50; // Minimum distance for swipe
    const diff = startX - endX;
    
    if (Math.abs(diff) > threshold) {
        if (diff > 0) {
            // Swipe left - next slide
            this.moveSlide(1, sliderId);
        } else {
            // Swipe right - previous slide
            this.moveSlide(-1, sliderId);
        }
    }
};

// Keyboard navigation - chỉ cho shortcode sliders
SimpleImageSlider.bindKeyboardEvents = function() {
    document.addEventListener('keydown', (e) => {
        const activeSlider = document.querySelector('.simple-image-slider:hover');
        if (!activeSlider) return;
        
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            this.moveSlide(-1, activeSlider.id);
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            this.moveSlide(1, activeSlider.id);
        }
    });
};

// Add smooth scroll behavior for better UX
SimpleImageSlider.addSmoothTransitions = function() {
    const slidersWrappers = document.querySelectorAll('.simple-image-slider .slides-wrapper');
    slidersWrappers.forEach((wrapper) => {
        wrapper.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    });
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    SimpleImageSlider.init();
});

// Global functions for backwards compatibility (nếu shortcode cần)
function moveSlide(direction, sliderId) {
    if (typeof SimpleImageSlider !== 'undefined') {
        SimpleImageSlider.moveSlide(direction, sliderId);
    }
}

function goToSlide(slideIndex, sliderId) {
    if (typeof SimpleImageSlider !== 'undefined') {
        SimpleImageSlider.goToSlide(slideIndex, sliderId);
    }
}
