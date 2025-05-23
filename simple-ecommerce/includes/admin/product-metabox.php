<?php
// filepath: includes/admin/class-product-meta-box.php

/**
 * Product Meta Box Handler
 */
class Simple_Ecommerce_Product_Meta_Box {

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
    }

    /**
     * Register the meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'simple_product_data',
            __('Product Data', 'simple-ecommerce'),
            array($this, 'render_product_meta_box'),
            'product',
            'normal',
            'high'
        );
    }

    /**
     * Render the product meta box
     */
    public function render_product_meta_box($post) {
        // Add a nonce field for security
        wp_nonce_field('simple_product_save', 'simple_product_nonce');

        // Get current values
        $price = get_post_meta($post->ID, '_price', true);
        $regular_price = get_post_meta($post->ID, '_regular_price', true);
        $sale_price = get_post_meta($post->ID, '_sale_price', true);
        $stock = get_post_meta($post->ID, '_stock', true);
        $sku = get_post_meta($post->ID, '_sku', true);
        $weight = get_post_meta($post->ID, '_weight', true);
        $dimensions = get_post_meta($post->ID, '_dimensions', true);
        ?>
        <div class="product-meta-box-container">
            <!-- Tabs for organization -->
            <div class="product-meta-box-tabs">
                <ul>
                    <li class="active"><a href="#general" data-tab="general">General</a></li>
                    <li><a href="#inventory" data-tab="inventory">Inventory</a></li>
                    <li><a href="#shipping" data-tab="shipping">Shipping</a></li>
                </ul>
            </div>
            
            <!-- Tab content -->
            <div class="product-meta-box-panels">
                <!-- General tab -->
                <div class="panel active" id="general">
                    <div class="form-field">
                        <label for="_regular_price"><?php _e('Regular Price ($)', 'simple-ecommerce'); ?></label>
                        <input type="text" id="_regular_price" name="_regular_price" value="<?php echo esc_attr($regular_price); ?>" class="regular-price">
                    </div>
                    
                    <div class="form-field">
                        <label for="_sale_price"><?php _e('Sale Price ($)', 'simple-ecommerce'); ?></label>
                        <input type="text" id="_sale_price" name="_sale_price" value="<?php echo esc_attr($sale_price); ?>" class="sale-price">
                    </div>
                    
                    <div class="form-field">
                        <label for="_sku"><?php _e('SKU', 'simple-ecommerce'); ?></label>
                        <input type="text" id="_sku" name="_sku" value="<?php echo esc_attr($sku); ?>">
                        <p class="description"><?php _e('Stock Keeping Unit. A unique identifier for this product.', 'simple-ecommerce'); ?></p>
                    </div>
                </div>
                
                <!-- Inventory tab -->
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
                </div>
                
                <!-- Shipping tab -->
                <div class="panel" id="shipping">
                    <div class="form-field">
                        <label for="_weight"><?php _e('Weight (kg)', 'simple-ecommerce'); ?></label>
                        <input type="text" id="_weight" name="_weight" value="<?php echo esc_attr($weight); ?>">
                    </div>
                    
                    <div class="form-field dimensions-field">
                        <label><?php _e('Dimensions (cm)', 'simple-ecommerce'); ?></label>
                        <input type="text" id="_length" name="_dimensions[length]" value="<?php echo isset($dimensions['length']) ? esc_attr($dimensions['length']) : ''; ?>" placeholder="Length">
                        <input type="text" id="_width" name="_dimensions[width]" value="<?php echo isset($dimensions['width']) ? esc_attr($dimensions['width']) : ''; ?>" placeholder="Width">
                        <input type="text" id="_height" name="_dimensions[height]" value="<?php echo isset($dimensions['height']) ? esc_attr($dimensions['height']) : ''; ?>" placeholder="Height">
                    </div>
                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Handle tab switching
            $('.product-meta-box-tabs a').on('click', function(e) {
                e.preventDefault();
                
                // Update active tab
                $('.product-meta-box-tabs li').removeClass('active');
                $(this).parent('li').addClass('active');
                
                // Show selected panel
                var targetTab = $(this).data('tab');
                $('.product-meta-box-panels .panel').removeClass('active');
                $('#' + targetTab).addClass('active');
            });
            
            // Update the _price field based on regular and sale price
            function updatePrice() {
                var regularPrice = parseFloat($('#_regular_price').val()) || 0;
                var salePrice = parseFloat($('#_sale_price').val()) || 0;
                
                if (salePrice > 0 && salePrice < regularPrice) {
                    return salePrice;
                } else {
                    return regularPrice;
                }
            }
            
            // Update price on form submit
            $('form#post').on('submit', function() {
                var price = updatePrice();
                $('<input>').attr({
                    type: 'hidden',
                    name: '_price',
                    value: price
                }).appendTo('form#post');
            });
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
        }
        .description {
            color: #666;
            font-style: italic;
            margin: 5px 0 0;
        }
        </style>
        <?php
    }

    /**
     * Save the meta box data
     */
    public function save_meta_boxes($post_id, $post) {
        // Check if nonce is set
        if (!isset($_POST['simple_product_nonce']) || !wp_verify_nonce($_POST['simple_product_nonce'], 'simple_product_save')) {
            return $post_id;
        }

        // Check if user has permission
        if ('product' != $post->post_type || !current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        // Don't save during autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Save meta fields
        $meta_fields = array(
            '_regular_price',
            '_sale_price',
            '_sku',
            '_stock',
            '_stock_status',
            '_weight'
        );

        foreach ($meta_fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }

        // Save dimensions as array
        if (isset($_POST['_dimensions']) && is_array($_POST['_dimensions'])) {
            $dimensions = array_map('sanitize_text_field', $_POST['_dimensions']);
            update_post_meta($post_id, '_dimensions', $dimensions);
        }

        // Calculate and save actual price (regular or sale if set)
        $regular_price = isset($_POST['_regular_price']) ? floatval($_POST['_regular_price']) : 0;
        $sale_price = isset($_POST['_sale_price']) && !empty($_POST['_sale_price']) ? floatval($_POST['_sale_price']) : 0;
        
        $price = $regular_price;
        if ($sale_price > 0 && $sale_price < $regular_price) {
            $price = $sale_price;
        }
        
        update_post_meta($post_id, '_price', $price);
    }
}