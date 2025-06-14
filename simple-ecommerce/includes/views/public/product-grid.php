<?php
// Get current sort values
$current_orderby = isset($atts['orderby']) ? $atts['orderby'] : 'date';
$current_order = isset($atts['order']) ? $atts['order'] : 'DESC';
?>
<div class="simple-ecommerce-products columns-<?php echo esc_attr($atts['columns']); ?>">
<<<<<<< HEAD

    <!-- Product Toolbar -->
    <div class="product-toolbar">
        <div class="toolbar-left"> 
            <span class="results-count">
=======
    
    <!-- Product Toolbar -->
    <div class="product-toolbar">        <div class="toolbar-left">            <span class="results-count">
>>>>>>> 76b0bef22025c284c5c2fff027b881ee20484c4d
                <?php if ($products->have_posts()): ?>
                    Hiển thị <?php echo $products->post_count; ?> trong số <?php echo $products->found_posts; ?> sản phẩm
                <?php else: ?>
                    Không có sản phẩm nào
                <?php endif; ?>
            </span>
        </div>
        <div class="toolbar-right">
            <div class="sort-dropdown">
                <label for="product-sort">Sắp xếp theo:</label>
                <select id="product-sort" class="product-sort-select"
                    data-category="<?php echo esc_attr(isset($atts['category']) ? $atts['category'] : ''); ?>"
                    data-limit="<?php echo esc_attr($atts['limit']); ?>"
                    data-columns="<?php echo esc_attr($atts['columns']); ?>"
                    data-nonce="<?php echo wp_create_nonce('simple_ecommerce_nonce'); ?>">
                    <option value="date_DESC" <?php selected($current_orderby . '_' . $current_order, 'date_DESC'); ?>>
                        Mới nhất
                    </option>
                    <option value="date_ASC" <?php selected($current_orderby . '_' . $current_order, 'date_ASC'); ?>>
                        Cũ nhất
                    </option>
<<<<<<< HEAD
=======
                    <!-- <option value="title_ASC" <?php selected($current_orderby . '_' . $current_order, 'title_ASC'); ?>>
                        Tên A-Z
                    </option>
                    <option value="title_DESC" <?php selected($current_orderby . '_' . $current_order, 'title_DESC'); ?>>
                        Tên Z-A
                    </option> -->
>>>>>>> 76b0bef22025c284c5c2fff027b881ee20484c4d
                    <option value="meta_value_num_ASC" <?php selected($current_orderby . '_' . $current_order, 'meta_value_num_ASC'); ?>>
                        Giá thấp đến cao
                    </option>
                    <option value="meta_value_num_DESC" <?php selected($current_orderby . '_' . $current_order, 'meta_value_num_DESC'); ?>>
                        Giá cao đến thấp
                    </option>
                </select>
            </div>
        </div>
    </div>

    <!-- Loading overlay -->
    <div class="products-loading" style="display: none;">
        <div class="loading-spinner">Đang tải...</div>
    </div>

    <!-- Product Grid Container -->
    <div class="products-grid-container">
        <?php if ($products->have_posts()): ?>
            <div class="products-grid">
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
            </div>

            <?php
            $total_pages = $products->max_num_pages;
            $current_page = isset($atts['paged']) ? intval($atts['paged']) : (get_query_var('paged') ? get_query_var('paged') : 1);

            if ($total_pages > 1): ?>
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
        <?php else: ?>
            <p class="no-products" style="color: black;">Không có sản phẩm nào.</p>
        <?php endif; ?>
    </div>
</div>