<?php
// Kế thừa style từ GeneratePress
add_action('wp_enqueue_scripts', 'gp_child_enqueue_styles');
function gp_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
});
function enqueue_swiper_assets() {
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_swiper_assets');
function custom_homepage_assets() {
    if (is_front_page()) {
        wp_enqueue_style('custom-slider-style', get_stylesheet_directory_uri() . '/slider.css');
        wp_enqueue_script('custom-slider-script', get_stylesheet_directory_uri() . '/slider.js', array(), null, true);
    }
}
add_action('wp_enqueue_scripts', 'custom_homepage_assets');






