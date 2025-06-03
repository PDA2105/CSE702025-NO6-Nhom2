jQuery(document).ready(function($) {
    // Add to cart button click - AJAX
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var productId = $button.data('product-id');
        var nonce = $button.data('nonce');
        var $quantity = $button.closest('.product-summary, .product-card, .product-list-item').find('.quantity');
        var quantity = $quantity.length ? parseInt($quantity.val()) : 1;
        
        // Kiểm tra dữ liệu trước khi gửi
        if (!productId || !nonce) {
            console.error('Missing product ID or nonce');
            alert('Có lỗi xảy ra. Vui lòng tải lại trang và thử lại.');
            return;
        }
        
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
                
                if (response && response.success) {
                    // Update cart count in header
                    $('.cart-count').text(response.data.cart_count);
                    
                    // Show success message
                    var $message = $('.product-added-message');
                    if ($message.length) {
                        $message.slideDown(300).delay(3000).slideUp(300);
                    } else {
                        // Create floating notification if not on product page
                        var cartUrl = $('<div>').text(simple_ecommerce.cart_url).html();
                        var notification = $('<div class="cart-notification">' +
                            '<div class="success-icon"></div>' +
                            '<div class="message-content">' +
                                'Sản phẩm đã được thêm vào giỏ hàng! ' +
                                '<a href="' + cartUrl + '">Xem giỏ hàng</a>' +
                            '</div>' +
                        '</div>');
                        $('body').append(notification);
                        notification.fadeIn(300).delay(3000).fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                } else {
                    console.error('Response error:', response);
                    alert(response && response.data && response.data.message ? response.data.message : 'Không thể thêm sản phẩm vào giỏ hàng.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                console.log('Response:', xhr.responseText);
                $button.prop('disabled', false).removeClass('loading');
                alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.');
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
