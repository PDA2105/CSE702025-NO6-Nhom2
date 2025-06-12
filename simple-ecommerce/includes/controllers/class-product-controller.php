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
        
        // AJAX handlers for sorting
        add_action('wp_ajax_sort_products', array($this, 'ajax_sort_products'));
        add_action('wp_ajax_nopriv_sort_products', array($this, 'ajax_sort_products'));

    }


    public function products_shortcode($atts)
    {        $atts = shortcode_atts(array(
            'category' => '',
            'limit' => 12,
            'columns' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
            'view' => 'grid',
        ), $atts, 'simple_products');

        // Handle URL parameters for sorting
        if (isset($_GET['orderby'])) {
            $atts['orderby'] = sanitize_text_field($_GET['orderby']);
        }
        if (isset($_GET['order'])) {
            $atts['order'] = sanitize_text_field($_GET['order']);
        }

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );

        // Handle price sorting
        if ($atts['orderby'] === 'meta_value_num') {
            $args['meta_key'] = '_regular_price';
            $args['meta_query'] = array(
                array(
                    'key' => '_regular_price',
                    'compare' => 'EXISTS',
                    'type' => 'NUMERIC'
                )
            );
        }

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
    }    function products_grid_shortcode($atts)
    {
        $atts = shortcode_atts(array(
            'category' => '',
            'limit' => 12,
            'columns' => 4,
            'orderby' => 'date',
            'order' => 'DESC',
        ), $atts, 'simple_product_grid');

        // Handle URL parameters for sorting
        if (isset($_GET['orderby'])) {
            $atts['orderby'] = sanitize_text_field($_GET['orderby']);
        }
        if (isset($_GET['order'])) {
            $atts['order'] = sanitize_text_field($_GET['order']);
        }

        $paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'paged' => $paged,
        );

        // Handle price sorting
        if ($atts['orderby'] === 'meta_value_num') {
            $args['meta_key'] = '_sale_price';
            $args['meta_query'] = array(
                array(
                    'key' => '_sale_price',
                    'compare' => 'EXISTS',
                    'type' => 'NUMERIC'
                )
            );
        }

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

        // DEBUG: Log query results
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[PAGINATION DEBUG] products_grid_shortcode:');
            error_log('  - limit: ' . $atts['limit']);
            error_log('  - found_posts: ' . $products->found_posts);
            error_log('  - post_count: ' . $products->post_count);
            error_log('  - max_num_pages: ' . $products->max_num_pages);
            error_log('  - paged: ' . $paged);
            error_log('  - query_vars[paged]: ' . $products->query_vars['paged']);
        }

        ob_start();

        // Set attributes for the view, including pagination info
        $atts['paged'] = $paged;

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
        }        wp_die();
    }    public function ajax_sort_products()
    {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'simple_ecommerce_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed'));
            return;
        }

        $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date';
        $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 12;
        $columns = isset($_POST['columns']) ? intval($_POST['columns']) : 4;
        $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $limit,
            'orderby' => $orderby,
            'order' => $order,
            'paged' => $paged,
        );

        // Handle price sorting
        if ($orderby === 'meta_value_num') {
            $args['meta_key'] = '_sale_price';
            $args['meta_query'] = array(
                array(
                    'key' => '_sale_price',
                    'compare' => 'EXISTS',
                    'type' => 'NUMERIC'
                )
            );
        }

        if (!empty($category)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_category',
                    'field' => 'slug',
                    'terms' => explode(',', $category),
                ),
            );
        }

        $products = new WP_Query($args);

        ob_start();
        
        // Set attributes for the view, including pagination info
        $atts = array(
            'columns' => $columns,
            'orderby' => $orderby,
            'order' => $order,
            'limit' => $limit,
            'paged' => $paged,
            'category' => $category
        );
        
        include plugin_dir_path(dirname(__FILE__)) . 'views/public/product-grid-ajax.php';
        
        $html = ob_get_clean();

        // Prepare pagination HTML separately
        $total_pages = $products->max_num_pages;
        $pagination_html = '';
        
        if ($total_pages > 1) {
            ob_start();
            echo '<div class="simple-pagination">';
            echo paginate_links(array(
                'base' => '%_%',
                'format' => 'page/%#%/',
                'current' => $paged,
                'total' => $total_pages,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
                'type' => 'plain',
                'add_args' => false
            ));
            echo '</div>';
            $pagination_html = ob_get_clean();
        }

        wp_send_json_success(array(
            'html' => $html,
            'pagination' => $pagination_html,
            'found_posts' => $products->found_posts,
            'current_page' => $paged,
            'total_pages' => $total_pages,
            'posts_per_page' => $limit
        ));

        wp_die();
    }

}
