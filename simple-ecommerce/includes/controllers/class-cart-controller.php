<?php
// filepath: includes/controllers/class-cart-controller.php

class Simple_Ecommerce_Cart_Controller {
    
    private $cart;
    
    public function __construct() {
        $this->cart = new Simple_Ecommerce_Cart();
        
        // Cart actions
        add_action('wp_ajax_add_to_cart', array($this, 'ajax_add_to_cart'));
        add_action('wp_ajax_nopriv_add_to_cart', array($this, 'ajax_add_to_cart'));
        
        add_action('wp_ajax_remove_from_cart', array($this, 'ajax_remove_from_cart'));
        add_action('wp_ajax_nopriv_remove_from_cart', array($this, 'ajax_remove_from_cart'));
        
        add_action('wp_ajax_update_cart', array($this, 'ajax_update_cart'));
        add_action('wp_ajax_nopriv_update_cart', array($this, 'ajax_update_cart'));
        
        // Shortcodes
        add_shortcode('simple_cart', array($this, 'cart_shortcode'));
        add_shortcode('simple_checkout', array($this, 'checkout_shortcode'));
    }
    
    public function ajax_add_to_cart() {
        check_ajax_referer('simple_ecommerce_nonce', 'nonce');
        
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($product_id > 0) {
            $result = $this->cart->add_item($product_id, $quantity);
            wp_send_json_success(array('success' => $result));
        } else {
            wp_send_json_error(array('message' => 'Invalid product ID'));
        }
    }
    
    public function ajax_remove_from_cart() {
        check_ajax_referer('simple_ecommerce_nonce', 'nonce');
        
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        
        if ($product_id > 0) {
            $result = $this->cart->remove_item($product_id);
            wp_send_json_success(array('success' => $result));
        } else {
            wp_send_json_error(array('message' => 'Invalid product ID'));
        }
    }
    
    public function ajax_update_cart() {
        check_ajax_referer('simple_ecommerce_nonce', 'nonce');
        
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        if ($product_id > 0) {
            $result = $this->cart->update_quantity($product_id, $quantity);
            wp_send_json_success(array('success' => $result));
        } else {
            wp_send_json_error(array('message' => 'Invalid product ID'));
        }
    }
    
    public function cart_shortcode() {
        ob_start();
        include_once plugin_dir_path(dirname(__FILE__)) . 'views/public/cart.php';
        return ob_get_clean();
    }
    
    public function checkout_shortcode() {
        // Process checkout if form submitted
        if (isset($_POST['simple_checkout_submit'])) {
            $user_data = array(
                'name' => sanitize_text_field($_POST['customer_name']),
                'email' => sanitize_email($_POST['customer_email']),
                'shipping_address' => sanitize_textarea_field($_POST['shipping_address']),
            );
            
            $order_id = $this->cart->checkout($user_data);
            
            if ($order_id) {
                // Sử dụng JavaScript để redirect sau khi xuất HTML
            echo '<script>window.location.href="' . esc_url(add_query_arg('order_id', $order_id, home_url('/thank-you/'))) . '";</script>';
            return '';
    }
        }
        
        ob_start();
        include_once plugin_dir_path(dirname(__FILE__)) . 'views/public/checkout.php';
        return ob_get_clean();
    }
}