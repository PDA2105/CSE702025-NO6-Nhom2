<?php


class Simple_Ecommerce_Product
{    public function __construct()
    {
        add_action('init', array($this, 'register_product_post_type'));
        add_action('init', array($this, 'register_product_taxonomy'));
    }

    public function register_product_post_type()
    {
        $labels = array(
            'name' => 'Products',
            'singular_name' => 'Product',
            'menu_name' => 'Products',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Product',
            'edit_item' => 'Edit Product',
            'new_item' => 'New Product',
            'view_item' => 'View Product',
            'search_items' => 'Search Products',
            'not_found' => 'No products found',
            'not_found_in_trash' => 'No products found in trash',
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'product'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-cart',
            'show_in_rest' => true,
        );

        register_post_type('product', $args);

        register_post_meta('product', '_price', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'number',
        ));

        register_post_meta('product', '_regular_price', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'number',
        ));

        register_post_meta('product', '_sale_price', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'number',
        ));

        register_post_meta('product', '_stock', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'integer',
        ));

        register_post_meta('product', '_sku', array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
        ));
    }

    public function register_product_taxonomy()
    {
        register_taxonomy('product_category', 'product', array(
            'labels' => array(
                'name' => 'Product Categories',
                'singular_name' => 'Product Category',
                'menu_name' => 'Categories',
                'all_items' => 'All Categories',
                'edit_item' => 'Edit Category',
                'view_item' => 'View Category',
                'update_item' => 'Update Category',
                'add_new_item' => 'Add New Category',
                'new_item_name' => 'New Category Name',
                'search_items' => 'Search Categories',
                'not_found' => 'No categories found',
            ),
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'product-category'),
            'show_in_rest' => true,
        ));
    }

    public function get_info($product_id)
    {
        $product = get_post($product_id);
        if (!$product || $product->post_type !== 'product') {
            return false;
        }

        return array(
            'id' => $product_id,
            'name' => $product->post_title,
            'description' => $product->post_content,
            'price' => get_post_meta($product_id, '_price', true),
            'regular_price' => get_post_meta($product_id, '_regular_price', true),
            'sale_price' => get_post_meta($product_id, '_sale_price', true),
            'stock' => get_post_meta($product_id, '_stock', true),
            'category' => $this->get_product_category($product_id),
            'image' => get_the_post_thumbnail_url($product_id, 'medium'),
        );
    }

    public function update_stock($product_id, $quantity)
    {
        $current_stock = get_post_meta($product_id, '_stock', true);
        if ($current_stock !== '') {
            $new_stock = max(0, $current_stock - $quantity);
            update_post_meta($product_id, '_stock', $new_stock);
            return true;
        }
        return false;
    }

    private function get_product_category($product_id)
    {
        $terms = get_the_terms($product_id, 'product_category');
        if ($terms && !is_wp_error($terms)) {
            return $terms[0]->name;
        }
        return '';
    }

}