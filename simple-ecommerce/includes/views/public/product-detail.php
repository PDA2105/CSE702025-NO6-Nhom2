<?php

$terms = get_the_terms($product_data['id'], 'product_category');
$categories = array();
if ($terms && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        $categories[] = $term->name;
    }
}


$image = get_the_post_thumbnail_url($product_data['id'], 'large');
if (!$image) {
    $image = SIMPLE_ECOMMERCE_URL . 'public/images/placeholder.png';
}

$gallery_images = get_post_meta($product_data['id'], '_gallery_images', true);
?>

<div class="simple-ecommerce-product-detail" id="product-<?php echo esc_attr($product_data['id']); ?>">
    <div class="product-message-container">
        <div class="product-added-message" style="display: none;">
            <div class="message-content">
                <span class="dashicons dashicons-yes"></span>
                <span class="message-text">Sản phẩm đã được thêm vào giỏ hàng!</span>
                <a href="<?php echo esc_url(home_url('/cart/')); ?>" class="view-cart-link">Xem giỏ hàng</a>
            </div>
        </div>
    </div>

    <div class="product-container">
        <div class="product-images">
            <div class="main-image">
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($product_data['name']); ?>">
            </div>

            <?php if (!empty($gallery_images)): ?>
                <div class="product-gallery">
                    <?php foreach ($gallery_images as $gallery_image): ?>
                        <?php $img_url = wp_get_attachment_image_url($gallery_image, 'medium'); ?>
                        <?php if ($img_url): ?>
                            <div class="gallery-item">
                                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($product_data['name']); ?>">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="product-summary">
            <div class="product-header">
                <h1 class="product-title-detail"><?php echo esc_html($product_data['name']); ?></h1>

                <!-- Phần chọn khu vực nhỏ bên cạnh title -->
                <div class="product-region-selector-compact">
                    <div class="region-dropdown-compact">
                        <select id="region-select" class="region-select-compact">
                            <option value="">Chọn khu vực</option>
                            <option value="hanoi">Hà Nội</option>
                            <option value="hcm">TP. HCM</option>
                            <option value="danang">Đà Nẵng</option>
                            <option value="haiphong">Hải Phòng</option>
                            <option value="cantho">Cần Thơ</option>
                            <option value="other">Tỉnh khác</option>
                        </select>
                        <div class="region-info-compact" style="display: none;">
                            <div class="delivery-info-compact">
                                <span class="time-text-compact">Giao trong 2-4h</span>
                                <span class="fee-text-compact">Phí: 25.000₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($categories)): ?>
                <div class="product-categories">
                    <span>Category: </span><?php echo esc_html(implode(', ', $categories)); ?>
                </div>
            <?php endif; ?>

            <div class="product-price-detail">
                <span
                    class="current-price-detail"><?php echo number_format($product_data['sale_price'], 0, ',', '.'); ?>₫</span>
                <span
                    class="original-price-detail"><?php echo number_format($product_data['regular_price'], 0, ',', '.'); ?>₫</span>
            </div>
            <div class="vat">
                <p>(Đã bao gồm VAT)</p>
            </div>
            <div class="product-stock in-stock">
                <span class="dashicons dashicons-yes"></span> Còn Hàng
                <?php if ($product_data['stock'] < 10): ?>
                    (Only <?php echo esc_html($product_data['stock']); ?> left)
                <?php endif; ?>
            </div>
            <!-- <div class="product-description">
                <?php echo wpautop($product_data['description']); ?>
            </div> -->

            <?php if ($product_data['stock'] > 0): ?>
                <div class="add-to-cart-form">
                    <div class="quantity-input">
                        <label for="product-quantity">Số lượng:</label>
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn minus">-</button>
                            <input type="number" id="product-quantity" class="quantity" value="1" min="1"
                                max="<?php echo esc_attr($product_data['stock']); ?>">
                            <button type="button" class="quantity-btn plus">+</button>
                        </div>
                    </div>
                    <div class="uu-dai">
                        <p class="title-uu-dai">Ưu đãi</p>
                        <div class="content-uu-dai">
                            <div class="uu-dai-item">
                                <div class="uu-dai-icon">
                                    <span class="dashicons dashicons-cart"></span>
                                </div>
                                <div class="uu-dai-text">
                                    <p><strong>Miễn phí vận chuyển</strong> cho đơn hàng từ 500.000₫</p>
                                </div>
                            </div>

                            <div class="uu-dai-item">
                                <div class="uu-dai-icon">
                                    <span class="dashicons dashicons-money-alt"></span>
                                </div>
                                <div class="uu-dai-text">
                                    <p><strong>Giảm 5%</strong> khi mua từ 2 sản phẩm trở lên</p>
                                </div>
                            </div>

                            <div class="uu-dai-item">
                                <div class="uu-dai-icon">
                                    <span class="dashicons dashicons-awards"></span>
                                </div>
                                <div class="uu-dai-text">
                                    <p><strong>Tích điểm thưởng</strong> 2% giá trị đơn hàng</p>
                                </div>
                            </div>

                            <div class="uu-dai-item">
                                <div class="uu-dai-icon">
                                    <span class="dashicons dashicons-businessman"></span>
                                </div>
                                <div class="uu-dai-text">
                                    <p><strong>Bảo hành 12 tháng</strong> chính hãng</p>
                                </div>
                            </div>

                            <div class="uu-dai-item">
                                <div class="uu-dai-icon">
                                    <span class="dashicons dashicons-clock"></span>
                                </div>
                                <div class="uu-dai-text">
                                    <p><strong>Giao hàng nhanh</strong> trong 2-4 giờ nội thành</p>
                                </div>
                            </div>

                            <div class="uu-dai-item">
                                <div class="uu-dai-icon">
                                    <span class="dashicons dashicons-update"></span>
                                </div>
                                <div class="uu-dai-text">
                                    <p><strong>Đổi trả miễn phí</strong> trong 7 ngày</p>
                                </div>
                            </div>


                            <div class="uu-dai-item">
                                <div class="uu-dai-icon">
                                    <span class="dashicons dashicons-star-filled"></span>
                                </div>
                                <div class="uu-dai-text">
                                    <p><strong>Ưu đãi thành viên VIP</strong> giảm thêm 3% cho lần mua tiếp theo</p>
                                </div>
                            </div>

                            <div class="uu-dai-item">
                                <div class="uu-dai-icon">
                                    <span class="dashicons dashicons-heart"></span>
                                </div>
                                <div class="uu-dai-text">
                                    <p><strong>Quà tặng đặc biệt</strong> cho đơn hàng trên 1.000.000₫</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="buttons">
                        <button class="add-to-cart-btn" data-product-id="<?php echo esc_attr($product_data['id']); ?>"
                            data-nonce="<?php echo wp_create_nonce('simple_ecommerce_nonce'); ?>">
                            <span class="dashicons dashicons-cart"></span> Thêm vào giỏ hàng
                        </button>

                        <button class="mua-ngay-btn">
                            Mua ngay
                        </button>
                        <button class="change">
                            Thu cũ đổi mới
                        </button>

                    </div>

                </div>
            <?php else: ?>
                <div class="product-stock out-of-stock">
                    <span class="dashicons dashicons-no"></span> Hết hàng
                </div>
            <?php endif; ?>

            <div class="product-meta-info">
                <?php if (!empty($product_data['sku'])): ?>
                    <div class="product-sku">
                        <span>SKU: </span><?php echo esc_html($product_data['sku']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="product-tabs">
        <ul class="tabs-nav">
            <li class="active"><a href="#tab-description">Description</a></li>
            <li><a href="#tab-additional-info">Additional Information</a></li>
        </ul>

        <div class="tabs-content">
            <div id="tab-description" class="tab-panel active">
                <?php echo wpautop($product_data['description']); ?>
            </div>

            <div id="tab-additional-info" class="tab-panel">
                <table class="additional-info-table">
                    <tbody>
                        <?php if (!empty($product_data['weight'])): ?>
                            <tr>
                                <th>Weight</th>
                                <td><?php echo esc_html($product_data['weight']); ?> kg</td>
                            </tr>
                        <?php endif; ?>

                        <?php if (!empty($product_data['dimensions'])): ?>
                            <tr>
                                <th>Dimensions</th>
                                <td>
                                    <?php
                                    $dimensions = $product_data['dimensions'];
                                    if (is_array($dimensions)) {
                                        $dim_parts = array();
                                        if (!empty($dimensions['length']))
                                            $dim_parts[] = 'Length: ' . $dimensions['length'] . ' cm';
                                        if (!empty($dimensions['width']))
                                            $dim_parts[] = 'Width: ' . $dimensions['width'] . ' cm';
                                        if (!empty($dimensions['height']))
                                            $dim_parts[] = 'Height: ' . $dimensions['height'] . ' cm';
                                        echo implode(', ', $dim_parts);
                                    } else {
                                        echo esc_html($dimensions);
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const regionSelect = document.getElementById('region-select');
        const regionInfo = document.querySelector('.region-info-compact');
        const timeText = document.querySelector('.time-text-compact');
        const feeText = document.querySelector('.fee-text-compact');
        const regionData = {
            'hanoi': {
                time: 'Giao trong 2-4h',
                fee: 'Miễn phí'
            },
            'hcm': {
                time: 'Giao trong 2-4h',
                fee: 'Miễn phí'
            },
            'danang': {
                time: 'Giao trong 4-6h',
                fee: '25.000₫'
            },
            'haiphong': {
                time: 'Giao trong 6-8h',
                fee: '30.000₫'
            },
            'cantho': {
                time: 'Giao trong 1-2 ngày',
                fee: '45.000₫'
            },
            'other': {
                time: 'Giao trong 2-3 ngày',
                fee: '50.000₫'
            }
        };

        regionSelect.addEventListener('change', function () {
            const selectedValue = this.value;
            if (selectedValue && regionData[selectedValue]) {
                const data = regionData[selectedValue];
                timeText.textContent = data.time;
                feeText.textContent = 'Phí: ' + data.fee;
                regionInfo.style.display = 'block';
            } else {
                regionInfo.style.display = 'none';
            }
        });
    });
</script>