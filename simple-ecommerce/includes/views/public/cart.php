<?php

$cart = new Simple_Ecommerce_Cart();
$cart_items = $cart->get_cart();


$subtotal = 0;
if ($cart_items && !empty($cart_items['items'])) {
    foreach ($cart_items['items'] as $item) {
        $subtotal += $item['subtotal'];
    }
}

function get_product_image_url($product_id)
{
    $image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium');
    return $image ? $image[0] : 'https://via.placeholder.com/100x100?text=No+Image';
}

?>

<div class="simple-ecommerce-cart">
    <main class="main-content">
        <div class="container">
            <?php if (!$cart_items || empty($cart_items['items'])): ?>
                <div class="empty-cart" id="emptyCart">
                    <div class="empty-cart-content">
                        <div class="empty-cart-icon">🛒</div>
                        <h2>Giỏ hàng của bạn đang trống</h2>
                        <p>Hãy khám phá các sản phẩm tuyệt vời của chúng tôi</p>
                        <a href="<?php echo site_url('/shop'); ?>" class="continue-shopping-btn">
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="container">
                    <div class="header-content">
                        <a href="<?php echo site_url('/shop'); ?>" class="back-btn">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Tiếp tục mua sắm
                        </a>                        <h1 class="page-title">Giỏ hàng của bạn</h1>
                        
                    </div>
                </div>
                <div class="cart-layout">

                    <div class="cart-items">
                        <?php foreach ($cart_items['items'] as $product_id => $item): ?>
                            <div class="cart-item" data-product-id="<?php echo esc_attr($product_id); ?>">
                                <div class="item-image">
                                    <img src="<?php echo esc_url(get_product_image_url($product_id)); ?>"
                                        alt="<?php echo esc_attr($item['name']); ?>">
                                </div>
                                <div class="item-details">
                                    <h3 class="item-name"><?php echo esc_html($item['name']); ?></h3>
                                    <p class="item-specs">Bảo hành: 12 tháng</p>
                                    <div class="item-actions">
                                        <div class="qty-control">
                                            <button class="qty-btn minus" type="button">−</button>
                                            <input class="qty" type="text" value="<?php echo esc_attr($item['quantity']); ?>"
                                                readonly>
                                            <button class="qty-btn plus" type="button">+</button>
                                        </div>
                                        <button class="remove-btn" type="button">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                <path d="M3 6H5H21" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            Xóa
                                        </button>
                                    </div>
                                </div>
                                <div class="item-price">
                                    <div class="current-price"><?php echo simple_format_price($item['subtotal']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="cart-summary">
                        <div class="summary-card">
                            <h3 class="summary-title">Tóm tắt đơn hàng</h3>

                            <div class="summary-row">
                                <span>Tạm tính</span>
                                <span id="subtotal"><?php echo simple_format_price($subtotal); ?></span>
                            </div>

                            <div class="summary-row">
                                <span>Phí vận chuyển</span>
                                <span id="shipping">Miễn phí</span>
                            </div>

                            <div class="summary-row">
                                <span>Giảm giá</span>
                                <span id="discount" class="discount-amount">0₫</span>
                            </div>

                            <div class="summary-divider"></div>

                            <div class="summary-row total-row">
                                <span>Tổng cộng</span>
                                <span id="total"
                                    class="total-price"><?php echo simple_format_price($cart_items['total']); ?></span>
                            </div>

                            <div class="promo-section">                                <div class="promo-input-group">
                                    <input type="text" class="promo-input" placeholder="Nhập mã giảm giá" id="promoCode">
                                    <button class="promo-btn">Áp dụng</button>
                                </div>
                            </div>                            <button class="checkout-btn">
                                Thanh toán
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </button>

                            <div class="security-badges">
                                <div class="badge">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    Thanh toán bảo mật
                                </div>
                                <div class="badge">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    Đảm bảo hoàn tiền
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>


    <div class="modal" id="successModal">
        <div class="modal-content">
            <div class="modal-icon success">✓</div>            <h3>Thành công!</h3>
            <p id="modalMessage">Thao tác đã được thực hiện thành công</p>
            <button class="modal-btn">Đóng</button>
        </div>
    </div>    <script>
        jQuery(document).ready(function($) {
            // Helper function to format price
            function formatPrice(amount) {
                return Number(amount).toLocaleString('vi-VN') + 'đ';
            }

            // Function to update header cart count
            function updateHeaderCartCount(count) {
                console.log('Updating header cart count to:', count);
                var cartCountElement = $('.cart-icon .cart-count');
                console.log('Found cart count element:', cartCountElement.length);
                
                if (cartCountElement.length > 0) {
                    cartCountElement.text(count);
                    cartCountElement.addClass('updated');
                    
                    setTimeout(function() {
                        cartCountElement.removeClass('updated');
                    }, 600);
                    console.log('Cart count updated successfully');
                } else {
                    console.log('Cart count element not found');
                }
            }

            function updateQuantity(productId, newQty, qtyInput, priceDiv, cartItem) {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'update_cart',
                    nonce: '<?php echo wp_create_nonce('simple_ecommerce_nonce'); ?>',
                    product_id: productId,
                    quantity: newQty
                },
                success: function (response) {                    if (response.success) {
                        qtyInput.value = newQty;
                        if (response.data && response.data.subtotal) {
                            priceDiv.textContent = formatPrice(response.data.subtotal);
                        }
                        
                        if (response.data && response.data.cart) {
                            $('#subtotal').text(formatPrice(response.data.cart.subtotal));
                            $('#total').text(formatPrice(response.data.cart.total));
                            $('#cart-items-count').text(response.data.cart.count);
                            
                            // Update cart counter in header
                            updateHeaderCartCount(response.data.cart.count);
                        }
                        if (newQty === 0) {
                            cartItem.remove();
                        }
                        if (response.data && response.data.cart && response.data.cart.count === 0) {
                            location.reload();
                        }
                    } else {
                        alert('Không thể cập nhật số lượng');
                    }
                },
                error: function () {
                    alert('Có lỗi xảy ra khi cập nhật giỏ hàng');
                }
            });        }        function removeItem(productId, cartItem) {
            console.log('Removing item:', productId);
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'remove_from_cart',
                    nonce: '<?php echo wp_create_nonce('simple_ecommerce_nonce'); ?>',
                    product_id: productId
                },
                success: function (response) {
                    console.log('Remove response:', response);                    if (response.success) {
                        $(cartItem).remove();
                        if (response.data && response.data.cart) {
                            $('#subtotal').text(formatPrice(response.data.cart.subtotal));
                            $('#total').text(formatPrice(response.data.cart.total));
                            $('#cart-items-count').text(response.data.cart.count);
                            
                            // Update cart counter in header
                            updateHeaderCartCount(response.data.cart.count);
                        }
                        if (response.data && response.data.cart && response.data.cart.count === 0) {
                            location.reload();
                        }
                    } else {
                        console.error('Remove failed:', response);
                        alert('Không thể xóa sản phẩm');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', xhr.responseText);
                    alert('Có lỗi xảy ra khi xóa sản phẩm');
                }            });
        }        function applyPromoCode() {
            var $promoInput = $('#promoCode');
            var code = $promoInput.val().trim().toUpperCase();

            if (!code) {
                showModal('Lỗi', 'Vui lòng nhập mã giảm giá');
                return;
            }

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'apply_promo_code',
                    nonce: '<?php echo wp_create_nonce('simple_ecommerce_nonce'); ?>',
                    promo_code: code,
                    cart_total: <?php echo $subtotal; ?>
                },
                success: function (response) {
                    if (response.success) {
                        var discount = response.data.discount;
                        var total = <?php echo $subtotal; ?> - discount;

                        $('#discount').text('-' + formatPrice(discount));
                        $('#total').text(formatPrice(total));

                        $promoInput.val('');
                        showModal('Thành công!', response.data.description + ' đã được áp dụng');

                        var $promoSection = $('.promo-section');
                        $promoSection.addClass('promo-applied');
                        setTimeout(function() {
                            $promoSection.removeClass('promo-applied');
                        }, 1000);
                    } else {
                        showModal('Lỗi', response.data.message || 'Mã giảm giá không hợp lệ');
                        $promoInput.addClass('error');
                        setTimeout(function() {
                            $promoInput.removeClass('error');
                        }, 2000);
                    }
                },
                error: function () {
                    showModal('Lỗi', 'Có lỗi xảy ra khi áp dụng mã giảm giá');
                }
            });
        }        function proceedToCheckout() {
            if (parseInt($('#cart-items-count').text(), 10) === 0) {
                showModal('Thông báo', 'Giỏ hàng của bạn đang trống');
                return;
            }
            var $checkoutBtn = $('.checkout-btn');
            var originalText = $checkoutBtn.html();
            $checkoutBtn.html(`
                <div class="loading-spinner"></div>
                Đang xử lý...
            `).prop('disabled', true);
            
            setTimeout(function() {
                window.location.href = '<?php echo home_url('/checkout/'); ?>';
            }, 1000);
        }

        function showModal(title, message) {
            var $modal = $('#successModal');
            $modal.find('h3').text(title);
            $('#modalMessage').text(message);            $modal.show();

            setTimeout(function() {
                closeModal();
            }, 3000);
        }function closeModal() {
            $('#successModal').hide();
        }

        // Initialize event listeners using jQuery
        $('.cart-item').each(function() {
            var $item = $(this);
            var productId = $item.data('product-id');
            var $minusBtn = $item.find('.qty-btn.minus');
            var $plusBtn = $item.find('.qty-btn.plus');
            var qtyInput = $item.find('.qty')[0];
            var priceDiv = $item.find('.current-price')[0];

            $minusBtn.on('click', function() {
                var qty = parseInt(qtyInput.value, 10);
                if (qty > 1) {
                    updateQuantity(productId, qty - 1, qtyInput, priceDiv, $item[0]);
                }
            });

            $plusBtn.on('click', function() {
                var qty = parseInt(qtyInput.value, 10);
                if (qty < 10) {
                    updateQuantity(productId, qty + 1, qtyInput, priceDiv, $item[0]);
                }
            });

            $item.find('.remove-btn').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                removeItem(productId, $item[0]);
            });
        });

        // Checkout button event listener
        $('.checkout-btn').on('click', function(e) {
            e.preventDefault();
            proceedToCheckout();
        });

        // Promo button event listener
        $('.promo-btn').on('click', function(e) {
            e.preventDefault();
            applyPromoCode();
        });

        // Modal close button event listener  
        $('.modal-btn').on('click', function(e) {
            e.preventDefault();
            closeModal();});

        $('#promoCode').on('keypress', function (e) {
            if (e.key === 'Enter') {
                applyPromoCode();
            }
        });

        $('#successModal').on('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });

        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        }); // End jQuery ready
    </script>
</div>