<?php
// filepath: includes/admin/class-order-meta-box.php

/**
 * Order Meta Box Handler
 */
class Simple_Ecommerce_Order_Meta_Box {

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
    }

    /**
     * Register the meta boxes
     */
    public function add_meta_boxes() {
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

    /**
     * Render the order meta box
     */
    public function render_order_meta_box($post) {
        // Add a nonce field for security
        wp_nonce_field('simple_order_save', 'simple_order_nonce');

        // Get current values
        $order_date = get_post_meta($post->ID, '_order_date', true);
        $order_status = get_post_meta($post->ID, '_order_status', true) ?: 'pending';
        $order_total = get_post_meta($post->ID, '_order_total', true);
        
        ?>
        <div class="order-meta-box">
            <div class="order-meta-box-field">
                <label for="_order_date"><?php _e('Order Date:', 'simple-ecommerce'); ?></label>
                <input type="text" id="_order_date" name="_order_date" value="<?php echo esc_attr($order_date); ?>" class="widefat" readonly>
                <p class="description"><?php _e('The date when the order was placed.', 'simple-ecommerce'); ?></p>
            </div>
            
            <div class="order-meta-box-field">
                <label for="_order_status"><?php _e('Order Status:', 'simple-ecommerce'); ?></label>
                <select id="_order_status" name="_order_status" class="widefat">
                    <option value="pending" <?php selected($order_status, 'pending'); ?>><?php _e('Pending', 'simple-ecommerce'); ?></option>
                    <option value="processing" <?php selected($order_status, 'processing'); ?>><?php _e('Processing', 'simple-ecommerce'); ?></option>
                    <option value="completed" <?php selected($order_status, 'completed'); ?>><?php _e('Completed', 'simple-ecommerce'); ?></option>
                    <option value="cancelled" <?php selected($order_status, 'cancelled'); ?>><?php _e('Cancelled', 'simple-ecommerce'); ?></option>
                </select>
            </div>
            
            <div class="order-meta-box-field">
                <label for="_order_total"><?php _e('Order Total:', 'simple-ecommerce'); ?></label>
                <div class="price-field">
                    <span class="price-symbol">$</span>
                    <input type="text" id="_order_total" name="_order_total" value="<?php echo esc_attr($order_total); ?>" readonly>
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
    
    /**
     * Render the order items meta box
     */
    public function render_order_items_meta_box($post) {
        // Get order items
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
                            <tr>
                                <td>
                                    <?php 
                                    echo esc_html($item['name']); 
                                    echo ' <a href="' . get_edit_post_link($item['product_id']) . '" class="edit-item">' . __('(Edit)', 'simple-ecommerce') . '</a>';
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
    
    /**
     * Render the customer meta box
     */
    public function render_customer_meta_box($post) {
        $user_id = get_post_meta($post->ID, '_user_id', true);
        $customer_email = get_post_meta($post->ID, '_customer_email', true);
        $shipping_address = get_post_meta($post->ID, '_shipping_address', true);
        
        ?>
        <div class="customer-info">
            <?php if ($user_id): ?>
                <?php $user = get_userdata($user_id); ?>
                <?php if ($user): ?>
                    <p><strong><?php _e('Customer:', 'simple-ecommerce'); ?></strong> 
                        <a href="<?php echo admin_url('user-edit.php?user_id=' . $user_id); ?>"><?php echo esc_html($user->display_name); ?></a>
                    </p>
                <?php endif; ?>
            <?php endif; ?>
            
            <p><strong><?php _e('Email:', 'simple-ecommerce'); ?></strong> <?php echo esc_html($customer_email); ?></p>
            
            <?php if ($shipping_address): ?>
                <p><strong><?php _e('Shipping Address:', 'simple-ecommerce'); ?></strong><br>
                <?php echo nl2br(esc_html($shipping_address)); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Save the meta box data
     */
    public function save_meta_boxes($post_id, $post) {
        // Check if nonce is set
        if (!isset($_POST['simple_order_nonce']) || !wp_verify_nonce($_POST['simple_order_nonce'], 'simple_order_save')) {
            return $post_id;
        }

        // Check if user has permission
        if ('shop_order' != $post->post_type || !current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        // Don't save during autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Update order status
        if (isset($_POST['_order_status'])) {
            update_post_meta($post_id, '_order_status', sanitize_text_field($_POST['_order_status']));
        }
    }
}