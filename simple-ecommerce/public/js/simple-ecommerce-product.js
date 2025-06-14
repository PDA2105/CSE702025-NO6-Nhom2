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
                    var cartCount = response.data.cart_count;
                    var $cartCountElement = $('.cart-count');
                    
                    if (cartCount > 0) {
                        if ($cartCountElement.length === 0) {
                            // Create cart count element if it doesn't exist
                            $('.cart-icon').append('<span class="cart-count">' + cartCount + '</span>');
                        } else {
                            $cartCountElement.text(cartCount).addClass('updated');
                            // Remove animation class after animation completes
                            setTimeout(function() {
                                $cartCountElement.removeClass('updated');
                            }, 600);
                        }
                    } else {
                        $cartCountElement.remove();
                    }
                      // Show success message
                    var $message = $('.product-added-message');
                    if ($message.length) {
                        $message.addClass('show').delay(3000).fadeOut(300);                    } else {
                        // Create floating notification for product grid
                        var cartUrl = $('<div>').text(simple_ecommerce.cart_url).html();
                        var notification = $('<div class="ajax-cart-popup active">' +
                            '<div class="ajax-cart-popup-inner">' +
                                '<div class="ajax-cart-icon"></div>' +
                                '<div class="ajax-cart-text-wrapper">' +
                                    '<span class="ajax-cart-message">Sản phẩm đã được thêm vào giỏ hàng!</span>' +
                                    '<a href="' + cartUrl + '" class="ajax-cart-link">Xem giỏ hàng</a>' +
                                '</div>' +
                                '<span class="ajax-cart-close">&times;</span>' +
                            '</div>' +
                        '</div>');
                        $('body').append(notification);
                          // Auto hide after 3 seconds
                        setTimeout(function() {
                            notification.addClass('hide').delay(400).queue(function() {
                                $(this).remove();
                            });
                        }, 3000);
                        
                        // Close button functionality
                        notification.find('.ajax-cart-close').on('click', function() {
                            notification.addClass('hide').delay(400).queue(function() {
                                $(this).remove();
                            });
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
    });    // Close message for both product detail and ajax cart notification  
    $(document).on('click', '.close-message, .ajax-cart-close', function() {
        var $message = $(this).closest('.product-added-message');
        var $notification = $(this).closest('.cart-notification, .ajax-cart-popup');
        
        if ($message.length) {
            $message.slideUp(300);
        } else if ($notification.length) {
            $notification.addClass('hide').delay(400).queue(function() {
                $(this).remove();
            });
        }
    });// Product sorting functionality
    $(document).on('change', '.product-sort-select', function() {
        console.log('Sort dropdown changed'); // Debug log
        handleProductSort($(this), 1); // Start from page 1 when sorting
    });
    
    // Pagination click handler for AJAX pagination
    $(document).on('click', '.simple-pagination .page-numbers', function(e) {
        e.preventDefault();
        
        var $link = $(this);
        var href = $link.attr('href');
        
        // Skip if it's current page or dots
        if ($link.hasClass('current') || $link.hasClass('dots')) {
            return;
        }
        
        // Extract page number from URL or get from text
        var page = 1;
        if (href && href.includes('page/')) {
            var matches = href.match(/page\/(\d+)/);
            if (matches) {
                page = parseInt(matches[1]);
            }
        } else if ($link.text() === '«') {
            // Previous page
            var $current = $link.closest('.simple-pagination').find('.current');
            page = Math.max(1, parseInt($current.text()) - 1);
        } else if ($link.text() === '»') {
            // Next page
            var $current = $link.closest('.simple-pagination').find('.current');
            var $container = $link.closest('.simple-ecommerce-products');
            var $select = $container.find('.product-sort-select');
            var maxPage = Math.ceil($select.data('found-posts') / $select.data('limit'));
            page = Math.min(maxPage, parseInt($current.text()) + 1);
        } else {
            // Regular page number
            page = parseInt($link.text()) || 1;
        }
        
        console.log('Pagination clicked, page:', page);
        
        // Find the sort select to get current sorting parameters
        var $container = $link.closest('.simple-ecommerce-products');
        var $select = $container.find('.product-sort-select');
        
        handleProductSort($select, page);
    });
    
    // Unified function to handle both sorting and pagination
    function handleProductSort($select, page) {
        page = page || 1;
        var sortValue = $select.val();
        console.log('Sort value:', sortValue, 'Page:', page); // Debug log
        
        var orderby, order;
        
        // Handle price sorting specially
        if (sortValue.startsWith('meta_value_num_')) {
            orderby = 'meta_value_num';
            order = sortValue.replace('meta_value_num_', '');
        } else {
            var sortParts = sortValue.split('_');
            orderby = sortParts[0];
            order = sortParts[1];
        }
        
        console.log('Parsed - Orderby:', orderby, 'Order:', order); // Debug log
        
        // Get other parameters
        var category = $select.data('category') || '';
        var limit = $select.data('limit') || 12;
        var columns = $select.data('columns') || 4;
        var nonce = $select.data('nonce');
        
        console.log('Parameters:', {category, limit, columns, nonce, page}); // Debug log
        console.log('simple_ecommerce object:', simple_ecommerce); // Debug log
        
        // Check if AJAX URL is available
        if (!simple_ecommerce || !simple_ecommerce.ajax_url) {
            console.error('AJAX URL not available');
            alert('Lỗi: Không thể tìm thấy URL AJAX. Vui lòng tải lại trang.');
            return;
        }
        
        // Get the products container
        var $productsContainer = $select.closest('.simple-ecommerce-products');
        var $gridContainer = $productsContainer.find('.products-grid-container');
        var $paginationContainer = $productsContainer.find('.simple-pagination');
        var $loading = $productsContainer.find('.products-loading');
        
        // Show loading state
        $loading.show();
        $gridContainer.addClass('loading');
        
        // Disable the select during request
        $select.prop('disabled', true);
        
        // Prepare AJAX data
        var ajaxData = {
            action: 'sort_products',
            orderby: orderby,
            order: order,
            category: category,
            limit: limit,
            columns: columns,
            paged: page,
            nonce: nonce
        };
        
        console.log('AJAX Data:', ajaxData); // Debug log
        console.log('AJAX URL:', simple_ecommerce.ajax_url); // Debug log
        
        $.ajax({
            url: simple_ecommerce.ajax_url,
            type: 'POST',
            data: ajaxData,
            success: function(response) {
                console.log('AJAX Success Response:', response); // Debug log
                
                $select.prop('disabled', false);
                $loading.hide();
                $gridContainer.removeClass('loading');
                
                if (response && response.success) {
                    // Replace the grid content
                    $gridContainer.html(response.data.html);
                    
                    // Update or replace pagination
                    if (response.data.pagination) {
                        if ($paginationContainer.length) {
                            $paginationContainer.replaceWith(response.data.pagination);
                        } else {
                            $gridContainer.after(response.data.pagination);
                        }
                    } else {
                        // Remove pagination if only one page
                        $paginationContainer.remove();
                    }                    // Update results count
                    var $resultsCount = $productsContainer.find('.results-count');
                    if (response.data.found_posts) {
                        // Simply show the total count without range format
                        $resultsCount.text('Hiển thị ' + response.data.post_count + ' trong số ' + response.data.found_posts + ' sản phẩm');
                    }
                    
                    // Store found posts for pagination calculation
                    $select.data('found-posts', response.data.found_posts);
                    
                    // Smooth scroll to top of products
                    $('html, body').animate({
                        scrollTop: $productsContainer.offset().top - 100
                    }, 500);
                    
                } else {
                    console.error('Sort response error:', response);
                    alert('Có lỗi xảy ra khi sắp xếp sản phẩm. Vui lòng thử lại.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Sort AJAX error:', status, error);
                console.log('Response Text:', xhr.responseText);
                $select.prop('disabled', false);
                $loading.hide();
                $gridContainer.removeClass('loading');
                alert('Có lỗi xảy ra khi sắp xếp sản phẩm. Vui lòng kiểm tra console.');
            }
        });
    }
});
