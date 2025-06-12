<?php
// This file contains the complete grid content with products and pagination for AJAX responses
?>
<div class="products-grid">
    <?php if ($products->have_posts()): ?>
        <?php while ($products->have_posts()):
            $products->the_post(); ?>
            <?php
            $product_id = get_the_ID();
            $product_model = new Simple_Ecommerce_Product();
            $product = $product_model->get_info($product_id);

            $image = get_the_post_thumbnail_url($product_id, 'medium');
            if (!$image) {
                $image = SIMPLE_ECOMMERCE_URL . 'public/images/placeholder.png';
            }
            ?>
            <div class="product-card">
                <div class="product-image">
                    <a href="<?php the_permalink(); ?>">
                        <img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>">
                    </a>
                    <div class="product-actions">
                        <button class="add-to-cart-btn" data-product-id="<?php echo esc_attr($product_id); ?>"
                            data-nonce="<?php echo wp_create_nonce('simple_ecommerce_nonce'); ?>">
                            Thêm vào giỏ hàng
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <h3 class="product-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <div class="product-price">
                        <?php if (isset($product['sale_price']) && $product['sale_price'] < $product['regular_price']): ?>
                            <span
                                class="current-price"><?php echo number_format($product['sale_price'], 0, ',', '.'); ?>₫</span>
                            <span
                                class="original-price"><?php echo number_format($product['regular_price'], 0, ',', '.'); ?>₫</span>
                        <?php else: ?>
                            <span
                                class="current-price"><?php echo number_format($product['regular_price'], 0, ',', '.'); ?>₫</span>
                        <?php endif; ?>
                    </div>

                    <?php if (isset($product['stock']) && $product['stock'] <= 0): ?>
                        <div class="out-of-stock">Hết Hàng</div>
                    <?php elseif (isset($product['stock']) && $product['stock'] > 0): ?>
                        <div class="in-stock">Còn Hàng</div>
                    <?php endif; ?>

                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-products-found">
            <p style="color: black;">Không có sản phẩm nào.</p>
        </div>
    <?php endif; ?>
</div>

<?php wp_reset_postdata(); ?>
