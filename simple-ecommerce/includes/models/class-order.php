<?php


class Simple_Ecommerce_Order
{

    public function __construct()
    {
        add_action('init', array($this, 'register_order_post_type'));
    }

    public function register_order_post_type()
    {
        $labels = array(
            'name' => 'Orders',
            'singular_name' => 'Order',
            'menu_name' => 'Orders',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Order',
            'edit_item' => 'Edit Order',
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'shop-order'),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 6,
            'supports' => array('title'),
            'menu_icon' => 'dashicons-clipboard',
        );

        register_post_type('shop_order', $args);
    }

    /**
     * Format price with currency symbol
     *
     * @param float $price The price to format
     * @return string Formatted price with currency symbol
     */
    private function format_price($price) {
        return number_format($price, 0, ',', '.') . ' đ';
    }    public function create_order($user_data, $cart)
    {
        $order_title = 'Order #' . time();

        $order_data = array(
            'post_title' => $order_title,
            'post_status' => 'publish',
            'post_type' => 'shop_order',
        );

        $order_id = wp_insert_post($order_data);

        if (!$order_id || is_wp_error($order_id)) {
            return false;
        }

        // Lưu thông tin đơn hàng
        update_post_meta($order_id, '_order_items', $cart['items']);
        update_post_meta($order_id, '_order_total', $cart['total']);
        update_post_meta($order_id, '_order_status', 'pending');
        update_post_meta($order_id, '_user_id', $user_data['user_id']);
        update_post_meta($order_id, '_order_date', current_time('mysql'));
        
        // Lưu thông tin khách hàng
        update_post_meta($order_id, '_customer_name', $user_data['name']);
        update_post_meta($order_id, '_customer_email', $user_data['email']);
        update_post_meta($order_id, '_customer_phone', $user_data['phone']);
        
        // Lưu địa chỉ giao hàng
        $shipping_address = array(
            'province' => $user_data['province'],
            'district' => $user_data['district'],
            'address' => $user_data['shipping_address'],
        );
        update_post_meta($order_id, '_shipping_address', $shipping_address);
        
        // Lưu phương thức thanh toán
        update_post_meta($order_id, '_payment_method', $user_data['payment_method']);
        
        // Lưu ghi chú đơn hàng
        if (!empty($user_data['order_notes'])) {
            update_post_meta($order_id, '_order_notes', $user_data['order_notes']);
        }

        // Gửi email thông báo đơn hàng
        try {
            $this->send_order_notification($order_id, $user_data, $cart);
        } catch (Exception $e) {
            // Log error but don't fail the order creation
        }

        return $order_id;
    }

    public function update_status($order_id, $status)
    {
        return update_post_meta($order_id, '_order_status', $status);
    }

    public function get_order_details($order_id)
    {
        $order = get_post($order_id);

        if (!$order || $order->post_type !== 'shop_order') {
            return false;
        }

        try {
            return array(
                'id' => $order_id,
                'date' => get_post_meta($order_id, '_order_date', true) ?: $order->post_date,
                'status' => get_post_meta($order_id, '_order_status', true) ?: 'pending',
                'total' => (float) get_post_meta($order_id, '_order_total', true) ?: 0,
                'items' => get_post_meta($order_id, '_order_items', true) ?: array(),
                'shipping_address' => get_post_meta($order_id, '_shipping_address', true) ?: '',
                'customer_email' => get_post_meta($order_id, '_customer_email', true) ?: '',
            );
        } catch (Exception $e) {

            return false;
        }
    }

    public function get_user_orders($user_id)
    {
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

    /**
     * Gửi email thông báo đơn hàng
     */
    private function send_order_notification($order_id, $user_data, $cart)
    {
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        
        // Email cho admin
        $admin_subject = sprintf('[%s] Đơn hàng mới #%s', $site_name, $order_id);
        $admin_message = "Có đơn hàng mới từ khách hàng:\n\n";
        $admin_message .= "Tên: " . $user_data['name'] . "\n";
        $admin_message .= "Email: " . $user_data['email'] . "\n";
        $admin_message .= "Điện thoại: " . $user_data['phone'] . "\n\n";
        $admin_message .= "Xem chi tiết đơn hàng tại: " . admin_url('post.php?post=' . $order_id . '&action=edit');
        
        wp_mail($admin_email, $admin_subject, $admin_message);
        
        // Email cho khách hàng
        $customer_subject = sprintf('[%s] Xác nhận đơn hàng #%s', $site_name, $order_id);
        $customer_message = "Cảm ơn bạn đã đặt hàng tại " . $site_name . ".\n\n";
        $customer_message .= "Thông tin đơn hàng:\n";
        
        foreach ($cart['items'] as $item) {
            // Đảm bảo subtotal là một số, không phải mảng
            $subtotal = isset($item['subtotal']) && !is_array($item['subtotal']) ? $item['subtotal'] : 0;
            $customer_message .= $item['name'] . " x " . $item['quantity'] . ": " . $this->format_price($subtotal) . "\n";
        }
        
        // Đảm bảo total là một số, không phải mảng
        $total = isset($cart['total']) && !is_array($cart['total']) ? $cart['total'] : 0;
        $customer_message .= "\nTổng cộng: " . $this->format_price($total) . "\n\n";
        $customer_message .= "Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận đơn hàng.";
        
        wp_mail($user_data['email'], $customer_subject, $customer_message);
    }
}
