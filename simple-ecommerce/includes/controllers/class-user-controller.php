<?php

class Simple_Ecommerce_User_Controller
{

    public function __construct()
    {

        add_action('init', array($this, 'register_customer_role'));

        add_shortcode('simple_login', array($this, 'login_shortcode'));
        add_shortcode('simple_register', array($this, 'register_shortcode'));
        add_shortcode('simple_my_account', array($this, 'my_account_shortcode'));

        add_action('template_redirect', array($this, 'redirect_checkout'));
    }


    public function register_customer_role()
    {

        if (get_option('simple_ecommerce_customer_role_added')) {
            return;
        }

        add_role(
            'customer',
            'Customer',
            get_role('subscriber')->capabilities
        );

        update_option('simple_ecommerce_customer_role_added', true);
    }


    public function login_shortcode()
    {
        ob_start();
        include_once plugin_dir_path(dirname(__FILE__)) . 'views/public/login.php';
        return ob_get_clean();
    }


    public function register_shortcode()
    {
        ob_start();
        include_once plugin_dir_path(dirname(__FILE__)) . 'views/public/register.php';
        return ob_get_clean();
    }


    public function my_account_shortcode()
    {
        ob_start();
        include_once plugin_dir_path(dirname(__FILE__)) . 'views/public/my-account.php';
        return ob_get_clean();
    }


    public function redirect_checkout()
    {

        if (is_page('checkout') && !is_user_logged_in()) {
            wp_redirect(home_url('/login/?redirect_to=' . urlencode(home_url('/checkout/'))));
            exit;
        }
    }


    public function get_user_data($user_id = null)
    {
        if (!$user_id && is_user_logged_in()) {
            $user_id = get_current_user_id();
        }

        if (!$user_id) {
            return false;
        }

        $user = get_userdata($user_id);

        if (!$user) {
            return false;
        }

        return array(
            'id' => $user->ID,
            'username' => $user->user_login,
            'email' => $user->user_email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'display_name' => $user->display_name,
            'billing_address' => get_user_meta($user_id, 'billing_address', true),
            'shipping_address' => get_user_meta($user_id, 'shipping_address', true),
        );
    }


    public function update_user_address($user_id, $address_type, $address)
    {
        if (!in_array($address_type, array('billing_address', 'shipping_address'))) {
            return false;
        }

        return update_user_meta($user_id, $address_type, $address);
    }
}