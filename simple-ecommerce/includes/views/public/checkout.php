<?php

$cart = new Simple_Ecommerce_Cart();
$cart_items = $cart->get_cart();



if (!$cart_items || empty($cart_items['items'])): ?>
    <p>Your cart is empty.</p>
    <p><a href="<?php echo get_post_type_archive_link('product'); ?>">Continue Shopping</a></p>
<?php else: ?>
    <div class="simple-ecommerce-checkout">
        <div class="order-summary">
            <h3>Đơn hàng của bạn</h3>
            <div class="checkout-items-container">
                <?php foreach ($cart_items['items'] as $product_id => $item): ?>
                    <div class="checkout-item">
                        <div class="item-image">
                            <img src="<?php echo esc_url(get_the_post_thumbnail_url($product_id, 'thumbnail')); ?>"
                                alt="<?php echo esc_attr($item['name']); ?>">
                        </div>
                        <div class="item-info">
                            <h4 class="item-name"><?php echo esc_html($item['name']); ?></h4>
                            <div class="item-quantity">
                                <span>Số lượng: <?php echo esc_html($item['quantity']); ?></span>
                            </div>
                        </div>
                        <div class="item-price-actions">
                            <div class="item-price"><?php echo simple_format_price($item['subtotal']); ?></div>
                            <button class="remove-item" type="button" data-product-id="<?php echo esc_attr($product_id); ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                    <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Xóa
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-totals">
                <div class="total-row">
                    <span>Tạm tính</span>
                    <span><?php echo simple_format_price($cart_items['total']); ?></span>
                </div>
                <div class="total-row">
                    <span>Phí vận chuyển</span>
                    <span>Miễn phí</span>
                </div>
                <div class="total-row grand-total">
                    <span>Tổng cộng</span>
                    <span><?php echo simple_format_price($cart_items['total']); ?></span>
                </div>
            </div>
        </div>

        <div class="checkout-form">
            <h3><span class="icon-user">👤</span> Thông Tin Khách Hàng</h3>
            <form method="post">
                <div class="form-row-group">
                    <div class="form-row">
                        <label for="customer_name">Họ và tên</label>
                        <input type="text" id="customer_name" name="customer_name" required>
                    </div>
                </div>

                <div class="form-row">
                    <label for="customer_email">Email</label>
                    <input type="email" id="customer_email" name="customer_email" required>
                </div>

                <div class="form-row">
                    <label for="customer_phone">Số điện thoại</label>
                    <input type="text" id="customer_phone" name="customer_phone" required>
                </div>

                <h3><span class="icon-location">📍</span>Địa Chỉ Giao Hàng</h3>

                <div class="form-row-group">
                    <div class="form-row half">
                        <label for="customer_city">Tỉnh, Thành Phố</label>
                        <select id="customer_city" name="customer_city" required>
                            <option value="">Chọn tỉnh/thành phố</option>
                            <option value="an_giang">An Giang</option>
                            <option value="ba_ria_vung_tau">Bà Rịa - Vũng Tàu</option>
                            <option value="bac_giang">Bắc Giang</option>
                            <option value="bac_kan">Bắc Kạn</option>
                            <option value="bac_lieu">Bạc Liêu</option>
                            <option value="bac_ninh">Bắc Ninh</option>
                            <option value="ben_tre">Bến Tre</option>
                            <option value="binh_dinh">Bình Định</option>
                            <option value="binh_duong">Bình Dương</option>
                            <option value="binh_phuoc">Bình Phước</option>
                            <option value="binh_thuan">Bình Thuận</option>
                            <option value="ca_mau">Cà Mau</option>
                            <option value="can_tho">Cần Thơ</option>
                            <option value="cao_bang">Cao Bằng</option>
                            <option value="da_nang">Đà Nẵng</option>
                            <option value="dak_lak">Đắk Lắk</option>
                            <option value="dak_nong">Đắk Nông</option>
                            <option value="dien_bien">Điện Biên</option>
                            <option value="dong_nai">Đồng Nai</option>
                            <option value="dong_thap">Đồng Tháp</option>
                            <option value="gia_lai">Gia Lai</option>
                            <option value="ha_giang">Hà Giang</option>
                            <option value="ha_nam">Hà Nam</option>
                            <option value="ha_noi">Hà Nội</option>
                            <option value="ha_tinh">Hà Tĩnh</option>
                            <option value="hai_duong">Hải Dương</option>
                            <option value="hai_phong">Hải Phòng</option>
                            <option value="hau_giang">Hậu Giang</option>
                            <option value="hoa_binh">Hòa Bình</option>
                            <option value="hung_yen">Hưng Yên</option>
                            <option value="khanh_hoa">Khánh Hòa</option>
                            <option value="kien_giang">Kiên Giang</option>
                            <option value="kon_tum">Kon Tum</option>
                            <option value="lai_chau">Lai Châu</option>
                            <option value="lam_dong">Lâm Đồng</option>
                            <option value="lang_son">Lạng Sơn</option>
                            <option value="lao_cai">Lào Cai</option>
                            <option value="long_an">Long An</option>
                            <option value="nam_dinh">Nam Định</option>
                            <option value="nghe_an">Nghệ An</option>
                            <option value="ninh_binh">Ninh Bình</option>
                            <option value="ninh_thuan">Ninh Thuận</option>
                            <option value="phu_tho">Phú Thọ</option>
                            <option value="phu_yen">Phú Yên</option>
                            <option value="quang_binh">Quảng Bình</option>
                            <option value="quang_nam">Quảng Nam</option>
                            <option value="quang_ngai">Quảng Ngãi</option>
                            <option value="quang_ninh">Quảng Ninh</option>
                            <option value="quang_tri">Quảng Trị</option>
                            <option value="soc_trang">Sóc Trăng</option>
                            <option value="son_la">Sơn La</option>
                            <option value="tay_ninh">Tây Ninh</option>
                            <option value="thai_binh">Thái Bình</option>
                            <option value="thai_nguyen">Thái Nguyên</option>
                            <option value="thanh_hoa">Thanh Hóa</option>
                            <option value="thua_thien_hue">Thừa Thiên Huế</option>
                            <option value="tien_giang">Tiền Giang</option>
                            <option value="tp_ho_chi_minh">TP. Hồ Chí Minh</option>
                            <option value="tra_vinh">Trà Vinh</option>
                            <option value="tuyen_quang">Tuyên Quang</option>
                            <option value="vinh_long">Vĩnh Long</option>
                            <option value="vinh_phuc">Vĩnh Phúc</option>
                            <option value="yen_bai">Yên Bái</option>
                        </select>

                    </div>

                    <div class="form-row">
                        <label for="customer_district">Quận/Huyện</label>
                        <select id="customer_district" name="customer_district" required>
                            <option value="">Chọn quận/huyện</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="shipping_address">Địa chỉ cụ thể</label>
                        <textarea id="shipping_address" name="shipping_address" rows="3" required></textarea>
                    </div>

                    <h3><span class="icon-payment">💳</span> Phương Thức Thanh Toán</h3>

                    <div class="payment-methods">
                        <div class="payment-method-option">
                            <input type="radio" id="cod" name="payment_method" value="cod" checked>
                            <label for="cod">
                                <span class="payment-icon">💵</span>
                                <span class="payment-text">Tiền mặt</span>
                            </label>
                        </div>
                        <div class="payment-method-option">
                            <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer">
                            <label for="bank_transfer">
                                <span class="payment-icon">🏦</span>
                                <span class="payment-text">Chuyển khoản</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <label for="order_notes">Ghi chú đơn hàng (tùy chọn)</label>
                        <textarea id="order_notes" name="order_notes" rows="2"></textarea>
                    </div>
                    <div class="form-row">
                        <input type="submit" name="simple_checkout_submit" value="Đặt Hàng">
                    </div>
            </form>
            <script>
                const districtsByCity = {
                    ha_noi: ["Ba Đình", "Hoàn Kiếm", "Đống Đa", "Hai Bà Trưng", "Tây Hồ", "Cầu Giấy", "Thanh Xuân", "Hà Đông", "Long Biên", "Nam Từ Liêm", "Bắc Từ Liêm", "Hoàng Mai", "Sơn Tây", "Ba Vì", "Chương Mỹ", "Đan Phượng", "Đông Anh", "Gia Lâm", "Hoài Đức", "Mê Linh", "Mỹ Đức", "Phú Xuyên", "Phúc Thọ", "Quốc Oai", "Sóc Sơn", "Thạch Thất", "Thanh Oai", "Thanh Trì", "Thường Tín", "Ứng Hòa"],
                    tp_ho_chi_minh: ["Quận 1", "Quận 3", "Quận 4", "Quận 5", "Quận 6", "Quận 7", "Quận 8", "Quận 10", "Quận 11", "Quận 12", "Gò Vấp", "Tân Bình", "Tân Phú", "Bình Thạnh", "Phú Nhuận", "Thủ Đức", "Bình Tân", "Bình Chánh", "Củ Chi", "Hóc Môn", "Nhà Bè", "Cần Giờ"],
                    da_nang: ["Hải Châu", "Thanh Khê", "Sơn Trà", "Ngũ Hành Sơn", "Liên Chiểu", "Cẩm Lệ", "Hòa Vang", "Hoàng Sa"],
                    hai_phong: ["Hồng Bàng", "Ngô Quyền", "Lê Chân", "Hải An", "Kiến An", "Đồ Sơn", "Dương Kinh", "An Dương", "An Lão", "Bạch Long Vĩ", "Cát Hải", "Kiến Thụy", "Thủy Nguyên", "Tiên Lãng", "Vĩnh Bảo"],
                    can_tho: ["Ninh Kiều", "Bình Thủy", "Cái Răng", "Ô Môn", "Thốt Nốt", "Cờ Đỏ", "Phong Điền", "Thới Lai", "Vĩnh Thạnh"],
                    an_giang: ["Long Xuyên", "Châu Đốc", "An Phú", "Châu Phú", "Châu Thành", "Chợ Mới", "Phú Tân", "Tân Châu", "Thoại Sơn", "Tịnh Biên", "Tri Tôn"],
                    ba_ria_vung_tau: ["Vũng Tàu", "Bà Rịa", "Châu Đức", "Côn Đảo", "Đất Đỏ", "Long Điền", "Phú Mỹ", "Xuyên Mộc"],
                    bac_giang: ["Bắc Giang", "Hiệp Hòa", "Lạng Giang", "Lục Nam", "Lục Ngạn", "Sơn Động", "Tân Yên", "Việt Yên", "Yên Dũng", "Yên Thế"],
                    bac_kan: ["Bắc Kạn", "Ba Bể", "Bạch Thông", "Chợ Đồn", "Chợ Mới", "Na Rì", "Ngân Sơn", "Pác Nặm"],
                    bac_lieu: ["Bạc Liêu", "Đông Hải", "Giá Rai", "Hòa Bình", "Hồng Dân", "Phước Long", "Vĩnh Lợi"],
                    bac_ninh: ["Bắc Ninh", "Gia Bình", "Lương Tài", "Quế Võ", "Thuận Thành", "Tiên Du", "Từ Sơn", "Yên Phong"],
                    ben_tre: ["Bến Tre", "Ba Tri", "Bình Đại", "Châu Thành", "Chợ Lách", "Giồng Trôm", "Mỏ Cày Bắc", "Mỏ Cày Nam", "Thạnh Phú"],
                    binh_dinh: ["Quy Nhơn", "An Lão", "An Nhơn", "Hoài Ân", "Hoài Nhơn", "Phù Cát", "Phù Mỹ", "Tây Sơn", "Tuy Phước", "Vân Canh", "Vĩnh Thạnh"],
                    binh_duong: ["Thủ Dầu Một", "Bắc Tân Uyên", "Bàu Bàng", "Bến Cát", "Dầu Tiếng", "Dĩ An", "Phú Giáo", "Tân Uyên", "Thuận An"],
                    binh_phuoc: ["Đồng Xoài", "Bình Long", "Bù Đăng", "Bù Đốp", "Bù Gia Mập", "Chơn Thành", "Đồng Phú", "Hớn Quản", "Lộc Ninh", "Phú Riềng", "Phước Long"],
                    binh_thuan: ["Phan Thiết", "Bắc Bình", "Đức Linh", "Hàm Tân", "Hàm Thuận Bắc", "Hàm Thuận Nam", "La Gi", "Phú Quý", "Tánh Linh", "Tuy Phong"],
                    ca_mau: ["Cà Mau", "Cái Nước", "Đầm Dơi", "Năm Căn", "Ngọc Hiển", "Phú Tân", "Thới Bình", "Trần Văn Thời", "U Minh"],
                    cao_bang: ["Cao Bằng", "Bảo Lạc", "Bảo Lâm", "Hạ Lang", "Hà Quảng", "Hòa An", "Nguyên Bình", "Quảng Hòa", "Thạch An", "Trùng Khánh"],
                    dak_lak: ["Buôn Ma Thuột", "Buôn Hồ", "Buôn Đôn", "Cư Kuin", "Cư M'gar", "Ea H'leo", "Ea Kar", "Ea Súp", "Krông Ana", "Krông Bông", "Krông Búk", "Krông Năng", "Krông Pắc", "Lắk", "M'Đrắk"],
                    dak_nong: ["Gia Nghĩa", "Cư Jút", "Đắk Glong", "Đắk Mil", "Đắk R'lấp", "Đắk Song", "Krông Nô", "Tuy Đức"],
                    dien_bien: ["Điện Biên Phủ", "Điện Biên", "Điện Biên Đông", "Mường Ảng", "Mường Chà", "Mường Lay", "Mường Nhé", "Nậm Pồ", "Tủa Chùa", "Tuần Giáo"],
                    dong_nai: ["Biên Hòa", "Cẩm Mỹ", "Định Quán", "Long Khánh", "Long Thành", "Nhơn Trạch", "Tân Phú", "Thống Nhất", "Trảng Bom", "Vĩnh Cửu", "Xuân Lộc"],
                    dong_thap: ["Cao Lãnh", "Hồng Ngự", "Châu Thành", "Hồng Ngự", "Lai Vung", "Lấp Vò", "Sa Đéc", "Tam Nông", "Tân Hồng", "Thanh Bình", "Tháp Mười"],
                    gia_lai: ["Pleiku", "An Khê", "Ayun Pa", "Chư Păh", "Chư Prông", "Chư Pưh", "Chư Sê", "Đak Đoa", "Đak Pơ", "Đức Cơ", "Ia Grai", "Ia Pa", "K'Bang", "Kông Chro", "Krông Pa", "Mang Yang", "Phú Thiện", "Đăk Đoa"],
                    ha_giang: ["Hà Giang", "Bắc Mê", "Bắc Quang", "Đồng Văn", "Hoàng Su Phì", "Mèo Vạc", "Quản Bạ", "Quang Bình", "Vị Xuyên", "Xín Mần", "Yên Minh"],
                    ha_nam: ["Phủ Lý", "Bình Lục", "Duy Tiên", "Kim Bảng", "Lý Nhân", "Thanh Liêm"],
                    ha_tinh: ["Hà Tĩnh", "Cẩm Xuyên", "Can Lộc", "Đức Thọ", "Hồng Lĩnh", "Hương Khê", "Hương Sơn", "Kỳ Anh", "Lộc Hà", "Nghi Xuân", "Thạch Hà", "Vũ Quang"],
                    hai_duong: ["Hải Dương", "Bình Giang", "Cẩm Giàng", "Chí Linh", "Gia Lộc", "Kim Thành", "Kinh Môn", "Nam Sách", "Ninh Giang", "Thanh Hà", "Thanh Miện", "Tứ Kỳ"],
                    hau_giang: ["Vị Thanh", "Châu Thành", "Châu Thành A", "Long Mỹ", "Ngã Bảy", "Phụng Hiệp", "Vị Thủy"],
                    hoa_binh: ["Hòa Bình", "Cao Phong", "Đà Bắc", "Kim Bôi", "Lạc Sơn", "Lạc Thủy", "Lương Sơn", "Mai Châu", "Tân Lạc", "Yên Thủy"],
                    hung_yen: ["Hưng Yên", "Ân Thi", "Khoái Châu", "Kim Động", "Mỹ Hào", "Phù Cừ", "Tiên Lữ", "Văn Giang", "Văn Lâm", "Yên Mỹ"],
                    khanh_hoa: ["Nha Trang", "Cam Lâm", "Cam Ranh", "Diên Khánh", "Khánh Sơn", "Khánh Vĩnh", "Ninh Hòa", "Trường Sa", "Vạn Ninh"],
                    kien_giang: ["Rạch Giá", "An Biên", "An Minh", "Châu Thành", "Giang Thành", "Giồng Riềng", "Gò Quao", "Hà Tiên", "Hòn Đất", "Kiên Hải", "Kiên Lương", "Phú Quốc", "Tân Hiệp", "U Minh Thượng", "Vĩnh Thuận"],
                    kon_tum: ["Kon Tum", "Đắk Glei", "Đắk Hà", "Đắk Tô", "Ia H'Drai", "Kon Plông", "Kon Rẫy", "Ngọc Hồi", "Sa Thầy", "Tu Mơ Rông"],
                    lai_chau: ["Lai Châu", "Mường Tè", "Nậm Nhùn", "Phong Thổ", "Sìn Hồ", "Tam Đường", "Tân Uyên", "Than Uyên"],
                    lam_dong: ["Đà Lạt", "Bảo Lâm", "Bảo Lộc", "Cát Tiên", "Đạ Huoai", "Đạ Tẻh", "Đam Rông", "Di Linh", "Đơn Dương", "Đức Trọng", "Lạc Dương", "Lâm Hà"],
                    lang_son: ["Lạng Sơn", "Bắc Sơn", "Bình Gia", "Cao Lộc", "Chi Lăng", "Đình Lập", "Hữu Lũng", "Lộc Bình", "Tràng Định", "Văn Lãng", "Văn Quan"],
                    lao_cai: ["Lào Cai", "Bắc Hà", "Bảo Thắng", "Bảo Yên", "Bát Xát", "Mường Khương", "Sa Pa", "Si Ma Cai", "Văn Bàn"],
                    long_an: ["Tân An", "Bến Lức", "Cần Đước", "Cần Giuộc", "Châu Thành", "Đức Hòa", "Đức Huệ", "Kiến Tường", "Mộc Hóa", "Tân Hưng", "Tân Thạnh", "Tân Trụ", "Thạnh Hóa", "Thủ Thừa", "Vĩnh Hưng"],
                    nam_dinh: ["Nam Định", "Giao Thủy", "Hải Hậu", "Mỹ Lộc", "Nam Trực", "Nghĩa Hưng", "Trực Ninh", "Vụ Bản", "Xuân Trường", "Ý Yên"],
                    nghe_an: ["Vinh", "Anh Sơn", "Con Cuông", "Diễn Châu", "Đô Lương", "Hoàng Mai", "Hưng Nguyên", "Kỳ Sơn", "Nam Đàn", "Nghi Lộc", "Nghĩa Đàn", "Quế Phong", "Quỳ Châu", "Quỳ Hợp", "Quỳnh Lưu", "Tân Kỳ", "Thái Hòa", "Thanh Chương", "Tương Dương", "Yên Thành"],
                    ninh_binh: ["Ninh Bình", "Gia Viễn", "Hoa Lư", "Kim Sơn", "Nho Quan", "Tam Điệp", "Yên Khánh", "Yên Mô"],
                    ninh_thuan: ["Phan Rang-Tháp Chàm", "Bác Ái", "Ninh Hải", "Ninh Phước", "Ninh Sơn", "Thuận Bắc", "Thuận Nam"],
                    phu_tho: ["Việt Trì", "Cẩm Khê", "Đoan Hùng", "Hạ Hòa", "Lâm Thao", "Phù Ninh", "Phú Thọ", "Tam Nông", "Tân Sơn", "Thanh Ba", "Thanh Sơn", "Thanh Thủy", "Yên Lập"],
                    phu_yen: ["Tuy Hòa", "Đông Hòa", "Đồng Xuân", "Phú Hòa", "Sơn Hòa", "Sông Cầu", "Sông Hinh", "Tây Hòa", "Tuy An"],
                    quang_binh: ["Đồng Hới", "Ba Đồn", "Bố Trạch", "Lệ Thủy", "Minh Hóa", "Quảng Ninh", "Quảng Trạch", "Tuyên Hóa"],
                    quang_nam: ["Tam Kỳ", "Bắc Trà My", "Duy Xuyên", "Đại Lộc", "Điện Bàn", "Đông Giang", "Hiệp Đức", "Hội An", "Nam Giang", "Nam Trà My", "Nông Sơn", "Núi Thành", "Phú Ninh", "Phước Sơn", "Quế Sơn", "Tây Giang", "Thăng Bình", "Tiên Phước"],
                    quang_ngai: ["Quảng Ngãi", "Ba Tơ", "Bình Sơn", "Đức Phổ", "Lý Sơn", "Minh Long", "Mộ Đức", "Nghĩa Hành", "Sơn Hà", "Sơn Tây", "Sơn Tịnh", "Tây Trà", "Trà Bồng", "Tư Nghĩa"],
                    quang_ninh: ["Hạ Long", "Ba Chẽ", "Bình Liêu", "Cẩm Phả", "Cô Tô", "Đầm Hà", "Đông Triều", "Hải Hà", "Móng Cái", "Quảng Yên", "Tiên Yên", "Uông Bí", "Vân Đồn"],
                    quang_tri: ["Đông Hà", "Cam Lộ", "Cồn Cỏ", "Đa Krông", "Gio Linh", "Hải Lăng", "Hướng Hóa", "Quảng Trị", "Triệu Phong", "Vĩnh Linh"],
                    soc_trang: ["Sóc Trăng", "Châu Thành", "Cù Lao Dung", "Kế Sách", "Long Phú", "Mỹ Tú", "Mỹ Xuyên", "Ngã Năm", "Thạnh Trị", "Trần Đề", "Vĩnh Châu"],
                    son_la: ["Sơn La", "Bắc Yên", "Mai Sơn", "Mộc Châu", "Mường La", "Phù Yên", "Quỳnh Nhai", "Sông Mã", "Sốp Cộp", "Thuận Châu", "Vân Hồ", "Yên Châu"],
                    tay_ninh: ["Tây Ninh", "Bến Cầu", "Châu Thành", "Dương Minh Châu", "Gò Dầu", "Hòa Thành", "Tân Biên", "Tân Châu", "Trảng Bàng"],
                    thai_binh: ["Thái Bình", "Đông Hưng", "Hưng Hà", "Kiến Xương", "Quỳnh Phụ", "Thái Thụy", "Tiền Hải", "Vũ Thư"],
                    thai_nguyen: ["Thái Nguyên", "Đại Từ", "Định Hóa", "Đồng Hỷ", "Phổ Yên", "Phú Bình", "Phú Lương", "Sông Công", "Võ Nhai"],
                    thanh_hoa: ["Thanh Hóa", "Bá Thước", "Cẩm Thủy", "Đông Sơn", "Hà Trung", "Hậu Lộc", "Hoằng Hóa", "Lang Chánh", "Mường Lát", "Nga Sơn", "Ngọc Lặc", "Như Thanh", "Như Xuân", "Nông Cống", "Quan Hóa", "Quan Sơn", "Quảng Xương", "Sầm Sơn", "Thạch Thành", "Thiệu Hóa", "Thọ Xuân", "Thường Xuân", "Tĩnh Gia", "Triệu Sơn", "Vĩnh Lộc", "Yên Định"],
                    thua_thien_hue: ["Huế", "A Lưới", "Nam Đông", "Phong Điền", "Phú Lộc", "Phú Vang", "Quảng Điền"],
                    tien_giang: ["Mỹ Tho", "Cái Bè", "Cai Lậy", "Châu Thành", "Chợ Gạo", "Gò Công", "Gò Công Đông", "Gò Công Tây", "Tân Phú Đông", "Tân Phước"],
                    tra_vinh: ["Trà Vinh", "Cái Nước", "Chợ Lách", "Cửa Lưới", "Kinh Ngư", "Mỏ Cày Nam", "Mỏ Cày Bắc", "Ngọc Hiển", "Phú Tân", "Thới Bình", "Trà Cú", "Vĩnh Thạnh"],
                    tuyen_quang: ["Tuyên Quang", "Bảo Lạc", "Bảo Lâm", "Hạ Lang", "Hà Quảng", "Hòa An", "Nguyên Bình", "Quảng Hòa", "Thạch An", "Trùng Khánh"],
                    vinh_long: ["Vĩnh Long", "Bình Tân", "Bình Cụt", "Mỏ Cày Nam", "Mỏ Cày Bắc", "Ngọc Hiển", "Phú Tân", "Thới Bình", "Trà Cú", "Vĩnh Thạnh"],
                    vinh_phuc: ["Vĩnh Phúc", "Bắc Sơn", "Bình Lưu", "Cẩm Xuyên", "Đoan Hùng", "Hàm Yên", "Lập Thạch", "Mê Linh", "Mỹ Lộc", "Ninh Sơn", "Phù Cát", "Phù Ninh", "Quan Sơn", "Sơn Lộc", "Sơn Phong", "Tân Sơn", "Thái Bình", "Thái Thụy", "Tiền Phong", "Trà Cú", "Vĩnh Lộc", "Yên Lập"],
                    yen_bai: ["Yên Bái", "Bắc Sơn", "Bình Lưu", "Cẩm Xuyên", "Đoan Hùng", "Hàm Yên", "Lập Thạch", "Mê Linh", "Mỹ Lộc", "Ninh Sơn", "Phù Cát", "Phù Ninh", "Quan Sơn", "Sơn Lộc", "Sơn Phong", "Tân Sơn", "Thái Bình", "Thái Thụy", "Tiền Phong", "Trà Cú", "Vĩnh Lộc", "Yên Lập"]
                };

                const citySelect = document.getElementById("customer_city");
                const districtSelect = document.getElementById("customer_district");

                citySelect.addEventListener("change", function () {
                    const selectedCity = this.value;
                    const districts = districtsByCity[selectedCity] || [];

                    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';                    districts.forEach(function (district) {
                        const option = document.createElement("option");
                        // Xử lý đặc biệt cho chữ "Đ" và "đ"
                        let convertedValue = district.toLowerCase()
                            .replace(/đ/g, "d")  // Thay đổ chữ "đ" thành "d"
                            .replace(/\s+/g, "_")
                            .normalize("NFD")
                            .replace(/[\u0300-\u036f]/g, "");
                        option.value = convertedValue;
                        option.textContent = district;
                        districtSelect.appendChild(option);
                    });
                });
            </script>

        </div>
    </div>
<?php endif; ?>
<script>
    jQuery(document).ready(function ($) {
        $('.remove-item').on('click', function () {
            const productId = $(this).data('product-id');
            removeItem(productId);
        });

        function removeItem(productId) {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'remove_from_cart',
                    product_id: productId,
                    security: '<?php echo wp_create_nonce('remove-from-cart'); ?>'
                },
                success: function (response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        }
    });
</script>