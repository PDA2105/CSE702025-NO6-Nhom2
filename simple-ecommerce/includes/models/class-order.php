<?php
// filepath: includes/models/class-order.php

class Simple_Ecommerce_Order {
    
    public function __construct() {
        add_action('init', array($this, 'register_order_post_type'));
    }
    
    public function register_order_post_type() {
        $labels = array(
            'name'               => 'Orders',
            'singular_name'      => 'Order',
            'menu_name'          => 'Orders',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Order',
            'edit_item'          => 'Edit Order',
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'shop-order'),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 6,
            'supports'           => array('title'),
            'menu_icon'          => 'dashicons-clipboard',
        );

        register_post_type('shop_order', $args);
    }
    
    public function create_order($user_data, $cart) {
        // Generate order title
        $order_title = 'Order #' . time();
        
        // Create order post
        $order_data = array(
            'post_title'    => $order_title,
            'post_status'   => 'publish', 
            'post_type'     => 'shop_order',
        );
        
        $order_id = wp_insert_post($order_data);
        
        if (!$order_id) {
            return false;
        }
        
        // Save order meta
        update_post_meta($order_id, '_order_items', $cart['items']);
        update_post_meta($order_id, '_order_total', $cart['total']);
        update_post_meta($order_id, '_order_status', 'completed');
        update_post_meta($order_id, '_user_id', get_current_user_id());
        update_post_meta($order_id, '_order_date', current_time('mysql'));
        update_post_meta($order_id, '_shipping_address', $user_data['shipping_address']);
        update_post_meta($order_id, '_customer_email', $user_data['email']);
        
        return $order_id;
    }
    
    public function update_status($order_id, $status) {
        return update_post_meta($order_id, '_order_status', $status);
    }
    
    public function get_order_details($order_id) {
    $order = get_post($order_id);
    
    if (!$order || $order->post_type !== 'shop_order') {
        return false;
    }
    
    // Try/catch to ensure no errors if meta is missing
    try {
        return array(
            'id' => $order_id,
            'date' => get_post_meta($order_id, '_order_date', true) ?: $order->post_date,
            'status' => get_post_meta($order_id, '_order_status', true) ?: 'pending',
            'total' => (float)get_post_meta($order_id, '_order_total', true) ?: 0,
            'items' => get_post_meta($order_id, '_order_items', true) ?: array(),
            'shipping_address' => get_post_meta($order_id, '_shipping_address', true) ?: '',
            'customer_email' => get_post_meta($order_id, '_customer_email', true) ?: '',
        );
    } catch (Exception $e) {
        // Log error if needed
        return false;
    }
}
    
    public function get_user_orders($user_id) {
        $args = array(
            'post_type' => 'shop_order',
            'posts_per_page' => -1,
            'meta_key' => '_user_id',
            'meta_value' => $user_id,
        );
        
        $orders = get_posts($args);
        $result = array();
        
        foreach ($orders as $order) {
            $result[] = $this->get_order_details($order->ID);
        }
        
        return $result;
    }
}