<?php
// filepath: includes/controllers/class-product-controller.php

/**
 * Product Controller - Handles product display and interactions
 */
class Simple_Ecommerce_Product_Controller {
    
    /**
     * Initialize the class and set up hooks
     */
    public function __construct() {
        // Register shortcodes
        add_shortcode('simple_products', array($this, 'products_shortcode'));
        add_shortcode('simple_product', array($this, 'product_detail_shortcode'));
        add_shortcode('simple_product_list', array($this, 'products_list_shortcode'));
        add_shortcode('simple_product_grid', array($this,'products_grid_shortcode'));
        // Register AJAX handlers
        add_action('wp_ajax_add_to_cart', array($this, 'ajax_add_to_cart'));
        add_action('wp_ajax_nopriv_add_to_cart', array($this, 'ajax_add_to_cart'));
        
    }
    
    /**
     * Products shortcode - displays a grid of products
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML content
     */
    public function products_shortcode($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts(array(
            'category' => '',
            'limit' => 12,
            'columns' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
            'view' => 'grid', // grid or list
        ), $atts, 'simple_products');
        
        // Query args
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );
        
        // Add category filter if specified
        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_category',
                    'field' => 'slug',
                    'terms' => explode(',', $atts['category']),
                ),
            );
        }
        
        // Get products
        $products = new WP_Query($args);
        
        // Start output buffer
        ob_start();
        
        // Include the appropriate template
        if ($atts['view'] === 'list') {
            include plugin_dir_path(dirname(__FILE__)) . 'views/public/product-list.php';
        } else {
            include plugin_dir_path(dirname(__FILE__)) . 'views/public/product-grid.php';
        }
        
        // Get the buffer content
        return ob_get_clean();
    }
    
    /**
     * Product detail shortcode - displays a single product
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML content
     */
    public function product_detail_shortcode($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts(array(
            'id' => 0,
            'slug' => '',
        ), $atts, 'simple_product');
        
        // Get the product ID
        $product_id = 0;
        
        if ($atts['id'] > 0) {
            $product_id = $atts['id'];
        } elseif (!empty($atts['slug'])) {
            $product = get_page_by_path($atts['slug'], OBJECT, 'product');
            if ($product) {
                $product_id = $product->ID;
            }
        } elseif (is_singular('product')) {
            $product_id = get_the_ID();
        }
        
        if (!$product_id) {
            return '<p>Product not found.</p>';
        }
        
        // Get the product model
        $product_model = new Simple_Ecommerce_Product();
        $product_data = $product_model->get_info($product_id);
        
        if (!$product_data) {
            return '<p>Product not found.</p>';
        }
        
        // Start output buffer
        ob_start();
        
        // Include the template
        include plugin_dir_path(dirname(__FILE__)) . 'views/public/product-detail.php';
        
        // Get the buffer content
        return ob_get_clean();
    }
    function products_list_shortcode($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts(array(
            'category' => '',
            'limit' => 12,
            'orderby' => 'date',
            'order' => 'DESC',
        ), $atts, 'simple_product_list');
        
        // Query args
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );
        
        // Add category filter if specified
        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_category',
                    'field' => 'slug',
                    'terms' => explode(',', $atts['category']),
                ),
            );
        }
        
        // Get products
        $products = new WP_Query($args);
        
        // Start output buffer
        ob_start();
        
        // Include the template
        include plugin_dir_path(dirname(__FILE__)) . 'views/public/product-list.php';
        
        // Get the buffer content
        return ob_get_clean();
    }
    function products_grid_shortcode($atts) {
         $atts = shortcode_atts(array(
        'category' => '',
        'limit' => 12,
        'columns' => 3,
        'orderby' => 'date',
        'order' => 'DESC',
    ), $atts, 'simple_product_grid');

        
        $paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'paged' => $paged,
        );
        
        // Add category filter if specified
        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_category',
                    'field' => 'slug',
                    'terms' => explode(',', $atts['category']),
                ),
            );
        }
        
        // Get products
        $products = new WP_Query($args);
        
        // Start output buffer
        ob_start();
        
        // Include the template
        include plugin_dir_path(dirname(__FILE__)) . 'views/public/product-grid.php';
        
        // Get the buffer content
        return ob_get_clean();
    }
    /**
     * AJAX handler for adding products to cart
     */
    public function ajax_add_to_cart() {
        // Check nonce for security
        check_ajax_referer('simple_ecommerce_nonce', 'nonce');
        
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($product_id <= 0) {
            wp_send_json_error(array('message' => 'Invalid product ID'));
            return;
        }
        
        // Get the cart
        $cart = new Simple_Ecommerce_Cart();
        $result = $cart->add_item($product_id, $quantity);
        
        if ($result) {
            // Get updated cart data
            $cart_data = $cart->get_cart();
            
            wp_send_json_success(array(
                'message' => 'Product added to cart successfully.',
                'cart_total' => $cart_data['total'],
                'cart_count' => $cart_data['count'],
                'product_id' => $product_id,
                'quantity' => $quantity,
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to add product to cart.'));
        }
        
        wp_die();
    }
    
}