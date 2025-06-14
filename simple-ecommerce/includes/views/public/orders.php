<?php
if (!defined('ABSPATH')) {
    exit; 
}

// Function để chuyển đổi status sang tiếng Việt
function get_order_status_label($status) {
    $status_labels = array(
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy'
    );
    
    return isset($status_labels[$status]) ? $status_labels[$status] : ucfirst($status);
}
?>
<div class="simple-ecommerce-orders">
    <h2>Đơn hàng của bạn</h2>
    <?php if (!empty($orders)) : ?>
        <table class="simple-ecommerce-orders-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Tổng tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) : ?>
                    <tr>
                        <td><?php echo esc_html($order['ID'] ?? $order['id'] ?? ''); ?></td>
                        <td><?php echo esc_html(date('Y-m-d', strtotime($order['order_date'] ?? $order['date'] ?? ''))); ?></td>
                        <td><?php echo esc_html(get_order_status_label($order['status'] ?? '')); ?></td>
                        <td><?php echo esc_html(number_format((float)($order['total'] ?? 0), 0, ',', '.')) . ' đ'; ?></td>
                        <td>
                            <a href="?order_id=<?php echo esc_attr($order['ID'] ?? $order['id'] ?? ''); ?>" class="simple-ecommerce-view-order">Xem</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Bạn chưa có đơn hàng nào.</p>
    <?php endif; ?>
</div>
