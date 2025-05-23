<?php
// filepath: includes/controllers/class-order-controller.php

class Simple_Ecommerce_Order_Controller {
    
    private $order;
    
    public function __construct() {
        $this->order = new Simple_Ecommerce_Order();
        
        // Shortcodes
        add_shortcode('simple_thank_you', array($this, 'thank_you_shortcode'));
        add_shortcode('simple_orders', array($this, 'orders_shortcode'));
    }
    
    public function thank_you_shortcode() {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        
        if ($order_id > 0) {
            $order_details = $this->order->get_order_details($order_id);
            
            if ($order_details) {
                ob_start();
                include_once plugin_dir_path(dirname(__FILE__)) . 'views/public/thank-you.php';
                return ob_get_clean();
            }
        }
        
        return '<p>Invalid order information.</p>';
    }
    
    public function orders_shortcode() {
        if (!is_user_logged_in()) {
            return '<p>Please log in to view your orders.</p>';
        }
        
        $user_id = get_current_user_id();
        $orders = $this->order->get_user_orders($user_id);
        
        ob_start();
        include_once plugin_dir_path(dirname(__FILE__)) . 'views/public/orders.php';
        return ob_get_clean();
    }
    public function get_user_orders($user_id) {
    // Make sure we're using the Order model (not controller)
    $order_model = new Simple_Ecommerce_Order();
    
    $args = array(
        'post_type'      => 'shop_order',
        'post_status'    => 'any',
        'posts_per_page' => -1,
        'meta_key'       => '_user_id',
        'meta_value'     => $user_id,
    );
    
    $posts = get_posts($args);
    $orders = array();
    
    foreach ($posts as $post) {
        // Use the model's method instead of trying to call it on the controller
        $order_details = $order_model->get_order_details($post->ID);
        if ($order_details) {
            $orders[] = $order_details;
        }
    }
    
    return $orders;
}
}