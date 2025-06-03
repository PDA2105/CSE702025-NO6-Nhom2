<?php
/**
 * @package SimpleEcommerce
 */
/*
Plugin Name: Simple Ecommerce
Plugin URI: https://example.com/simple-ecommerce
Description: A simple e-commerce plugin for WordPress with custom post types and taxonomies
Version: 1.0.0
Author: Your Name
Author URI: https://example.com
Text Domain: simple-ecommerce
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Start session if not already started
if (!session_id()) {
    session_start();
}

// Plugin constants
define('SIMPLE_ECOMMERCE_VERSION', '1.0.0');
define('SIMPLE_ECOMMERCE_DIR', plugin_dir_path(__FILE__));
define('SIMPLE_ECOMMERCE_URL', plugin_dir_url(__FILE__));

// Include main plugin class
require_once SIMPLE_ECOMMERCE_DIR . 'includes/class-simple-ecommerce.php';

// Activation hook
register_activation_hook(__FILE__, array('Simple_Ecommerce', 'activate'));
// Deactivation hook
register_deactivation_hook(__FILE__, array('Simple_Ecommerce', 'deactivate'));

// Initialize the plugin
function run_simple_ecommerce() {
    $plugin = new Simple_Ecommerce();
    $plugin->run();
}
run_simple_ecommerce();

// Đảm bảo CSS/JS plugin luôn được nhúng trên frontend
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('simple-ecommerce', plugins_url('public/css/simple-ecommerce.css', dirname(__FILE__)), array(), null);
    wp_enqueue_style('simple-ecommerce-product', plugins_url('public/css/simple-ecommerce-product.css', dirname(__FILE__)), array(), null);
    wp_enqueue_script('simple-ecommerce', plugins_url('public/js/simple-ecommerce.js', dirname(__FILE__)), array('jquery'), null, true);
    wp_enqueue_script('simple-ecommerce-product', plugins_url('public/js/simple-ecommerce-product.js', dirname(__FILE__)), array('jquery'), null, true);
});

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script(
        'simple-ecommerce',
        plugins_url('public/js/simple-ecommerce.js', __FILE__),
        array('jquery'),
        null,
        true
    );

    wp_localize_script('simple-ecommerce', 'simple_ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('remove-from-cart')
    ]);
});

