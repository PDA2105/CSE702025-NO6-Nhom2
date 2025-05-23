<?php
// filepath: includes/class-simple-ecommerce-helpers.php

class Simple_Ecommerce_Helpers {
    /**
     * Format price with currency symbol
     *
     * @param float $price The price to format
     * @return string Formatted price with currency symbol
     */
    public static function format_price($price) {
        $currency_symbol = apply_filters('simple_ecommerce_currency_symbol', 'đ');
        $decimals = apply_filters('simple_ecommerce_price_decimals', 0);
        $decimal_separator = apply_filters('simple_ecommerce_decimal_separator', '.');
        $thousand_separator = apply_filters('simple_ecommerce_thousand_separator', ',');
        
        $formatted_price = number_format($price, $decimals, $decimal_separator, $thousand_separator);
        
        return $formatted_price." ".$currency_symbol;
    }
    
    /**
     * Generate a unique ID
     *
     * @return string Unique ID
     */
    public static function generate_unique_id() {
        return md5(uniqid('simple_ecommerce', true));
    }
}