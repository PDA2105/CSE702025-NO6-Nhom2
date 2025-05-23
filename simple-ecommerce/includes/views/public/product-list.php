<?php
// filepath: includes/views/public/product-list.php
?>

<div class="simple-ecommerce-products product-list-view">
    <?php if ($products->have_posts()) : ?>
        <div class="products-list">
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
                <div class="product-list-item">
                    <div class="product-image">
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>">
                        </a>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <div class="product-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <div class="product-meta">
                            <div class="product-price">
                                <?php echo Simple_Ecommerce_Helpers::format_price($product['price']); ?>
                            </div>
                            <?php if (isset($product['stock']) && $product['stock'] <= 0) : ?>
                                <div class="out-of-stock">Out of Stock</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="product-actions">
                        <a href="<?php the_permalink(); ?>" class="view-details-btn">View Details</a>
                        <button class="add-to-cart-btn" data-product-id="<?php echo esc_attr($product_id); ?>" data-nonce="<?php echo wp_create_nonce('simple_ecommerce_nonce'); ?>">
                            <span class="dashicons dashicons-cart"></span> Add to Cart
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
    <?php else : ?>
        <p class="no-products">No products found.</p>
    <?php endif; ?>
</div>