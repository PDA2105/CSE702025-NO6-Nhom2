<?php


class Simple_Ecommerce_Cart
{

    private $cart_key = 'simple_ecommerce_cart';

    public function __construct()
    {
        if (!is_admin()) {
            add_action('init', array($this, 'initialize_cart'));
        }
    }
    public function initialize_cart()
    {
        if (!$this->get_cart()) {
            $this->set_empty_cart();
        }
    }

    public function get_cart()
    {
        if (is_user_logged_in()) {
            return get_user_meta(get_current_user_id(), $this->cart_key, true);
        } else {
            if (isset($_SESSION[$this->cart_key])) {
                return $_SESSION[$this->cart_key];
            }

            if (isset($_COOKIE[$this->cart_key])) {
                return json_decode(stripslashes($_COOKIE[$this->cart_key]), true);
            }
        }
        return false;
    }

    private function save_cart($cart)
    {
        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), $this->cart_key, $cart);
        } else {
            $_SESSION[$this->cart_key] = $cart;
            setcookie(
                $this->cart_key,
                json_encode($cart),
                time() + (7 * DAY_IN_SECONDS),
                COOKIEPATH,
                COOKIE_DOMAIN
            );
        }
    }

    private function set_empty_cart()
    {
        $cart = array(
            'items' => array(),
            'total' => 0,
            'count' => 0,
        );
        $this->save_cart($cart);
        return $cart;
    }
    public function remove_item($product_id)
    {
        $cart = $this->get_cart();

        if (!isset($cart['items'][$product_id])) {
            return false;
        }

        unset($cart['items'][$product_id]);

        // Tính lại tổng và số lượng
        $this->calculate_totals($cart);

        // Lưu lại giỏ hàng
        $this->save_cart($cart);

        return true;
    }
    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add_item($product_id, $quantity = 1)
    {
        try {
            $cart = $this->get_cart();
            $product = get_post($product_id);

            if (!$product || $product->post_type !== 'product') {
                return false;
            }

            $price = (float) get_post_meta($product_id, '_price', true);

            if (isset($cart['items'][$product_id])) {
                // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng
                $cart['items'][$product_id]['quantity'] += $quantity;
                $cart['items'][$product_id]['subtotal'] = $price * $cart['items'][$product_id]['quantity'];
            } else {
                // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
                $cart['items'][$product_id] = array(
                    'id' => $product_id,
                    'name' => $product->post_title,
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $price * $quantity
                );
            }

            // Cập nhật tổng giỏ hàng
            $this->calculate_totals($cart);

            // Lưu giỏ hàng vào session
            $this->save_cart($cart);

            return true;
        } catch (Exception $e) {
            error_log('Simple Ecommerce - Error adding item to cart: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update_cart($product_id, $quantity)
    {
        $cart = $this->get_cart();

        if (!isset($cart['items'][$product_id])) {
            return false;
        }

        if ($quantity <= 0) {
            // Nếu số lượng <= 0, xóa sản phẩm khỏi giỏ hàng
            unset($cart['items'][$product_id]);
        } else {
            // Cập nhật số lượng và tính lại subtotal
            $cart['items'][$product_id]['quantity'] = $quantity;
            $cart['items'][$product_id]['subtotal'] = (float) $cart['items'][$product_id]['price'] * $quantity;
        }

        // Tính lại tổng giá trị giỏ hàng
        $cart['total'] = 0;
        foreach ($cart['items'] as $item) {
            $cart['total'] += (float) $item['subtotal'];
        }

        // Lưu giỏ hàng vào session
        $this->save_cart($cart);

        return true;
    }

    public function clear_cart()
    {
        return $this->set_empty_cart();
    }

    private function calculate_totals(&$cart)
    {
        $cart['total'] = 0;
        $cart['count'] = 0;

        foreach ($cart['items'] as $item) {
            $cart['total'] += $item['subtotal'];
            $cart['count'] += $item['quantity'];
        }
    }    public function checkout($user_data)
    {
        $cart = $this->get_cart();
        if (!$cart || empty($cart['items'])) {
            return false;
        }

        $user_id = is_user_logged_in() ? get_current_user_id() : 0;

        // Tạo đơn hàng với thông tin khách hàng
        $order_model = new Simple_Ecommerce_Order();
        $order_data = array_merge($user_data, array('user_id' => $user_id));
        
        $order_id = $order_model->create_order($order_data, $cart);

        if ($order_id) {            // Cập nhật số lượng sản phẩm
            $product_model = new Simple_Ecommerce_Product();
            foreach ($cart['items'] as $item) {
                $product_model->update_stock($item['id'], $item['quantity']);
            }

            // Xóa giỏ hàng sau khi đặt hàng thành công
            $this->clear_cart();

            return $order_id;
        }

        return false;
    }

}
