<?php
// filepath: includes/models/class-cart.php

class Simple_Ecommerce_Cart {
    
    private $cart_key = 'simple_ecommerce_cart';
    
    public function __construct() {
        if (!is_admin()) {
            add_action('init', array($this, 'initialize_cart'));
        }
    }
    
    public function initialize_cart() {
        if (!$this->get_cart()) {
            $this->set_empty_cart();
        }
    }
    
    public function get_cart() {
        if (is_user_logged_in()) {
            return get_user_meta(get_current_user_id(), $this->cart_key, true);
        } else {
            if (isset($_SESSION[$this->cart_key])) {
                return $_SESSION[$this->cart_key];
            } 
            
            if (isset($_COOKIE[$this->cart_key])) {
                return json_decode(stripslashes($_COOKIE[$this->cart_key]), true);
            }
        }
        return false;
    }
    
    private function save_cart($cart) {
        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), $this->cart_key, $cart);
        } else {
            $_SESSION[$this->cart_key] = $cart;
            setcookie(
                $this->cart_key,
                json_encode($cart),
                time() + (7 * DAY_IN_SECONDS),
                COOKIEPATH,
                COOKIE_DOMAIN
            );
        }
    }
    
    private function set_empty_cart() {
        $cart = array(
            'items' => array(),
            'total' => 0,
            'count' => 0,
        );
        $this->save_cart($cart);
        return $cart;
    }
    
    public function add_item($product_id, $quantity = 1) {
        $cart = $this->get_cart();
        if (!$cart) {
            $cart = $this->set_empty_cart();
        }
        
        // Check if product exists
        $product_model = new Simple_Ecommerce_Product();
        $product = $product_model->get_info($product_id);
        
        if (!$product) {
            return false;
        }
        
        // Check if product is already in cart
        if (isset($cart['items'][$product_id])) {
            $cart['items'][$product_id]['quantity'] += $quantity;
        } else {
            $cart['items'][$product_id] = array(
                'product_id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'subtotal' => $product['price'] * $quantity,
            );
        }
        
        // Calculate totals
        $this->calculate_totals($cart);
        $this->save_cart($cart);
        
        return true;
    }
    
    public function remove_item($product_id) {
        $cart = $this->get_cart();
        if (!$cart || !isset($cart['items'][$product_id])) {
            return false;
        }
        
        unset($cart['items'][$product_id]);
        
        // Calculate totals
        $this->calculate_totals($cart);
        $this->save_cart($cart);
        
        return true;
    }
    
    public function update_quantity($product_id, $quantity) {
        if ($quantity <= 0) {
            return $this->remove_item($product_id);
        }
        
        $cart = $this->get_cart();
        if (!$cart || !isset($cart['items'][$product_id])) {
            return false;
        }
        
        $cart['items'][$product_id]['quantity'] = $quantity;
        $cart['items'][$product_id]['subtotal'] = 
            $cart['items'][$product_id]['price'] * $quantity;
        
        // Calculate totals
        $this->calculate_totals($cart);
        $this->save_cart($cart);
        
        return true;
    }
    
    public function clear_cart() {
        return $this->set_empty_cart();
    }
    
    private function calculate_totals(&$cart) {
        $cart['total'] = 0;
        $cart['count'] = 0;
        
        foreach ($cart['items'] as $item) {
            $cart['total'] += $item['subtotal'];
            $cart['count'] += $item['quantity'];
        }
    }
    
    public function checkout($user_data) {
        $cart = $this->get_cart();
    if (!$cart || empty($cart['items'])) {
        return false;
    }
    
    // Get current user ID if logged in
    $user_id = is_user_logged_in() ? get_current_user_id() : 0;
    
    // Create order
    $order_model = new Simple_Ecommerce_Order();
    $order_data = array_merge($user_data, array('user_id' => $user_id));
    $order_id = $order_model->create_order($order_data, $cart);
    
    if ($order_id) {
        // Update stock
        $product_model = new Simple_Ecommerce_Product();
        foreach ($cart['items'] as $item) {
            $product_model->update_stock($item['product_id'], $item['quantity']);
        }
        
        // Clear cart
        $this->clear_cart();
        
        return $order_id;
    }
    
    return false;
    }
}