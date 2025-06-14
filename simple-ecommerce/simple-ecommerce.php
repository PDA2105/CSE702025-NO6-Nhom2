<?php
/**
 * @package SimpleEcommerce
 */
/*
Plugin Name: Simple Ecommerce
Description: Minimum viable product for an ecommerce plugin.
Version: 1.0.0
Author: ttung
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

