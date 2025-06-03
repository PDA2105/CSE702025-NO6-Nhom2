jQuery(document).ready(function ($) {
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
                    quantity: quantity
                },
                success: function (response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        }
    });

    // Remove item from cart
    $('.remove-item').on('click', function (e) {
        e.preventDefault();
        var productId = $(this).data('product-id');

        $.ajax({
            url: simple_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'remove_from_cart',
                product_id: productId,
                security: simple_ajax_object.nonce
            },
            success: function (response) {
                if (response.success) {
                    location.reload();
                }
            }
        });

    });
});