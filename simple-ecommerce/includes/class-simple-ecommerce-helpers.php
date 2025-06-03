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
        // Đảm bảo price là một số, không phải mảng
        if (is_array($price)) {
            $price = 0; // hoặc xử lý mảng theo cách phù hợp
        }
        
        $currency_symbol = apply_filters('simple_ecommerce_currency_symbol', 'đ');
        $decimals = apply_filters('simple_ecommerce_price_decimals', 0);
        $decimal_separator = apply_filters('simple_ecommerce_decimal_separator', ',');
        $thousand_separator = apply_filters('simple_ecommerce_thousand_separator', '.');
        
        $formatted_price = number_format((float)$price, $decimals, $decimal_separator, $thousand_separator);
        
        return $formatted_price." ".$currency_symbol;
    }
      /**
     * Get district names mapping
     */
    public static function get_district_names() {
        return array(
            // Hà Nội
            'ba_vi' => 'Ba Vì',
            'cau_giay' => 'Cầu Giấy',
            'dong_da' => 'Đống Đa',
            'ha_dong' => 'Hà Đông',
            'hai_ba_trung' => 'Hai Bà Trưng',
            'hoan_kiem' => 'Hoàn Kiếm',
            'hoang_mai' => 'Hoàng Mai',
            'long_bien' => 'Long Biên',
            'nam_tu_liem' => 'Nam Từ Liêm',
            'tay_ho' => 'Tây Hồ',
            'thanh_xuan' => 'Thanh Xuân',
            'bac_tu_liem' => 'Bắc Từ Liêm',
            'chuong_my' => 'Chương Mỹ',
            'dan_phuong' => 'Đan Phượng',
            'dong_anh' => 'Đông Anh',
            'gia_lam' => 'Gia Lâm',
            'hoai_duc' => 'Hoài Đức',
            'me_linh' => 'Mê Linh',
            'my_duc' => 'Mỹ Đức',
            'phuc_tho' => 'Phúc Thọ',
            'quoc_oai' => 'Quốc Oai',
            'soc_son' => 'Sóc Sơn',
            'thach_that' => 'Thạch Thất',
            'thuong_tin' => 'Thường Tín',
            'ung_hoa' => 'Ứng Hòa',
            'son_tay' => 'Sơn Tây',
            // TP Hồ Chí Minh
            'quan_1' => 'Quận 1',
            'quan_2' => 'Quận 2',
            'quan_3' => 'Quận 3',
            'quan_4' => 'Quận 4',
            'quan_5' => 'Quận 5',
            'quan_6' => 'Quận 6',
            'quan_7' => 'Quận 7',
            'quan_8' => 'Quận 8',
            'quan_9' => 'Quận 9',
            'quan_10' => 'Quận 10',
            'quan_11' => 'Quận 11',
            'quan_12' => 'Quận 12',
            'binh_thanh' => 'Bình Thạnh',
            'go_vap' => 'Gò Vấp',
            'phu_nhuan' => 'Phú Nhuận',
            'tan_binh' => 'Tân Bình',
            'tan_phu' => 'Tân Phú',
            'thu_duc' => 'Thủ Đức',
            // Đà Nẵng
            'hai_chau' => 'Hải Châu',
            'thanh_khe' => 'Thanh Khê',
            'son_tra' => 'Sơn Trà',
            'ngu_hanh_son' => 'Ngũ Hành Sơn',
            'lien_chieu' => 'Liên Chiểu',
            'cam_le' => 'Cẩm Lệ',
        );
    }
    
    /**
     * Get province names mapping
     */
    public static function get_province_names() {
        return array(
            'ha_noi' => 'Hà Nội',
            'tp_ho_chi_minh' => 'TP Hồ Chí Minh',
            'da_nang' => 'Đà Nẵng',
            'hai_phong' => 'Hải Phòng',
            'can_tho' => 'Cần Thơ',
            'an_giang' => 'An Giang',
            'ba_ria_vung_tau' => 'Bà Rịa - Vũng Tàu',
            'bac_giang' => 'Bắc Giang',
            'bac_kan' => 'Bắc Kạn',
            'bac_lieu' => 'Bạc Liêu',
            'bac_ninh' => 'Bắc Ninh',
            'ben_tre' => 'Bến Tre',
            'binh_dinh' => 'Bình Định',
            'binh_duong' => 'Bình Dương',
            'binh_phuoc' => 'Bình Phước',
            'binh_thuan' => 'Bình Thuận',
            'ca_mau' => 'Cà Mau',
            'cao_bang' => 'Cao Bằng',
            'dak_lak' => 'Đắk Lắk',
            'dak_nong' => 'Đắk Nông',
            'dien_bien' => 'Điện Biên',
            'dong_nai' => 'Đồng Nai',
            'dong_thap' => 'Đồng Tháp',
            'gia_lai' => 'Gia Lai',
            'ha_giang' => 'Hà Giang',
            'ha_nam' => 'Hà Nam',
            'ha_tinh' => 'Hà Tĩnh',
            'hau_giang' => 'Hậu Giang',
            'hoa_binh' => 'Hòa Bình',
            'hung_yen' => 'Hưng Yên',
            'khanh_hoa' => 'Khánh Hòa',
            'kien_giang' => 'Kiên Giang',
            'kon_tum' => 'Kon Tum',
            'lai_chau' => 'Lai Châu',
            'lam_dong' => 'Lâm Đồng',
            'lang_son' => 'Lạng Sơn',
            'lao_cai' => 'Lào Cai',
            'long_an' => 'Long An',
            'nam_dinh' => 'Nam Định',
            'nghe_an' => 'Nghệ An',
            'ninh_binh' => 'Ninh Bình',
            'ninh_thuan' => 'Ninh Thuận',
            'phu_tho' => 'Phú Thọ',
            'phu_yen' => 'Phú Yên',
            'quang_binh' => 'Quảng Bình',
            'quang_nam' => 'Quảng Nam',
            'quang_ngai' => 'Quảng Ngãi',
            'quang_ninh' => 'Quảng Ninh',
            'quang_tri' => 'Quảng Trị',
            'soc_trang' => 'Sóc Trăng',
            'son_la' => 'Sơn La',
            'tay_ninh' => 'Tây Ninh',
            'thai_binh' => 'Thái Bình',
            'thai_nguyen' => 'Thái Nguyên',
            'thanh_hoa' => 'Thanh Hóa',
            'thua_thien_hue' => 'Thừa Thiên Huế',
            'tien_giang' => 'Tiền Giang',
            'tra_vinh' => 'Trà Vinh',
            'tuyen_quang' => 'Tuyên Quang',
            'vinh_long' => 'Vĩnh Long',
            'vinh_phuc' => 'Vĩnh Phúc',
            'yen_bai' => 'Yên Bái',
        );
    }
    
    /**
     * Get district to province mapping
     */
    public static function get_district_to_province() {        return array(
            // Hà Nội
            'ba_vi' => 'Hà Nội',
            'cau_giay' => 'Hà Nội',
            'dong_da' => 'Hà Nội',
            'ha_dong' => 'Hà Nội',
            'hai_ba_trung' => 'Hà Nội',
            'hoan_kiem' => 'Hà Nội',
            'hoang_mai' => 'Hà Nội',
            'long_bien' => 'Hà Nội',
            'nam_tu_liem' => 'Hà Nội',
            'tay_ho' => 'Hà Nội',
            'thanh_xuan' => 'Hà Nội',
            'bac_tu_liem' => 'Hà Nội',
            'chuong_my' => 'Hà Nội',
            'dan_phuong' => 'Hà Nội',
            'dong_anh' => 'Hà Nội',
            'gia_lam' => 'Hà Nội',
            'hoai_duc' => 'Hà Nội',
            'me_linh' => 'Hà Nội',
            'my_duc' => 'Hà Nội',
            'phuc_tho' => 'Hà Nội',
            'quoc_oai' => 'Hà Nội',
            'soc_son' => 'Hà Nội',
            'thach_that' => 'Hà Nội',
            'thuong_tin' => 'Hà Nội',
            'ung_hoa' => 'Hà Nội',
            'son_tay' => 'Hà Nội',
            // TP Hồ Chí Minh
            'quan_1' => 'TP Hồ Chí Minh',
            'quan_2' => 'TP Hồ Chí Minh',
            'quan_3' => 'TP Hồ Chí Minh',
            'quan_4' => 'TP Hồ Chí Minh',
            'quan_5' => 'TP Hồ Chí Minh',
            'quan_6' => 'TP Hồ Chí Minh',
            'quan_7' => 'TP Hồ Chí Minh',
            'quan_8' => 'TP Hồ Chí Minh',
            'quan_9' => 'TP Hồ Chí Minh',
            'quan_10' => 'TP Hồ Chí Minh',
            'quan_11' => 'TP Hồ Chí Minh',
            'quan_12' => 'TP Hồ Chí Minh',
            'binh_thanh' => 'TP Hồ Chí Minh',
            'go_vap' => 'TP Hồ Chí Minh',
            'phu_nhuan' => 'TP Hồ Chí Minh',
            'tan_binh' => 'TP Hồ Chí Minh',
            'tan_phu' => 'TP Hồ Chí Minh',
            'thu_duc' => 'TP Hồ Chí Minh',
            // Đà Nẵng
            'hai_chau' => 'Đà Nẵng',
            'thanh_khe' => 'Đà Nẵng',
            'son_tra' => 'Đà Nẵng',
            'ngu_hanh_son' => 'Đà Nẵng',
            'lien_chieu' => 'Đà Nẵng',
            'cam_le' => 'Đà Nẵng',
        );
    }
      /**
     * Format shipping address to display full names instead of codes
     */    public static function format_shipping_address($shipping_address) {
        if (!is_array($shipping_address)) {
            return $shipping_address;
        }
        
        $district_names = self::get_district_names();
        $province_names = self::get_province_names();
        $district_to_province = self::get_district_to_province();
        
        $address_parts = array();        // Địa chỉ cụ thể
        if (!empty($shipping_address['address'])) {
            $address_parts[] = $shipping_address['address'];
        }        // Quận/huyện
        $district_code = '';
        if (!empty($shipping_address['district'])) {
            $raw_district = $shipping_address['district'];
            $district_code = self::clean_district_input($raw_district);
            
            $district = isset($district_names[$district_code])
                ? $district_names[$district_code]
                : $district_code;
            $address_parts[] = $district;
        }        // Tỉnh/thành phố
        if (!empty($district_code) && isset($district_to_province[$district_code])) {
            $province_name = $district_to_province[$district_code];
            $address_parts[] = $province_name;
        } elseif (!empty($shipping_address['province'])) {
            $province = isset($province_names[$shipping_address['province']])
                ? $province_names[$shipping_address['province']]
                : $shipping_address['province'];
            $address_parts[] = $province;
        }

        return implode(', ', $address_parts);
    }
    
    /**
     * Get reverse mapping from district names to codes
     */
    public static function get_district_name_to_code() {
        $district_names = self::get_district_names();
        return array_flip($district_names);
    }

    /**
     * Clean district input - handle cases where district might be a name instead of code
     */
    public static function clean_district_input($district_input) {
        if (empty($district_input)) {
            return '';
        }
        
        $district_names = self::get_district_names();
        $name_to_code = self::get_district_name_to_code();
        
        // If it's already a valid code, return it
        if (isset($district_names[$district_input])) {
            return $district_input;
        }
        
        // If it's a district name, convert to code
        if (isset($name_to_code[$district_input])) {
            return $name_to_code[$district_input];
        }
        
        // Handle malformed input with đ character issues
        $cleaned = $district_input;
        
        // Try to fix common issues with đ character
        if (strpos($cleaned, 'đ') !== false) {
            $test_cleaned = str_replace('đ', 'd', $cleaned);
            if (isset($district_names[$test_cleaned])) {
                return $test_cleaned;
            }
        }
        
        // If we still can't find it, return original input
        return $district_input;
    }
}

/**
 * Helper function to format price
 */
function simple_format_price($price) {
    return Simple_Ecommerce_Helpers::format_price($price);
}
