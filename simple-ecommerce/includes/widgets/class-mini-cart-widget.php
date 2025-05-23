<?php
// filepath: includes/widgets/class-mini-cart-widget.php

/**
 * Mini Cart Widget
 */
class Simple_Ecommerce_Mini_Cart_Widget extends WP_Widget {
    
    /**
     * Register widget with WordPress
     */
    public function __construct() {
        parent::__construct(
            'simple_ecommerce_mini_cart',
            __('Simple Ecommerce Mini Cart', 'simple-ecommerce'),
            array('description' => __('Displays a mini cart for your store', 'simple-ecommerce'))
        );
    }
    
    /**
     * Front-end display of widget
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        $cart = new Simple_Ecommerce_Cart();
        $cart_data = $cart->get_cart();
        
        ?>
        <div class="mini-cart-widget">
            <div class="mini-cart-header">
                <a href="<?php echo home_url('/cart/'); ?>" class="mini-cart-link">
                    <span class="dashicons dashicons-cart"></span>
                    
                </a>
            </div>
        </div>
        <?php
        
        echo $args['after_widget'];
    }
    
    /**
     * Back-end widget form
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Cart', 'simple-ecommerce');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'simple-ecommerce'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }
    
    /**
     * Sanitize widget form values as they are saved
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        
        return $instance;
    }
}

// Register the widget
function register_simple_ecommerce_mini_cart_widget() {
    register_widget('Simple_Ecommerce_Mini_Cart_Widget');
}
add_action('widgets_init', 'register_simple_ecommerce_mini_cart_widget');