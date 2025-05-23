<?php
// filepath: includes/views/public/checkout.php

$cart = new Simple_Ecommerce_Cart();
$cart_items = $cart->get_cart();

/**
 * Format price with currency symbol
 */
function simple_format_price($price) {
    return  number_format($price, 0)." đ";
}

if (!$cart_items || empty($cart_items['items'])): ?>
    <p>Your cart is empty.</p>
    <p><a href="<?php echo get_post_type_archive_link('product'); ?>">Continue Shopping</a></p>
<?php else: ?>
    <div class="simple-ecommerce-checkout">
        <h2>Checkout</h2>
        
        <div class="order-summary">
            <h3>Order Summary</h3>
            <table class="checkout-items">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items['items'] as $item): ?>
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
                        <th><?php echo simple_format_price($cart_items['total']); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="checkout-form">
            <h3>Customer Information</h3>
            <form method="post">
                <div class="form-row">
                    <label for="customer_name">Name</label>
                    <input type="text" id="customer_name" name="customer_name" required>
                </div>
                
                <div class="form-row">
                    <label for="customer_email">Email</label>
                    <input type="email" id="customer_email" name="customer_email" required>
                </div>
                
                <div class="form-row">
                    <label for="shipping_address">Shipping Address</label>
                    <textarea id="shipping_address" name="shipping_address" rows="4" required></textarea>
                </div>
                
                <div class="form-row">
                    <input type="submit" name="simple_checkout_submit" value="Place Order">
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>