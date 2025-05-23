<?php
// filepath: includes/views/public/cart.php

$cart = new Simple_Ecommerce_Cart();
$cart_items = $cart->get_cart();

/**
 * Format price with currency symbol
 */
function simple_format_price($price) {
    return  number_format($price, 0)." đ";
}
?>

<div class="simple-ecommerce-cart">
    <h2>Your Cart</h2>
    
    <?php if (!$cart_items || empty($cart_items['items'])): ?>
        <p>Your cart is empty.</p>
        <p><a href="<?php echo home_url('/shop/'); ?>">Continue Shopping</a></p>
    <?php else: ?>
        <form id="cart-form">
            <table class="cart-items">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items['items'] as $product_id => $item): ?>
                    <tr>
                        <td><?php echo esc_html($item['name']); ?></td>
                        <td><?php echo simple_format_price($item['price']); ?></td>
                        <td>
                            <input type="number" min="1" class="quantity" 
                                data-product-id="<?php echo esc_attr($product_id); ?>" 
                                value="<?php echo esc_attr($item['quantity']); ?>">
                        </td>
                        <td><?php echo simple_format_price($item['subtotal']); ?></td>
                        <td>
                            <a href="#" class="remove-item" data-product-id="<?php echo esc_attr($product_id); ?>">Remove</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th><?php echo simple_format_price($cart_items['total']); ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            
            <div class="cart-actions">
                <a href="<?php echo get_post_type_archive_link('product'); ?>" class="button">Continue Shopping</a>
                <a href="<?php echo home_url('/checkout/'); ?>" class="button">Proceed to Checkout</a>
            </div>
        </form>
        
        <script>
        jQuery(function($) {
            // Update quantity
            $('.quantity').on('change', function() {
                var productId = $(this).data('product-id');
                var quantity = $(this).val();
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'update_cart',
                        nonce: '<?php echo wp_create_nonce('simple_ecommerce_nonce'); ?>',
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    }
                });
            });
            
            // Remove item
            $('.remove-item').on('click', function(e) {
                e.preventDefault();
                var productId = $(this).data('product-id');
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'remove_from_cart',
                        nonce: '<?php echo wp_create_nonce('simple_ecommerce_nonce'); ?>',
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    }
                });
            });
        });
        </script>
    <?php endif; ?>
</div>