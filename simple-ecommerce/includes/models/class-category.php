<?php
// filepath: includes/models/class-category.php

class Simple_Ecommerce_Category {
    
    public function __construct() {
        add_action('init', array($this, 'register_product_category_taxonomy'));
    }
    
    public function register_product_category_taxonomy() {
        $labels = array(
            'name'                       => 'Categories',
            'singular_name'              => 'Category',
            'menu_name'                  => 'Categories',
            'all_items'                  => 'All Categories',
            'parent_item'                => 'Parent Category',
            'parent_item_colon'          => 'Parent Category:',
            'new_item_name'              => 'New Category Name',
            'add_new_item'               => 'Add New Category',
            'edit_item'                  => 'Edit Category',
            'update_item'                => 'Update Category',
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'product-category'),
        );

        register_taxonomy('product_category', array('product'), $args);
    }
    
    public function get_products($category_id) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_category',
                    'field' => 'term_id',
                    'terms' => $category_id,
                ),
            ),
        );
        
        $query = new WP_Query($args);
        return $query->posts;
    }
}