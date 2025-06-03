<?php

class Simple_Ecommerce_Product_Controller
{


    public function __construct()
    {

        add_shortcode('simple_products', array($this, 'products_shortcode'));
        add_shortcode('simple_product', array($this, 'product_detail_shortcode'));
        add_shortcode('simple_product_list', array($this, 'products_list_shortcode'));
        add_shortcode('simple_product_grid', array($this, 'products_grid_shortcode'));

        add_action('wp_ajax_add_to_cart', array($this, 'ajax_add_to_cart'));
        add_action('wp_ajax_nopriv_add_to_cart', array($this, 'ajax_add_to_cart'));

    }


    public function products_shortcode($atts)
    {

        $atts = shortcode_atts(array(
            'category' => '',
            'limit' => 12,
            'columns' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
            'view' => 'grid',
        ), $atts, 'simple_products');


        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );

        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_category',
                    'field' => 'slug',
                    'terms' => explode(',', $atts['category']),
                ),
            );
        }


        $products = new WP_Query($args);

        ob_start();

        if ($atts['view'] === 'list') {
            include plugin_dir_path(dirname(__FILE__)) . 'views/public/product-list.php';
        } else {
            include plugin_dir_path(dirname(__FILE__)) . 'views/public/product-grid.php';
        }


        return ob_get_clean();
    }


    public function product_detail_shortcode($atts)
    {

        $atts = shortcode_atts(array(
            'id' => 0,
            'slug' => '',
        ), $atts, 'simple_product');


        $product_id = 0;

        if ($atts['id'] > 0) {
            $product_id = $atts['id'];
        } elseif (!empty($atts['slug'])) {
            $product = get_page_by_path($atts['slug'], OBJECT, 'product');
            if ($product) {
                $product_id = $product->ID;
            }
        } elseif (is_singular('product')) {
            $product_id = get_the_ID();
        }

        if (!$product_id) {
            return '<p>Product not found.</p>';
        }


        $product_model = new Simple_Ecommerce_Product();
        $product_data = $product_model->get_info($product_id);

        if (!$product_data) {
            return '<p>Product not found.</p>';
        }


        ob_start();


        include plugin_dir_path(dirname(__FILE__)) . 'views/public/product-detail.php';

        return ob_get_clean();
    }

    function products_grid_shortcode($atts)
    {
        $atts = shortcode_atts(array(
            'category' => '',
            'limit' => 12,
            'columns' => 4,
            'orderby' => 'date',
            'order' => 'DESC',
        ), $atts, 'simple_product_grid');


        $paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'paged' => $paged,
        );


        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_category',
                    'field' => 'slug',
                    'terms' => explode(',', $atts['category']),
                ),
            );
        }


        $products = new WP_Query($args);


        ob_start();


        include plugin_dir_path(dirname(__FILE__)) . 'views/public/product-grid.php';


        return ob_get_clean();
    }

    public function ajax_add_to_cart()
    {
        // Kiểm tra nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'simple_ecommerce_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed'));
            return;
        }

        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        if ($product_id <= 0) {
            wp_send_json_error(array('message' => 'Invalid product ID'));
            return;
        }

        // Kiểm tra sản phẩm tồn tại
        $product = get_post($product_id);
        if (!$product || $product->post_type !== 'product') {
            wp_send_json_error(array('message' => 'Product not found'));
            return;
        }

        try {
            $cart = new Simple_Ecommerce_Cart();
            $result = $cart->add_item($product_id, $quantity);

            if ($result) {
                $cart_data = $cart->get_cart();

                wp_send_json_success(array(
                    'message' => 'Product added to cart successfully.',
                    'cart_total' => $cart_data['total'],
                    'cart_count' => $cart_data['count'],
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                ));
            } else {
                wp_send_json_error(array('message' => 'Failed to add product to cart.'));
            }
        } catch (Exception $e) {
            wp_send_json_error(array('message' => 'Error: ' . $e->getMessage()));
        }

        wp_die();
    }

}
