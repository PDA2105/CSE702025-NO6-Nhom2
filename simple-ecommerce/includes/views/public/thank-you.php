<?php
// filepath: includes/views/public/thank-you.php

/**
 * Format price with currency symbol
 */
function simple_format_price($price) {
    return  number_format($price, 0)." đ";
}
?>

<div class="simple-ecommerce-thank-you">
    <h2>Thank You for Your Order!</h2>
    
    <div class="order-information">
        <h3>Order #<?php echo esc_html($order_details['id']); ?></h3>
        <p>Date: <?php echo esc_html($order_details['date']); ?></p>
        <p>Status: <?php echo esc_html($order_details['status']); ?></p>
        
        <h4>Order Details:</h4>
        <table class="order-items">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_details['items'] as $item): ?>
                <tr>
                    <td><?php echo esc_html($item['name']); ?></td>
                    <td><?php echo esc_html($item['quantity']); ?></td>
                    <td><?php echo simple_format_price($item['subtotal']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th><?php echo simple_format_price($order_details['total']); ?></th>
                </tr>
            </tfoot>
        </table>
        
        <div class="shipping-information">
            <h4>Shipping Information:</h4>
            <p><?php echo nl2br(esc_html($order_details['shipping_address'])); ?></p>
        </div>
        
        <p>A confirmation email has been sent to <?php echo esc_html($order_details['customer_email']); ?>.</p>
    </div>
    
    <a href="<?php echo get_post_type_archive_link('product'); ?>" class="button">Continue Shopping</a>
</div>