<?php
// Lấy thông tin đơn hàng
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if (!$order_id) {
    wp_redirect(home_url());
    exit;
}

$order_items = get_post_meta($order_id, '_order_items', true);
$order_total = get_post_meta($order_id, '_order_total', true);
$customer_name = get_post_meta($order_id, '_customer_name', true);
$customer_phone = get_post_meta($order_id, '_customer_phone', true);
$customer_email = get_post_meta($order_id, '_customer_email', true);
$shipping_address = get_post_meta($order_id, '_shipping_address', true);
$payment_method = get_post_meta($order_id, '_payment_method', true);

// Đảm bảo order_total là một số, không phải mảng
if (is_array($order_total)) {
    $order_total = 0; // hoặc xử lý mảng theo cách phù hợp
}

// Định dạng địa chỉ giao hàng
$formatted_address = Simple_Ecommerce_Helpers::format_shipping_address($shipping_address);
?>

<div class="simple-ecommerce-thank-you">
    <div class="thank-you-header">
        <div class="thank-you-icon">✅</div>
        <h2>Cảm ơn bạn đã đặt hàng!</h2>
        <p>Đơn hàng #<?php echo esc_html($order_id); ?> của bạn đã được tiếp nhận.</p>
    </div>

    <div class="customer-info">
        <h3>Thông tin khách hàng</h3>
        <p><strong>Họ tên:</strong> <?php echo esc_html($customer_name); ?></p>
        <?php if (!empty($customer_email)): ?>
            <p><strong>Email:</strong> <?php echo esc_html($customer_email); ?></p>
        <?php endif; ?>
        <?php if (!empty($customer_phone)): ?>
            <p><strong>Số điện thoại:</strong> <?php echo esc_html($customer_phone); ?></p>
        <?php endif; ?>
        <?php if (!empty($formatted_address)): ?>
            <p><strong>Địa chỉ giao hàng:</strong> <?php echo esc_html($formatted_address); ?></p>
        <?php endif; ?>
    </div>

    <div class="order-details">
        <h3>Chi tiết đơn hàng</h3>

        <div class="order-items">
            <?php if (is_array($order_items) && !empty($order_items)): ?>
                <?php foreach ($order_items as $item): ?>
                    <div class="order-item">
                        <div class="item-image">
                            <?php
                            $product_id = isset($item['id']) ? $item['id'] : 0;
                            $image_url = get_the_post_thumbnail_url($product_id, 'thumbnail');
                            if (!$image_url) {
                                $image_url = 'https://via.placeholder.com/50x50?text=No+Image';
                            }
                            ?>
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($item['name']); ?>">
                        </div>
                        <div class="item-name-qty">
                            <span class="item-name"><?php echo esc_html($item['name']); ?></span>
                            <span class="item-qty">Số lượng: <?php echo esc_html($item['quantity']); ?></span>
                        </div>
                        <div class="item-price">
                            <?php
                            $subtotal = isset($item['subtotal']) && !is_array($item['subtotal']) ? $item['subtotal'] : 0;
                            echo simple_format_price($subtotal);
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="order-total">
            <span>Tổng cộng:</span>
            <span><?php echo simple_format_price($order_total); ?></span>
        </div>
    </div>

    <div class="payment-instructions">
        <?php if ($payment_method === 'bank_transfer'): ?>
            <h3>Hướng dẫn thanh toán</h3>
            <p>Vui lòng chuyển khoản theo thông tin sau:</p>
            <div class="bank-info">
                <p><strong>Ngân hàng:</strong> MB BANK</p>
                <p><strong>Số tài khoản:</strong> 0375401903</p>
                <p><strong>Chủ tài khoản:</strong> DO THANH TUNG</p>
                <p><strong>Nội dung:</strong> THANHTOAN<?php echo esc_html($order_id); ?></p>
            </div>
        <?php else: ?>
            <p>Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng.</p>
        <?php endif; ?>
    </div>

    <div class="thank-you-actions">
        <a href="<?php echo esc_url(home_url('/shop')); ?>" class="continue-shopping">Tiếp tục mua sắm</a>
    </div>
</div>