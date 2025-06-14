<?php
class Simple_Ecommerce_Cart_Controller
{

    private $cart;

    public function __construct()
    {
        $this->cart = new Simple_Ecommerce_Cart();


        add_action('wp_ajax_add_to_cart', array($this, 'ajax_add_to_cart'));
        add_action('wp_ajax_nopriv_add_to_cart', array($this, 'ajax_add_to_cart'));

        add_action('wp_ajax_remove_from_cart', array($this, 'ajax_remove_from_cart'));
        add_action('wp_ajax_nopriv_remove_from_cart', array($this, 'ajax_remove_from_cart'));

        add_action('wp_ajax_update_cart', array($this, 'ajax_update_cart'));
        add_action('wp_ajax_nopriv_update_cart', array($this, 'ajax_update_cart'));


        add_shortcode('simple_cart', array($this, 'cart_shortcode'));
        add_shortcode('simple_checkout', array($this, 'checkout_shortcode'));
    }

    public function ajax_add_to_cart()
    {
        check_ajax_referer('simple_ecommerce_nonce', 'nonce');

        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        if ($product_id > 0) {
            $result = $this->cart->add_item($product_id, $quantity);            if ($result) {
                $cart_data = $this->cart->get_cart();
                wp_send_json_success(array(
                    'success' => true,
                    'message' => 'Product added to cart successfully!',
                    'cart_count' => $cart_data['count'],
                    'cart_total' => $cart_data['total']
                ));
            } else {
                wp_send_json_error(array('message' => 'Failed to add product to cart'));
            }
        } else {
            wp_send_json_error(array('message' => 'Invalid product ID'));
        }
    }   
    public function ajax_remove_from_cart()
    {
        // Check for different nonce formats
        $nonce_verified = false;
        
        if (isset($_POST['nonce'])) {
            $nonce_verified = wp_verify_nonce($_POST['nonce'], 'simple_ecommerce_nonce');
        }
        
        if (!$nonce_verified && isset($_POST['security'])) {
            $nonce_verified = wp_verify_nonce($_POST['security'], 'remove-from-cart');
        }
        
        if (!$nonce_verified) {
            wp_send_json_error(array('message' => 'Security check failed'));
            return;
        }

        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

        if ($product_id > 0) {
            $result = $this->cart->remove_item($product_id);            if ($result) {
                $cart_data = $this->cart->get_cart();
                wp_send_json_success(array(
                    'success' => true,
                    'cart' => array(
                        'subtotal' => $cart_data['total'],
                        'total' => $cart_data['total'],
                        'count' => $cart_data['count'],
                    ),
                    'cart_count' => $cart_data['count']
                ));
            } else {
                wp_send_json_error(array('message' => 'Failed to remove product'));
            }
        } else {
            wp_send_json_error(array('message' => 'Invalid product ID'));
        }
    }

    public function ajax_update_cart()
    {
        check_ajax_referer('simple_ecommerce_nonce', 'nonce');

        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        if ($product_id > 0) {
            $result = $this->cart->update_cart($product_id, $quantity);

            if ($result) {
                $cart_data = $this->cart->get_cart();
                $item_subtotal = 0;
                if (isset($cart_data['items'][$product_id])) {
                    $item_subtotal = $cart_data['items'][$product_id]['subtotal'];
                }                wp_send_json_success(array(
                    'success' => true,
                    'subtotal' => $item_subtotal,
                    'cart' => array(
                        'subtotal' => $cart_data['total'],
                        'total' => $cart_data['total'],
                        'count' => $cart_data['count'],
                    ),
                    'cart_count' => $cart_data['count']
                ));
            } else {
                wp_send_json_error(array('message' => 'Failed to update cart'));
            }
        } else {
            wp_send_json_error(array('message' => 'Invalid product ID'));
        }
    }

    public function cart_shortcode()
    {
        ob_start();
        include_once plugin_dir_path(dirname(__FILE__)) . 'views/public/cart.php';
        return ob_get_clean();
    }

    public function checkout_shortcode()
    {
        $cart_items = $this->cart->get_cart();
        
        if (isset($_POST['simple_checkout_submit'])) {            // Lấy và làm sạch dữ liệu từ form
            $user_data = array(
                'name' => sanitize_text_field($_POST['customer_name']),
                'email' => sanitize_email($_POST['customer_email']),
                'phone' => sanitize_text_field($_POST['customer_phone']),
                'province' => isset($_POST['customer_city']) ? sanitize_text_field($_POST['customer_city']) : '',
                'district' => isset($_POST['customer_district']) ? sanitize_text_field($_POST['customer_district']) : '',
                'shipping_address' => isset($_POST['shipping_address']) ? sanitize_textarea_field($_POST['shipping_address']) : '',
                'payment_method' => isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : 'cod',
                'order_notes' => isset($_POST['order_notes']) ? sanitize_textarea_field($_POST['order_notes']) : '',
            );

            // Kiểm tra dữ liệu bắt buộc
            $required_fields = array('name', 'email', 'phone', 'province', 'district', 'shipping_address');
            $errors = array();
            
            foreach ($required_fields as $field) {
                if (empty($user_data[$field])) {
                    $errors[] = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
                    break;
                }
            }
            
            if (empty($errors)) {
                // Tạo đơn hàng
                $order_id = $this->cart->checkout($user_data);

                if ($order_id) {
                    // Chuyển hướng đến trang cảm ơn
                    echo '<script>window.location.href="' . esc_url(add_query_arg('order_id', $order_id, home_url('/thank-you/'))) . '";</script>';
                    return '';
                } else {
                    $errors[] = 'Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại.';
                }
            }
        }

        ob_start();
        include_once plugin_dir_path(dirname(__FILE__)) . 'views/public/checkout.php';
        return ob_get_clean();
    }
}
