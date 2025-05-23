<?php
// filepath: includes/views/public/product-detail.php

// Get product categories
$terms = get_the_terms($product_data['id'], 'product_category');
$categories = array();
if ($terms && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        $categories[] = $term->name;
    }
}

// Get product image
$image = get_the_post_thumbnail_url($product_data['id'], 'large');
if (!$image) {
    $image = SIMPLE_ECOMMERCE_URL . 'public/images/placeholder.png';
}

// Get gallery images
$gallery_images = get_post_meta($product_data['id'], '_gallery_images', true);
?>

<div class="simple-ecommerce-product-detail" id="product-<?php echo esc_attr($product_data['id']); ?>">
    <div class="product-message-container">
        <div class="product-added-message" style="display: none;">
            <div class="message-content">
                <span class="dashicons dashicons-yes"></span>
                <span class="message-text">Product added to cart!</span>
                <a href="<?php echo esc_url(home_url('/cart/')); ?>" class="view-cart-link">View Cart</a>
                <span class="close-message">&times;</span>
            </div>
        </div>
    </div>

    <div class="product-container">
        <div class="product-images">
            <div class="main-image">
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($product_data['name']); ?>">
            </div>
            
            <?php if (!empty($gallery_images)) : ?>
                <div class="product-gallery">
                    <?php foreach ($gallery_images as $gallery_image) : ?>
                        <?php $img_url = wp_get_attachment_image_url($gallery_image, 'medium'); ?>
                        <?php if ($img_url) : ?>
                            <div class="gallery-item">
                                <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($product_data['name']); ?>">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="product-summary">
            <h1 class="product-title"><?php echo esc_html($product_data['name']); ?></h1>
            
            <?php if (!empty($categories)) : ?>
                <div class="product-categories">
                    <span>Category: </span><?php echo esc_html(implode(', ', $categories)); ?>
                </div>
            <?php endif; ?>
            
            <div class="product-price">
                <?php echo Simple_Ecommerce_Helpers::format_price($product_data['price']); ?>
            </div>
            
            <div class="product-description">
                <?php echo wpautop($product_data['description']); ?>
            </div>
            
            <?php if ($product_data['stock'] > 0) : ?>
                <div class="product-stock in-stock">
                    <span class="dashicons dashicons-yes"></span> In Stock
                    <?php if ($product_data['stock'] < 10) : ?>
                        (Only <?php echo esc_html($product_data['stock']); ?> left)
                    <?php endif; ?>
                </div>
                
                <div class="add-to-cart-form">
                    <div class="quantity-input">
                        <label for="product-quantity">Quantity:</label>
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn minus">-</button>
                            <input type="number" id="product-quantity" class="quantity" value="1" min="1" max="<?php echo esc_attr($product_data['stock']); ?>">
                            <button type="button" class="quantity-btn plus">+</button>
                        </div>
                    </div>
                    
                    <button class="add-to-cart-btn" data-product-id="<?php echo esc_attr($product_data['id']); ?>" data-nonce="<?php echo wp_create_nonce('simple_ecommerce_nonce'); ?>">
                        <span class="dashicons dashicons-cart"></span> Add to Cart
                    </button>
                </div>
            <?php else : ?>
                <div class="product-stock out-of-stock">
                    <span class="dashicons dashicons-no"></span> Out of Stock
                </div>
            <?php endif; ?>
            
            <div class="product-meta-info">
                <?php if (!empty($product_data['sku'])) : ?>
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
                        <?php if (!empty($product_data['weight'])) : ?>
                            <tr>
                                <th>Weight</th>
                                <td><?php echo esc_html($product_data['weight']); ?> kg</td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php if (!empty($product_data['dimensions'])) : ?>
                            <tr>
                                <th>Dimensions</th>
                                <td>
                                    <?php 
                                    $dimensions = $product_data['dimensions'];
                                    if (is_array($dimensions)) {
                                        $dim_parts = array();
                                        if (!empty($dimensions['length'])) $dim_parts[] = 'Length: ' . $dimensions['length'] . ' cm';
                                        if (!empty($dimensions['width'])) $dim_parts[] = 'Width: ' . $dimensions['width'] . ' cm';
                                        if (!empty($dimensions['height'])) $dim_parts[] = 'Height: ' . $dimensions['height'] . ' cm';
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