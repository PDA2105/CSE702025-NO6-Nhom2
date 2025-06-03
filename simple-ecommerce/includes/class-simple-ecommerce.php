<?php
// filepath: includes/class-simple-ecommerce.php

class Simple_Ecommerce {
    
    private function load_dependencies() {
    // Load models
    require_once SIMPLE_ECOMMERCE_DIR . 'includes/models/class-product.php';
    require_once SIMPLE_ECOMMERCE_DIR . 'includes/models/class-category.php';
    require_once SIMPLE_ECOMMERCE_DIR . 'includes/models/class-cart.php';
    require_once SIMPLE_ECOMMERCE_DIR . 'includes/models/class-order.php';
    
    // Load controllers
    require_once SIMPLE_ECOMMERCE_DIR . 'includes/controllers/class-product-controller.php';
    require_once SIMPLE_ECOMMERCE_DIR . 'includes/controllers/class-cart-controller.php';
    require_once SIMPLE_ECOMMERCE_DIR . 'includes/controllers/class-order-controller.php';
    require_once SIMPLE_ECOMMERCE_DIR . 'includes/controllers/class-user-controller.php';
    
    // Load admin meta boxes
    require_once SIMPLE_ECOMMERCE_DIR . 'includes/admin/product-metabox.php';
    require_once SIMPLE_ECOMMERCE_DIR . 'includes/admin/order-metabox.php';
    //Load widgets
    
    // Load helpers
    require_once SIMPLE_ECOMMERCE_DIR . 'includes/class-simple-ecommerce-helpers.php';
}

// Inside your run() method, add:
public function run() {
    // Load dependencies
    $this->load_dependencies();
    
    // Initialize models
    $product = new Simple_Ecommerce_Product();
    $category = new Simple_Ecommerce_Category();
    $order = new Simple_Ecommerce_Order();
    
    // Initialize controllers
    $product_controller = new Simple_Ecommerce_Product_Controller();
    $cart_controller = new Simple_Ecommerce_Cart_Controller();
    $order_controller = new Simple_Ecommerce_Order_Controller();
    $user_controller = new Simple_Ecommerce_User_Controller();
    
    // Initialize admin meta boxes and enqueue admin assets
    if (is_admin()) {
        $product_meta_box = new Simple_Ecommerce_Product_Meta_Box();
        $order_meta_box = new Simple_Ecommerce_Order_Meta_Box();
        
        // Register admin assets
        add_action('admin_enqueue_scripts', array($this, 'register_admin_assets'));
    }
    
    // Register frontend assets
    add_action('wp_enqueue_scripts', array($this, 'register_assets'));
}
   
    /**
     * Register and enqueue admin scripts and styles
     */
    public function register_admin_assets() {
        wp_enqueue_style(
            'simple-ecommerce-admin',
            SIMPLE_ECOMMERCE_URL . 'admin/css/simple-ecommerce-admin.css',
            array(),
            SIMPLE_ECOMMERCE_VERSION
        );
        
        wp_enqueue_script(
            'simple-ecommerce-admin',
            SIMPLE_ECOMMERCE_URL . 'admin/js/simple-ecommerce-admin.js',
            array('jquery'),
            SIMPLE_ECOMMERCE_VERSION,
            true
        );
    }
    public function register_assets() {
        // Register and enqueue CSS
        wp_enqueue_style(
            'dashicons'
        );
        
        wp_enqueue_style(
            'simple-ecommerce',
            SIMPLE_ECOMMERCE_URL . 'public/css/simple-ecommerce.css',
            array(),
            SIMPLE_ECOMMERCE_VERSION
        );
        wp_enqueue_style(
            'simple-ecommerce-product',
            SIMPLE_ECOMMERCE_URL . 'public/css/simple-ecommerce-product.css',
            array('simple-ecommerce'),
            filemtime(SIMPLE_ECOMMERCE_DIR . 'public/css/simple-ecommerce-product.css') // version theo thời gian sửa file
        );
        wp_enqueue_style(
        'simple-ecommerce-mini-cart',
        SIMPLE_ECOMMERCE_URL . 'public/css/simple-ecommerce-mini-cart.css',
        array('simple-ecommerce'),
        SIMPLE_ECOMMERCE_VERSION
        );
        
        // Register and enqueue main JS
        wp_enqueue_script(
            'simple-ecommerce',
            SIMPLE_ECOMMERCE_URL . 'public/js/simple-ecommerce.js',
            array('jquery'),
            SIMPLE_ECOMMERCE_VERSION,
            true
        );
        
        wp_enqueue_script(
            'simple-ecommerce-product',
            SIMPLE_ECOMMERCE_URL . 'public/js/simple-ecommerce-product.js',
            array('jquery', 'simple-ecommerce'),
            SIMPLE_ECOMMERCE_VERSION,
            true
        );
        
        // Localize script data
        wp_localize_script(
            'simple-ecommerce',
            'simple_ecommerce',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('simple_ecommerce_nonce'),
                'cart_url' => home_url('/cart/')
            )
        );
}
    
    public static function activate() {
    // Create necessary pages
    $pages = array(
        'cart' => array(
            'title' => 'Cart',
            'content' => '[simple_cart]',
        ),
        'checkout' => array(
            'title' => 'Checkout',
            'content' => '[simple_checkout]',
        ),
        'thank-you' => array(
            'title' => 'Thank You',
            'content' => '[simple_thank_you]',
        ),
        'login' => array(
            'title' => 'Login',
            'content' => '[simple_login]',
        ),
        'register' => array(
            'title' => 'Register',
            'content' => '[simple_register]',
        ),
        'my-account' => array(
            'title' => 'My Account',
            'content' => '[simple_my_account]',
        ),
    );
    
    foreach ($pages as $slug => $page) {
        // Check if page exists
        $page_exists = get_page_by_path($slug);
        
        if (!$page_exists) {
            $page_data = array(
                'post_title' => $page['title'],
                'post_content' => $page['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug,
            );
            
            wp_insert_post($page_data);
        }
    }
    
    // Register customer role
    $user_controller = new Simple_Ecommerce_User_Controller();
    $user_controller->register_customer_role();
    
    // Register post types
    $product = new Simple_Ecommerce_Product();
    $product->register_product_post_type();
    
    $category = new Simple_Ecommerce_Category();
    $category->register_product_category_taxonomy();
    
    $order = new Simple_Ecommerce_Order();
    $order->register_order_post_type();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
    public static function deactivate() {
        flush_rewrite_rules();
    }
}
