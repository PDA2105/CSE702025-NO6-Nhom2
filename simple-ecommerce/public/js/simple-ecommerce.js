jQuery(document).ready(function ($) {
    // Helper function to update cart counter in header
    function updateHeaderCartCount(count) {
        var $cartCountElement = $('.cart-count');
        
        if (count > 0) {
            if ($cartCountElement.length === 0) {
                // Create cart count element if it doesn't exist
                $('.cart-icon').append('<span class="cart-count">' + count + '</span>');
            } else {
                $cartCountElement.text(count).addClass('updated');
                // Remove animation class after animation completes
                setTimeout(function() {
                    $cartCountElement.removeClass('updated');
                }, 600);
            }
        } else {
            $cartCountElement.remove();
        }
    }

    // update cart item quantity
    $('.quantity').on('change', function () {
        var productId = $(this).data('product-id');
        var quantity = $(this).val();

        if (quantity > 0) {
            $.ajax({
                url: simple_ecommerce.ajax_url,
                type: 'POST',
                data: {
                    action: 'update_cart',
                    nonce: simple_ecommerce.nonce,
                    product_id: productId,
                    quantity: quantity                },
                success: function (response) {
                    if (response.success) {
                        // Update cart counter in header if cart_count is provided
                        if (response.data && response.data.cart_count !== undefined) {
                            updateHeaderCartCount(response.data.cart_count);
                        }
                        location.reload();
                    }
                }
            });
        }
    });    // Remove item from cart
    $('.remove-item').on('click', function (e) {
        e.preventDefault();
        var productId = $(this).data('product-id');

        $.ajax({
            url: simple_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'remove_from_cart',
                product_id: productId,
                security: simple_ajax_object.nonce            },
            success: function (response) {
                if (response.success) {
                    // Update cart counter in header if cart_count is provided
                    if (response.data && response.data.cart_count !== undefined) {
                        updateHeaderCartCount(response.data.cart_count);
                    }
                    location.reload();
                }
            }
        });

    });
});