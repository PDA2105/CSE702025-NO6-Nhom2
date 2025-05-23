<?php
// filepath: includes/views/public/product-grid.php
?>

<div class="simple-ecommerce-products columns-<?php echo esc_attr($atts['columns']); ?>">
    <?php if ($products->have_posts()) : ?>
        <div class="products-grid">
            <?php while ($products->have_posts()) : $products->the_post(); ?>
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
                            <button class="add-to-cart-btn" data-product-id="<?php echo esc_attr($product_id); ?>" data-nonce="<?php echo wp_create_nonce('simple_ecommerce_nonce'); ?>">
                                <span class="dashicons dashicons-cart"></span> Thêm vào giỏ hàng
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <div class="product-price">
                            <?php echo Simple_Ecommerce_Helpers::format_price($product['price']); ?>
                        </div>
                        <div class="product-stock-qty">
                            Tồn kho: <?php echo isset($product['stock']) ? intval($product['stock']) : 0; ?>
                        </div>
                        <?php if (isset($product['stock']) && $product['stock'] <= 0) : ?>
                            <div class="out-of-stock">Hết Hàng</div>
                        <?php endif; ?>
                        <?php if (isset($product['stock']) && $product['stock'] > 0) : ?>
                            <div class="in-stock">Còn Hàng</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php
        $total_pages = $products->max_num_pages;
        $current_page = isset($atts['paged']) ? intval($atts['paged']) : (get_query_var('paged') ? get_query_var('paged') : 1);

        if ($total_pages > 1) : ?>
            <div class="simple-pagination">
                <?php echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                )); ?>  
            </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    <?php else : ?>
        <p class="no-products">No products found.</p>
    <?php endif; ?>
</div>