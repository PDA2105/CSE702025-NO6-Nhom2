<?php

class Simple_Ecommerce_Order_Meta_Box
{


    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
    }


    public function add_meta_boxes()
    {
        add_meta_box(
            'simple_order_data',
            __('Order Details', 'simple-ecommerce'),
            array($this, 'render_order_meta_box'),
            'shop_order',
            'normal',
            'high'
        );

        add_meta_box(
            'simple_order_items',
            __('Order Items', 'simple-ecommerce'),
            array($this, 'render_order_items_meta_box'),
            'shop_order',
            'normal',
            'default'
        );

        add_meta_box(
            'simple_order_customer',
            __('Customer Details', 'simple-ecommerce'),
            array($this, 'render_customer_meta_box'),
            'shop_order',
            'side',
            'default'
        );
    }


    public function render_order_meta_box($post)
    {

        wp_nonce_field('simple_order_save', 'simple_order_nonce');

        $order_date = get_post_meta($post->ID, '_order_date', true);
        $order_status = get_post_meta($post->ID, '_order_status', true) ?: 'pending';
        $order_total = get_post_meta($post->ID, '_order_total', true);

        ?>
        <div class="order-meta-box">
            <div class="order-meta-box-field">
                <label for="_order_date"><?php _e('Order Date:', 'simple-ecommerce'); ?></label>
                <input type="text" id="_order_date" name="_order_date" value="<?php echo esc_attr($order_date); ?>"
                    class="widefat" readonly>
                <p class="description"><?php _e('The date when the order was placed.', 'simple-ecommerce'); ?></p>
            </div>

            <div class="order-meta-box-field">
                <label for="_order_status"><?php _e('Order Status:', 'simple-ecommerce'); ?></label>
                <select id="_order_status" name="_order_status" class="widefat">
                    <option value="pending" <?php selected($order_status, 'pending'); ?>>
                        <?php _e('Chờ xử lý', 'simple-ecommerce'); ?></option>
                    <option value="processing" <?php selected($order_status, 'processing'); ?>>
                        <?php _e('Đang xử lý', 'simple-ecommerce'); ?></option>
                    <option value="completed" <?php selected($order_status, 'completed'); ?>>
                        <?php _e('Hoàn Thành', 'simple-ecommerce'); ?></option>
                    <option value="cancelled" <?php selected($order_status, 'cancelled'); ?>>
                        <?php _e('Đã Hủy', 'simple-ecommerce'); ?></option>
                </select>
            </div>

            <div class="order-meta-box-field">
                <label for="_order_total"><?php _e('Order Total:', 'simple-ecommerce'); ?></label>
                <div class="price-field">
                    <span class="price-symbol">$</span>
                    <input type="text" id="_order_total" name="_order_total" value="<?php echo esc_attr($order_total); ?>"
                        readonly>
                </div>
            </div>
        </div>

        <style>
            .order-meta-box-field {
                margin-bottom: 15px;
            }

            .order-meta-box-field label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            .price-field {
                position: relative;
                display: inline-block;
            }

            .price-symbol {
                position: absolute;
                left: 10px;
                top: 6px;
            }

            #_order_total {
                padding-left: 25px;
            }
        </style>
        <?php
    }


    public function render_order_items_meta_box($post)
    {

        $order_items = get_post_meta($post->ID, '_order_items', true);

        ?>
        <div class="order-items-table-container">
            <?php if (!$order_items || empty($order_items)): ?>
                <p><?php _e('No items found in this order.', 'simple-ecommerce'); ?></p>
            <?php else: ?>
                <table class="widefat order-items-table">
                    <thead>
                        <tr>
                            <th><?php _e('Product', 'simple-ecommerce'); ?></th>
                            <th><?php _e('Quantity', 'simple-ecommerce'); ?></th>
                            <th><?php _e('Price', 'simple-ecommerce'); ?></th>
                            <th><?php _e('Subtotal', 'simple-ecommerce'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                            <tr>                                <td>
                                    <?php
                                    echo esc_html($item['name']);
                                    echo ' <a href="' . get_edit_post_link($item['id']) . '" class="edit-item">' . __('(Edit)', 'simple-ecommerce') . '</a>';
                                    ?>
                                </td>
                                <td><?php echo esc_html($item['quantity']); ?></td>
                                <td><?php echo '$' . number_format($item['price'], 2); ?></td>
                                <td><?php echo '$' . number_format($item['subtotal'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3"><?php _e('Total:', 'simple-ecommerce'); ?></th>
                            <th>
                                <?php
                                $total = get_post_meta($post->ID, '_order_total', true);
                                echo '$' . number_format($total, 2);
                                ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </div>

        <style>
            .order-items-table {
                border-collapse: collapse;
            }

            .order-items-table th,
            .order-items-table td {
                padding: 10px;
            }

            .edit-item {
                font-size: 12px;
                margin-left: 5px;
            }
        </style>
        <?php
    }


    public function render_customer_meta_box($post)
    {
        $user_id = get_post_meta($post->ID, '_user_id', true);
        $customer_name = get_post_meta($post->ID, '_customer_name', true);
        $customer_email = get_post_meta($post->ID, '_customer_email', true);
        $customer_phone = get_post_meta($post->ID, '_customer_phone', true);
        $shipping_address = get_post_meta($post->ID, '_shipping_address', true);
        $payment_method = get_post_meta($post->ID, '_payment_method', true);
        $order_notes = get_post_meta($post->ID, '_order_notes', true);
        
        // Hiển thị thông tin khách hàng
        echo '<div class="order-customer-info">';
        echo '<p><strong>Tên khách hàng:</strong> ' . esc_html($customer_name) . '</p>';
        echo '<p><strong>Email:</strong> ' . esc_html($customer_email) . '</p>';
        echo '<p><strong>Điện thoại:</strong> ' . esc_html($customer_phone) . '</p>';
          // Hiển thị địa chỉ giao hàng
        echo '<h4>Địa chỉ giao hàng:</h4>';
        if (is_array($shipping_address)) {
            $formatted_address = Simple_Ecommerce_Helpers::format_shipping_address($shipping_address);
            echo '<p>' . esc_html($formatted_address) . '</p>';
        } else {
            echo '<p>' . esc_html($shipping_address) . '</p>';
        }
        
        // Hiển thị phương thức thanh toán
        echo '<h4>Phương thức thanh toán:</h4>';
        echo '<p>' . ($payment_method === 'cod' ? 'Tiền mặt' : 'Chuyển khoản') . '</p>';
        
        // Hiển thị ghi chú đơn hàng
        if (!empty($order_notes)) {
            echo '<h4>Ghi chú đơn hàng:</h4>';
            echo '<p>' . esc_html($order_notes) . '</p>';
        }
        
        echo '</div>';
    }


    public function save_meta_boxes($post_id, $post)
    {

        if (!isset($_POST['simple_order_nonce']) || !wp_verify_nonce($_POST['simple_order_nonce'], 'simple_order_save')) {
            return $post_id;
        }

        if ('shop_order' != $post->post_type || !current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if (isset($_POST['_order_status'])) {
            update_post_meta($post_id, '_order_status', sanitize_text_field($_POST['_order_status']));
        }
    }
}

