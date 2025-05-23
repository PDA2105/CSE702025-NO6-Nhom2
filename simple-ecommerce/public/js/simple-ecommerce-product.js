jQuery(document).ready(function($) {
    // Add to cart button click - AJAX
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var productId = $button.data('product-id');
        var nonce = $button.data('nonce');
        var $quantity = $button.closest('.product-summary, .product-card, .product-list-item').find('.quantity');
        var quantity = $quantity.length ? parseInt($quantity.val()) : 1;
        
        // Disable button during request
        $button.prop('disabled', true).addClass('loading');
        
        $.ajax({
            url: simple_ecommerce.ajax_url,
            type: 'POST',
            data: {
                action: 'add_to_cart',
                product_id: productId,
                quantity: quantity,
                nonce: nonce
            },
            success: function(response) {
                $button.prop('disabled', false).removeClass('loading');
                
                if (response.success) {
                    // Update cart count in header
                    $('.cart-count').text(response.data.cart_count);
                    
                    // Show success message
                    var $message = $('.product-added-message');
                    if ($message.length) {
                        $message.slideDown(300).delay(3000).slideUp(300);
                    } else {
                        // Create floating notification if not on product page
                        var notification = $('<div class="cart-notification">Product added to cart! <a href="' + simple_ecommerce.cart_url + '">View Cart</a></div>');
                        $('body').append(notification);
                        notification.fadeIn(300).delay(3000).fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                } else {
                    alert(response.data.message || 'Failed to add product to cart.');
                }
            },
            error: function() {
                $button.prop('disabled', false).removeClass('loading');
                alert('An error occurred. Please try again.');
            }
        });
    });
    
    // Quantity controls
    $(document).on('click', '.quantity-btn', function() {
        var $button = $(this);
        var $input = $button.parent().find('.quantity');
        var oldValue = parseInt($input.val());
        var max = parseInt($input.attr('max'));
        
        if ($button.hasClass('plus')) {
            var newVal = oldValue + 1;
            if (max && newVal > max) {
                newVal = max;
            }
        } else {
            var newVal = oldValue > 1 ? oldValue - 1 : 1;
        }
        
        $input.val(newVal).trigger('change');
    });
    
    // Product tabs
    $('.tabs-nav a').on('click', function(e) {
        e.preventDefault();
        
        var target = $(this).attr('href');
        
        $('.tabs-nav li').removeClass('active');
        $(this).parent().addClass('active');
        
        $('.tab-panel').removeClass('active');
        $(target).addClass('active');
    });
    
    // Product gallery
    $('.product-gallery .gallery-item').on('click', function() {
        var imgSrc = $(this).find('img').attr('src');
        $('.main-image img').attr('src', imgSrc);
    });
    
    // Close message
    $(document).on('click', '.close-message', function() {
        $(this).closest('.product-added-message').slideUp(300);
    });
});