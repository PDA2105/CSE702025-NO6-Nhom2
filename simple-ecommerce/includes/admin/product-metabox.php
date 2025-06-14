<?php

class Simple_Ecommerce_Product_Meta_Box
{    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_admin_scripts($hook)
    {
        global $post_type;
        
        if ($hook == 'post-new.php' || $hook == 'post.php') {
            if ('product' === $post_type) {
                wp_enqueue_media();
                wp_enqueue_script('jquery-ui-sortable');
            }
        }
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'simple_product_data',
            __('Product Data', 'simple-ecommerce'),
            array($this, 'render_product_meta_box'),
            'product',
            'normal',
            'high'
        );
    }    public function render_product_meta_box($post)
    {

        wp_nonce_field('simple_product_save', 'simple_product_nonce');

        $price = get_post_meta($post->ID, '_price', true);
        $regular_price = get_post_meta($post->ID, '_regular_price', true);
        $sale_price = get_post_meta($post->ID, '_sale_price', true);
        $stock = get_post_meta($post->ID, '_stock', true);
        $sku = get_post_meta($post->ID, '_sku', true);
        $weight = get_post_meta($post->ID, '_weight', true);
        $dimensions = get_post_meta($post->ID, '_dimensions', true);
        $short_description = get_post_meta($post->ID, '_short_description', true);
        $product_status = get_post_meta($post->ID, '_product_status', true);
        $gallery_images = get_post_meta($post->ID, '_product_gallery', true);
      
        $categories = get_terms('product_category', array('hide_empty' => false));
        $selected_categories = wp_get_post_terms($post->ID, 'product_category', array('fields' => 'ids'));
        ?>
        <div class="product-meta-box-container">            <div class="product-meta-box-tabs">
                <ul>
                    <li class="active"><a href="#general" data-tab="general">General</a></li>
                    <li><a href="#images" data-tab="images">Images</a></li>
                    <li><a href="#inventory" data-tab="inventory">Inventory</a></li>
                    <li><a href="#shipping" data-tab="shipping">Shipping</a></li>
                    <li><a href="#categories" data-tab="categories">Categories</a></li>
                </ul>
            </div>


            <div class="product-meta-box-panels">                <div class="panel active" id="general">
                    <div class="form-field">
                        <label for="_regular_price"><?php _e('Regular Price ($)', 'simple-ecommerce'); ?></label>
                        <input type="text" id="_regular_price" name="_regular_price"
                            value="<?php echo esc_attr($regular_price); ?>" class="regular-price">
                    </div>

                    <div class="form-field">
                        <label for="_sale_price"><?php _e('Sale Price ($)', 'simple-ecommerce'); ?></label>
                        <input type="text" id="_sale_price" name="_sale_price" value="<?php echo esc_attr($sale_price); ?>"
                            class="sale-price">
                    </div>

                    <div class="form-field">
                        <label for="_sku"><?php _e('SKU', 'simple-ecommerce'); ?></label>
                        <input type="text" id="_sku" name="_sku" value="<?php echo esc_attr($sku); ?>">
                        <p class="description">
                            <?php _e('Stock Keeping Unit. A unique identifier for this product.', 'simple-ecommerce'); ?></p>
                    </div>

                    <div class="form-field">
                        <label for="_short_description"><?php _e('Short Description', 'simple-ecommerce'); ?></label>
                        <textarea id="_short_description" name="_short_description" rows="3"><?php echo esc_textarea($short_description); ?></textarea>
                        <p class="description"><?php _e('Brief description of the product for listings.', 'simple-ecommerce'); ?></p>
                    </div>

                    <div class="form-field">
                        <label for="_product_status"><?php _e('Product Status', 'simple-ecommerce'); ?></label>
                        <select id="_product_status" name="_product_status">
                            <option value="active" <?php selected($product_status, 'active'); ?>><?php _e('Active', 'simple-ecommerce'); ?></option>
                            <option value="inactive" <?php selected($product_status, 'inactive'); ?>><?php _e('Inactive', 'simple-ecommerce'); ?></option>
                            <option value="draft" <?php selected($product_status, 'draft'); ?>><?php _e('Draft', 'simple-ecommerce'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="panel" id="images">
                    <div class="form-field">
                        <label><?php _e('Featured Image', 'simple-ecommerce'); ?></label>
                        <div class="featured-image-container">
                            <?php 
                            $featured_image_id = get_post_thumbnail_id($post->ID);
                            if ($featured_image_id) {
                                $featured_image = wp_get_attachment_image($featured_image_id, 'thumbnail');
                                echo '<div class="featured-image-preview">' . $featured_image . '<button type="button" class="remove-featured-image">×</button></div>';
                            }
                            ?>
                            <button type="button" class="button upload-featured-image" <?php echo $featured_image_id ? 'style="display:none;"' : ''; ?>>
                                <?php _e('Set Featured Image', 'simple-ecommerce'); ?>
                            </button>
                            <input type="hidden" name="_thumbnail_id" value="<?php echo esc_attr($featured_image_id); ?>">
                        </div>
                    </div>

                    <div class="form-field">
                        <label><?php _e('Product Gallery', 'simple-ecommerce'); ?></label>
                        <div class="product-gallery-container">
                            <div class="gallery-images">
                                <?php 
                                if (!empty($gallery_images)) {
                                    $image_ids = explode(',', $gallery_images);
                                    foreach ($image_ids as $image_id) {
                                        if ($image_id) {
                                            $image = wp_get_attachment_image($image_id, 'thumbnail');
                                            echo '<div class="gallery-image" data-id="' . esc_attr($image_id) . '">';
                                            echo $image;
                                            echo '<button type="button" class="remove-gallery-image">×</button>';
                                            echo '</div>';
                                        }
                                    }
                                }
                                ?>
                            </div>
                            <button type="button" class="button upload-gallery-images">
                                <?php _e('Add Gallery Images', 'simple-ecommerce'); ?>
                            </button>
                            <input type="hidden" name="_product_gallery" value="<?php echo esc_attr($gallery_images); ?>">
                        </div>
                    </div>
                </div>


                <div class="panel" id="inventory">
                    <div class="form-field">
                        <label for="_stock"><?php _e('Stock Quantity', 'simple-ecommerce'); ?></label>
                        <input type="number" id="_stock" name="_stock" value="<?php echo esc_attr($stock); ?>" min="0" step="1">
                    </div>

                    <div class="form-field">
                        <label for="_stock_status"><?php _e('Stock Status', 'simple-ecommerce'); ?></label>
                        <select id="_stock_status" name="_stock_status">
                            <option value="instock" <?php selected(get_post_meta($post->ID, '_stock_status', true), 'instock'); ?>><?php _e('In stock', 'simple-ecommerce'); ?></option>
                            <option value="outofstock" <?php selected(get_post_meta($post->ID, '_stock_status', true), 'outofstock'); ?>><?php _e('Out of stock', 'simple-ecommerce'); ?></option>
                            <option value="onbackorder" <?php selected(get_post_meta($post->ID, '_stock_status', true), 'onbackorder'); ?>><?php _e('On backorder', 'simple-ecommerce'); ?></option>
                        </select>
                    </div>
                </div>                <div class="panel" id="shipping">
                    <div class="form-field">
                        <label for="_weight"><?php _e('Weight (kg)', 'simple-ecommerce'); ?></label>
                        <input type="text" id="_weight" name="_weight" value="<?php echo esc_attr($weight); ?>">
                    </div>

                    <div class="form-field dimensions-field">
                        <label><?php _e('Dimensions (cm)', 'simple-ecommerce'); ?></label>
                        <input type="text" id="_length" name="_dimensions[length]"
                            value="<?php echo isset($dimensions['length']) ? esc_attr($dimensions['length']) : ''; ?>"
                            placeholder="Length">
                        <input type="text" id="_width" name="_dimensions[width]"
                            value="<?php echo isset($dimensions['width']) ? esc_attr($dimensions['width']) : ''; ?>"
                            placeholder="Width">
                        <input type="text" id="_height" name="_dimensions[height]"
                            value="<?php echo isset($dimensions['height']) ? esc_attr($dimensions['height']) : ''; ?>"
                            placeholder="Height">
                    </div>
                </div>

                <div class="panel" id="categories">
                    <div class="form-field">
                        <label><?php _e('Product Categories', 'simple-ecommerce'); ?></label>
                        <div class="categories-container">
                            <?php if (!empty($categories)) : ?>
                                <?php foreach ($categories as $category) : ?>
                                    <label class="category-item">
                                        <input type="checkbox" name="product_category[]" value="<?php echo esc_attr($category->term_id); ?>" 
                                               <?php checked(in_array($category->term_id, $selected_categories)); ?>>
                                        <?php echo esc_html($category->name); ?>
                                    </label>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p><?php _e('No categories found. Please create categories first.', 'simple-ecommerce'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>        <script>
            jQuery(document).ready(function ($) {

                $('.product-meta-box-tabs a').on('click', function (e) {
                    e.preventDefault();

                    $('.product-meta-box-tabs li').removeClass('active');
                    $(this).parent('li').addClass('active');

                    var targetTab = $(this).data('tab');
                    $('.product-meta-box-panels .panel').removeClass('active');
                    $('#' + targetTab).addClass('active');
                });

                function updatePrice() {
                    var regularPrice = parseFloat($('#_regular_price').val()) || 0;
                    var salePrice = parseFloat($('#_sale_price').val()) || 0;

                    if (salePrice > 0 && salePrice < regularPrice) {
                        return salePrice;
                    } else {
                        return regularPrice;
                    }
                }

                $('form#post').on('submit', function () {
                    var price = updatePrice();
                    $('<input>').attr({
                        type: 'hidden',
                        name: '_price',
                        value: price
                    }).appendTo('form#post');
                });

                // Featured Image Upload
                $('.upload-featured-image').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var custom_uploader = wp.media({
                        title: '<?php _e('Select Featured Image', 'simple-ecommerce'); ?>',
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: '<?php _e('Use this image', 'simple-ecommerce'); ?>'
                        },
                        multiple: false
                    });

                    custom_uploader.on('select', function() {
                        var attachment = custom_uploader.state().get('selection').first().toJSON();
                        $('.featured-image-container input[name="_thumbnail_id"]').val(attachment.id);
                        $('.featured-image-container .featured-image-preview').remove();
                        $('.featured-image-container').prepend(
                            '<div class="featured-image-preview">' +
                            '<img src="' + attachment.sizes.thumbnail.url + '" style="max-width: 150px; height: auto;">' +
                            '<button type="button" class="remove-featured-image">×</button>' +
                            '</div>'
                        );
                        button.hide();
                    });

                    custom_uploader.open();
                });

                // Remove Featured Image
                $(document).on('click', '.remove-featured-image', function(e) {
                    e.preventDefault();
                    $('.featured-image-container input[name="_thumbnail_id"]').val('');
                    $('.featured-image-preview').remove();
                    $('.upload-featured-image').show();
                });                // Gallery Images Upload
                $('.upload-gallery-images').on('click', function(e) {
                    e.preventDefault();
                    
                    var custom_uploader = wp.media({
                        title: '<?php _e('Add Gallery Images', 'simple-ecommerce'); ?>',
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: '<?php _e('Add to gallery', 'simple-ecommerce'); ?>'
                        },
                        multiple: true
                    });

                    custom_uploader.on('select', function() {
                        var attachments = custom_uploader.state().get('selection').toJSON();
                        var galleryIds = [];
                        
                        // Get existing gallery IDs
                        var currentGallery = $('input[name="_product_gallery"]').val();
                        if (currentGallery) {
                            galleryIds = currentGallery.split(',');
                        }

                        attachments.forEach(function(attachment) {
                            if (galleryIds.indexOf(attachment.id.toString()) === -1) {
                                galleryIds.push(attachment.id);
                                $('.gallery-images').append(
                                    '<div class="gallery-image" data-id="' + attachment.id + '">' +
                                    '<img src="' + attachment.sizes.thumbnail.url + '" style="max-width: 100px; height: auto;">' +
                                    '<button type="button" class="remove-gallery-image">×</button>' +
                                    '</div>'
                                );
                            }
                        });

                        $('input[name="_product_gallery"]').val(galleryIds.join(','));
                        updateGalleryOrder();
                    });

                    custom_uploader.open();
                });

                // Remove Gallery Image
                $(document).on('click', '.remove-gallery-image', function(e) {
                    e.preventDefault();
                    var imageDiv = $(this).closest('.gallery-image');
                    var imageId = imageDiv.data('id');
                    var galleryIds = $('input[name="_product_gallery"]').val().split(',');
                    
                    galleryIds = galleryIds.filter(function(id) {
                        return id != imageId;
                    });
                    
                    $('input[name="_product_gallery"]').val(galleryIds.join(','));
                    imageDiv.remove();
                });

                // Make gallery images sortable
                $('.gallery-images').sortable({
                    placeholder: 'gallery-image-placeholder',
                    update: function(event, ui) {
                        updateGalleryOrder();
                    }
                });

                // Update gallery order
                function updateGalleryOrder() {
                    var galleryIds = [];
                    $('.gallery-image').each(function() {
                        galleryIds.push($(this).data('id'));
                    });
                    $('input[name="_product_gallery"]').val(galleryIds.join(','));
                }
            });
        </script>

        <style>
            .product-meta-box-container {
                background: #fff;
                margin-top: 10px;
            }

            .product-meta-box-tabs ul {
                display: flex;
                margin: 0;
                padding: 0;
                border-bottom: 1px solid #ddd;
            }

            .product-meta-box-tabs li {
                margin: 0;
                display: block;
            }

            .product-meta-box-tabs a {
                padding: 10px 15px;
                display: block;
                text-decoration: none;
                color: #666;
                border-bottom: 2px solid transparent;
            }

            .product-meta-box-tabs li.active a {
                border-bottom-color: #0073aa;
                color: #0073aa;
            }

            .product-meta-box-panels {
                padding: 15px;
            }

            .product-meta-box-panels .panel {
                display: none;
            }

            .product-meta-box-panels .panel.active {
                display: block;
            }

            .form-field {
                margin-bottom: 15px;
            }

            .form-field label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            .form-field input,
            .form-field select {
                width: 100%;
                max-width: 400px;
                padding: 6px;
            }

            .dimensions-field input {
                width: 30%;
                margin-right: 3%;
                display: inline-block;
            }            .description {
                color: #666;
                font-style: italic;
                margin: 5px 0 0;
            }

            /* Featured Image Styles */
            .featured-image-container {
                position: relative;
            }

            .featured-image-preview {
                position: relative;
                display: inline-block;
                margin-bottom: 10px;
            }

            .featured-image-preview img {
                max-width: 150px;
                height: auto;
                border: 1px solid #ddd;
                border-radius: 4px;
            }

            .remove-featured-image {
                position: absolute;
                top: -8px;
                right: -8px;
                background: #dc3232;
                color: white;
                border: none;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                line-height: 1;
                cursor: pointer;
                font-size: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .remove-featured-image:hover {
                background: #a00;
            }

            /* Gallery Images Styles */
            .product-gallery-container {
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 15px;
                background: #f9f9f9;
            }            .gallery-images {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-bottom: 15px;
                min-height: 50px;
            }

            .gallery-image {
                position: relative;
                display: inline-block;
                cursor: move;
            }

            .gallery-image img {
                max-width: 100px;
                height: auto;
                border: 1px solid #ddd;
                border-radius: 4px;
            }

            .gallery-image-placeholder {
                width: 100px;
                height: 100px;
                border: 2px dashed #ccc;
                background: #f9f9f9;
                display: inline-block;
                margin: 5px;
                border-radius: 4px;
            }

            .remove-gallery-image {
                position: absolute;
                top: -8px;
                right: -8px;
                background: #dc3232;
                color: white;
                border: none;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                line-height: 1;
                cursor: pointer;
                font-size: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .remove-gallery-image:hover {
                background: #a00;
            }

            /* Categories Styles */
            .categories-container {
                max-height: 200px;
                overflow-y: auto;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 10px;
                background: #fff;
            }

            .category-item {
                display: block !important;
                margin-bottom: 8px;
                font-weight: normal !important;
                cursor: pointer;
            }

            .category-item input[type="checkbox"] {
                margin-right: 8px;
                width: auto !important;
            }

            .category-item:hover {
                background-color: #f0f0f0;
                padding: 2px 4px;
                border-radius: 3px;
            }
        </style>
        <?php
    }    public function save_meta_boxes($post_id, $post)
    {

        if (!isset($_POST['simple_product_nonce']) || !wp_verify_nonce($_POST['simple_product_nonce'], 'simple_product_save')) {
            return $post_id;
        }

        if ('product' != $post->post_type || !current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        $meta_fields = array(
            '_regular_price',
            '_sale_price',
            '_sku',
            '_stock',
            '_stock_status',
            '_weight',
            '_short_description',
            '_product_status',
            '_product_gallery'
        );

        foreach ($meta_fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }

        // Handle dimensions array
        if (isset($_POST['_dimensions']) && is_array($_POST['_dimensions'])) {
            $dimensions = array_map('sanitize_text_field', $_POST['_dimensions']);
            update_post_meta($post_id, '_dimensions', $dimensions);
        }

        // Handle featured image
        if (isset($_POST['_thumbnail_id'])) {
            $thumbnail_id = intval($_POST['_thumbnail_id']);
            if ($thumbnail_id > 0) {
                set_post_thumbnail($post_id, $thumbnail_id);
            } else {
                delete_post_thumbnail($post_id);
            }
        }

        // Handle product categories
        if (isset($_POST['product_category']) && is_array($_POST['product_category'])) {
            $categories = array_map('intval', $_POST['product_category']);
            wp_set_post_terms($post_id, $categories, 'product_category');
        } else {
            // If no categories selected, remove all category associations
            wp_set_post_terms($post_id, array(), 'product_category');
        }

        // Calculate and save price
        $regular_price = isset($_POST['_regular_price']) ? floatval($_POST['_regular_price']) : 0;
        $sale_price = isset($_POST['_sale_price']) && !empty($_POST['_sale_price']) ? floatval($_POST['_sale_price']) : 0;

        $price = $regular_price;
        if ($sale_price > 0 && $sale_price < $regular_price) {
            $price = $sale_price;
        }

        update_post_meta($post_id, '_price', $price);
    }
}