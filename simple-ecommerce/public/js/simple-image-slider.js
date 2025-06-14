
/**
 * SE Banner Slider JavaScript
 * Modern slide transitions with smooth touch/swipe support
 * Unique namespace 'SEBannerSlider' to avoid conflicts
 * 
 * Features:
 * - Smooth slide transitions
 * - Auto-play with pause on hover
 * - Touch/swipe support for mobile
 * - Keyboard navigation
 * - Dots and arrow navigation
 * - Responsive design
 */

(function($) {
    'use strict';
    
    // Namespace to avoid conflicts
    window.SEBannerSlider = window.SEBannerSlider || {};
    
    /**
     * SE Banner Slider Class
     */
    class SEBannerSlider {
        constructor(element) {
            this.slider = $(element);
            this.slides = this.slider.find('.se-banner-slide');
            this.dots = this.slider.find('.se-banner-dot');
            this.prevBtn = this.slider.find('.se-banner-prev');
            this.nextBtn = this.slider.find('.se-banner-next');
            
            // Settings from data attributes
            this.settings = {
                autoplay: this.slider.data('autoplay') !== 'false',
                autoplaySpeed: parseInt(this.slider.data('autoplay-speed')) || 5000,
                transitionSpeed: parseInt(this.slider.data('transition-speed')) || 500,
                currentSlide: 0,
                totalSlides: this.slides.length,
                isTransitioning: false,
                autoplayTimer: null,
                touchStartX: 0,
                touchEndX: 0,
                touchThreshold: 50
            };
            
            // Only initialize if we have multiple slides
            if (this.settings.totalSlides > 1) {
                this.init();
            }
        }
        
        /**
         * Initialize the slider
         */
        init() {
            this.bindEvents();
            this.startAutoplay();
            this.preloadImages();
            
            // Set initial state
            this.updateSlidePosition(0, false);
            
            console.log('SE Banner Slider initialized with', this.settings.totalSlides, 'slides');
        }
        
        /**
         * Bind all events
         */
        bindEvents() {
            const self = this;
            
            // Arrow navigation
            this.prevBtn.on('click', () => this.previousSlide());
            this.nextBtn.on('click', () => this.nextSlide());
            
            // Dots navigation
            this.dots.on('click', function() {
                const slideIndex = parseInt($(this).data('slide'));
                self.goToSlide(slideIndex);
            });
            
            // Pause autoplay on hover
            this.slider.on('mouseenter', () => this.pauseAutoplay());
            this.slider.on('mouseleave', () => this.resumeAutoplay());
            
            // Touch events for swipe
            this.slider.on('touchstart', (e) => this.handleTouchStart(e));
            this.slider.on('touchmove', (e) => this.handleTouchMove(e));
            this.slider.on('touchend', (e) => this.handleTouchEnd(e));
            
            // Keyboard navigation
            $(document).on('keydown', (e) => this.handleKeydown(e));
            
            // Focus events for accessibility
            this.slider.on('focus', () => this.pauseAutoplay());
            this.slider.on('blur', () => this.resumeAutoplay());
            
            // Window resize
            $(window).on('resize', () => this.handleResize());
        }
        
        /**
         * Go to next slide
         */
        nextSlide() {
            if (this.settings.isTransitioning) return;
            
            const nextIndex = (this.settings.currentSlide + 1) % this.settings.totalSlides;
            this.goToSlide(nextIndex);
        }
        
        /**
         * Go to previous slide
         */
        previousSlide() {
            if (this.settings.isTransitioning) return;
            
            const prevIndex = this.settings.currentSlide === 0 
                ? this.settings.totalSlides - 1 
                : this.settings.currentSlide - 1;
            this.goToSlide(prevIndex);
        }
        
        /**
         * Go to specific slide
         */
        goToSlide(index, useTransition = true) {
            if (this.settings.isTransitioning || index === this.settings.currentSlide) return;
            
            const direction = index > this.settings.currentSlide ? 'next' : 'prev';
            this.updateSlidePosition(index, useTransition, direction);
            this.settings.currentSlide = index;
            this.restartAutoplay();
        }
        
        /**
         * Update slide position with smooth transition
         */
        updateSlidePosition(newIndex, useTransition = true, direction = 'next') {
            if (useTransition) {
                this.settings.isTransitioning = true;
            }
            
            const currentSlide = this.slides.eq(this.settings.currentSlide);
            const newSlide = this.slides.eq(newIndex);
            
            // Remove all classes
            this.slides.removeClass('se-banner-active se-banner-prev se-banner-next');
            
            if (useTransition) {
                // Set up the new slide position
                if (direction === 'next') {
                    newSlide.addClass('se-banner-next');
                } else {
                    newSlide.addClass('se-banner-prev');
                }
                
                // Force reflow
                newSlide[0].offsetHeight;
                
                // Animate to active position
                setTimeout(() => {
                    currentSlide.addClass(direction === 'next' ? 'se-banner-prev' : 'se-banner-next');
                    newSlide.removeClass('se-banner-prev se-banner-next').addClass('se-banner-active');
                }, 10);
                
                // Clear transition state
                setTimeout(() => {
                    this.settings.isTransitioning = false;
                }, this.settings.transitionSpeed);
            } else {
                // Instant change (for initialization)
                newSlide.addClass('se-banner-active');
            }
            
            // Update dots
            this.updateDots(newIndex);
            
            // Update ARIA attributes
            this.updateAria(newIndex);
        }
        
        /**
         * Update dots active state
         */
        updateDots(activeIndex) {
            this.dots.removeClass('se-banner-active');
            this.dots.eq(activeIndex).addClass('se-banner-active');
        }
        
        /**
         * Update ARIA attributes for accessibility
         */
        updateAria(activeIndex) {
            this.slides.attr('aria-hidden', 'true');
            this.slides.eq(activeIndex).attr('aria-hidden', 'false');
            
            this.dots.attr('aria-pressed', 'false');
            this.dots.eq(activeIndex).attr('aria-pressed', 'true');
        }
        
        /**
         * Start autoplay
         */
        startAutoplay() {
            if (this.settings.autoplay && this.settings.totalSlides > 1) {
                this.settings.autoplayTimer = setInterval(() => {
                    this.nextSlide();
                }, this.settings.autoplaySpeed);
            }
        }
        
        /**
         * Pause autoplay
         */
        pauseAutoplay() {
            if (this.settings.autoplayTimer) {
                clearInterval(this.settings.autoplayTimer);
                this.settings.autoplayTimer = null;
            }
        }
        
        /**
         * Resume autoplay
         */
        resumeAutoplay() {
            if (this.settings.autoplay && !this.settings.autoplayTimer) {
                this.startAutoplay();
            }
        }
        
        /**
         * Restart autoplay (reset timer)
         */
        restartAutoplay() {
            this.pauseAutoplay();
            this.resumeAutoplay();
        }
        
        /**
         * Handle touch start
         */
        handleTouchStart(e) {
            this.settings.touchStartX = e.originalEvent.touches[0].clientX;
            this.pauseAutoplay();
        }
        
        /**
         * Handle touch move (prevent default scrolling during swipe)
         */
        handleTouchMove(e) {
            if (Math.abs(e.originalEvent.touches[0].clientX - this.settings.touchStartX) > 10) {
                e.preventDefault();
            }
        }
        
        /**
         * Handle touch end
         */
        handleTouchEnd(e) {
            this.settings.touchEndX = e.originalEvent.changedTouches[0].clientX;
            this.handleSwipe();
            this.resumeAutoplay();
        }
        
        /**
         * Handle swipe gesture
         */
        handleSwipe() {
            const swipeDistance = this.settings.touchStartX - this.settings.touchEndX;
            
            if (Math.abs(swipeDistance) > this.settings.touchThreshold) {
                if (swipeDistance > 0) {
                    // Swiped left - next slide
                    this.nextSlide();
                } else {
                    // Swiped right - previous slide
                    this.previousSlide();
                }
            }
        }
        
        /**
         * Handle keyboard navigation
         */
        handleKeydown(e) {
            // Only handle if slider is focused or contains focused element
            if (!this.slider.is(':focus') && !this.slider.has(':focus').length) {
                return;
            }
            
            switch(e.keyCode) {
                case 37: // Left arrow
                    e.preventDefault();
                    this.previousSlide();
                    break;
                case 39: // Right arrow
                    e.preventDefault();
                    this.nextSlide();
                    break;
                case 32: // Spacebar
                    e.preventDefault();
                    if (this.settings.autoplayTimer) {
                        this.pauseAutoplay();
                    } else {
                        this.resumeAutoplay();
                    }
                    break;
            }
        }
        
        /**
         * Handle window resize
         */
        handleResize() {
            // Debounce resize handling
            clearTimeout(this.resizeTimer);
            this.resizeTimer = setTimeout(() => {
                // Reset any transition states
                this.settings.isTransitioning = false;
                this.updateSlidePosition(this.settings.currentSlide, false);
            }, 100);
        }
        
        /**
         * Preload images for smoother transitions
         */
        preloadImages() {
            this.slides.each(function() {
                const img = $(this).find('img');
                const imgSrc = img.attr('src');
                if (imgSrc) {
                    const preloadImg = new Image();
                    preloadImg.src = imgSrc;
                }
            });
        }
        
        /**
         * Destroy slider (cleanup)
         */
        destroy() {
            this.pauseAutoplay();
            this.slider.off();
            $(document).off('keydown');
            $(window).off('resize');
            this.slides.removeClass('se-banner-active se-banner-prev se-banner-next');
            this.dots.removeClass('se-banner-active');
        }
    }
    
    /**
     * Initialize all sliders on page
     */
    function initializeSliders() {
        $('.se-banner-slider').each(function() {
            const sliderId = $(this).attr('id');
            if (sliderId && !window.SEBannerSlider[sliderId]) {
                window.SEBannerSlider[sliderId] = new SEBannerSlider(this);
            }
        });
    }
    
    /**
     * Auto-initialize when DOM is ready
     */
    $(document).ready(function() {
        initializeSliders();
    });
    
    /**
     * Re-initialize on AJAX content load (for dynamic content)
     */
    $(document).on('se-banner-slider-init', function() {
        initializeSliders();
    });
    
    /**
     * Pause all sliders when page becomes hidden
     */
    $(document).on('visibilitychange', function() {
        if (document.hidden) {
            $('.se-banner-slider').each(function() {
                const sliderId = $(this).attr('id');
                if (window.SEBannerSlider[sliderId]) {
                    window.SEBannerSlider[sliderId].pauseAutoplay();
                }
            });
        } else {
            $('.se-banner-slider').each(function() {
                const sliderId = $(this).attr('id');
                if (window.SEBannerSlider[sliderId]) {
                    window.SEBannerSlider[sliderId].resumeAutoplay();
                }
            });
        }
    });
    
})(jQuery);

/**
 * USAGE INSTRUCTIONS:
 * 
 * 1. Basic shortcode:
 *    [se_banner_slider image_ids="123,456,789"]
 * 
 * 2. Custom settings:
 *    [se_banner_slider image_ids="123,456" width="1000" height="300" autoplay="true" autoplay_speed="3000"]
 * 
 * 3. Manual control (JavaScript):
 *    // Get slider instance
 *    const slider = window.SEBannerSlider['se-banner-slider-1'];
 *    
 *    // Control methods
 *    slider.nextSlide();
 *    slider.previousSlide();
 *    slider.goToSlide(2);
 *    slider.pauseAutoplay();
 *    slider.resumeAutoplay();
 * 
 * 4. Events:
 *    // Trigger re-initialization after AJAX content load
 *    $(document).trigger('se-banner-slider-init');
 */
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
