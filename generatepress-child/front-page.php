<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header(); ?>

<div class="container">
    <div class="slider-wrapper">
        <div class="slider" id="slider">
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
        </div>


        <button class="slider-arrows prev" onclick="changeSlide(-1)">‹</button>
        <button class="slider-arrows next" onclick="changeSlide(1)">›</button>


        <div class="slider-nav">
            <span class="nav-dot active" onclick="currentSlide(1)"></span>
            <span class="nav-dot" onclick="currentSlide(2)"></span>
            <span class="nav-dot" onclick="currentSlide(3)"></span>
            <span class="nav-dot" onclick="currentSlide(4)"></span>
            <span class="nav-dot" onclick="currentSlide(5)"></span>
        </div>
    </div>
    <div class="category-list">
        <div class="category-item">
            <a href="<?php echo site_url('/iPhone/'); ?>"><img
                    src="https://cdnv2.tgdd.vn/webmwg/2024/tz/images/desktop/IP_Desk.png" alt=""></a>
            <a href="<?php echo site_url('/iPhone/'); ?>">
                <p>iPhone</p>
            </a>
        </div>
        <div class="category-item">
            <a href="<?php echo site_url('/iPad/'); ?>"><img
                    src="https://cdnv2.tgdd.vn/webmwg/2024/tz/images/desktop/Ipad_Desk.png" alt=""></a>
            <a href="<?php echo site_url('/iPad/'); ?>">
                <p>iPad</p>
            </a>
        </div>
        <div class="category-item">
            <a href="<?php echo site_url('/mac/'); ?>"><img
                    src="https://cdnv2.tgdd.vn/webmwg/2024/tz/images/desktop/Mac_Desk.png" alt=""></a>
            <a href="<?php echo site_url('/mac/'); ?>">
                <p>Mac</p>
            </a>
        </div>
        <div class="category-item">
            <a href="<?php echo site_url('/watch/'); ?>"><img
                    src="https://cdnv2.tgdd.vn/webmwg/2024/tz/images/desktop/Watch_Desk.png" alt=""></a>
            <a href="<?php echo site_url('/watch/'); ?>">
                <p>Watch</p>
            </a>
        </div>
        <div class="category-item">
            <a href="<?php echo site_url('/phu-kien/'); ?>"><img
                    src="https://cdnv2.tgdd.vn/webmwg/2024/tz/images/desktop/Speaker_Desk.png" alt=""></a>
            <a href="<?php echo site_url('/phu-kien/'); ?>">
                <p>Tai nghe,Loa</p>
            </a>
        </div>
    </div>
    <div class="iphone-class">
        <h2>iPhone</h2>
        <div class="box-iphone">
            <div class="iphone-list">
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 4%</span>
                        <span class="badge new">Mới</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0034910_iphone-16e-128gb_240.png"
                            alt="iPhone 16 128GB">
                    </div>
                    <div class="iphone-title">iPhone 16 128GB</div>
                    <div class="iphone-price">
                        <span class="price-new">16.190.000₫</span>
                        <span class="price-old">16.999.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 13%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0030444_iphone-16-pro-128gb_240.png"
                            alt="iPhone 16 Pro 128GB">
                    </div>
                    <div class="iphone-title">iPhone 16 Pro 128GB</div>
                    <div class="iphone-price">
                        <span class="price-new">24.990.000₫</span>
                        <span class="price-old">28.990.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 13%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0029155_iphone-16-pro-max-256gb_240.png"
                            alt="iPhone 16 Pro Max 256GB">
                    </div>
                    <div class="iphone-title">iPhone 16 Pro Max 256GB</div>
                    <div class="iphone-price">
                        <span class="price-new">30.290.000₫</span>
                        <span class="price-old">34.990.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 5%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0030771_iphone-16-128gb_240.png"
                            alt="iPhone 16 Plus 128GB">
                    </div>
                    <div class="iphone-title">iPhone 16 Plus 128GB</div>
                    <div class="iphone-price">
                        <span class="price-new">26.390.000₫</span>
                        <span class="price-old">27.990.000₫</span>
                    </div>
                </div>
            </div>
            <div class="iphone-viewall">
                <a href="<?php echo site_url('/iPhone/'); ?>">Xem tất cả iPhone &rarr;</a>
            </div>

        </div>
    </div>
    <div class="iphone-class">
        <h2>iPad</h2>
        <div class="box-iphone">
            <div class="iphone-list">
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 3%</span>
                        <span class="badge new">Mới</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0035054_ipad-air-m3-11-inch-wi-fi_240.png"
                            alt="iPad Air M3 11 inch Wi-Fi">
                    </div>
                    <div class="iphone-title">iPad Air M3 11 inch Wi-Fi</div>
                    <div class="iphone-price">
                        <span class="price-new">15.890.000₫</span>
                        <span class="price-old">16.490.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 8%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0035754_ipad-a16-11-inch-wi-fi_240.png"
                            alt="iPad Pro M4 11 inch Wi-Fi">
                    </div>
                    <div class="iphone-title">iPad Pro M4 11 inch Wi-Fi</div>
                    <div class="iphone-price">
                        <span class="price-new">9.190.000₫</span>
                        <span class="price-old">9.990.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 22%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0025630_ipad-air-6-m2-13-inch-wi-fi_240.jpeg"
                            alt="iPad Air M2 13 inch Wi-Fi">
                    </div>
                    <div class="iphone-title">iPad Air M2 13 inch Wi-Fi</div>
                    <div class="iphone-price">
                        <span class="price-new">8.490.000₫</span>
                        <span class="price-old">10.990.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 9%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0009725_ipad-gen-10-th-109-inch-wifi-64gb_240.png"
                            alt="iPad Gen 10 10.9 inch Wi-Fi 64GB">
                    </div>
                    <div class="iphone-title">iPad Gen 10 10.9 inch Wi-Fi 64GB</div>
                    <div class="iphone-price">
                        <span class="price-new">6.590.000₫</span>
                        <span class="price-old">7.299.000₫</span>
                    </div>
                </div>
            </div>
            <div class="iphone-viewall">
                <a href="<?php echo site_url('/iPad/'); ?>">Xem tất cả iPad &rarr;</a>
            </div>
        </div>
    </div>
    <div class="iphone-class">
        <h2>Mac</h2>
        <div class="box-iphone">
            <div class="iphone-list">
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 12%</span>
                        <span class="badge new">Mới</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0036014_macbook-air-m4-13-inch-8-core-gpu-16gb-ram-256gb-ssd_240.jpeg"
                            alt="MacBook Air M4 13 inch 256GB">
                    </div>
                    <div class="iphone-title">MacBook Air M4 13 inch 256GB</div>
                    <div class="iphone-price">
                        <span class="price-new">12.190.000₫</span>
                        <span class="price-old">13.990.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 20%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0008502_macbook-air-m2-13-inch-8gb-ram-256gb-ssd_240.png"
                            alt="MacBook Air M2 13 inch 256GB">
                    </div>
                    <div class="iphone-title">MacBook Air M2 13 inch 256GB</div>
                    <div class="iphone-price">
                        <span class="price-new">5.390.000₫</span>
                        <span class="price-old">6.790.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 28%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0022586_macbook-pro-16-inch-m3-pro-2023-18gb-ram-18-core-gpu-512gb-ssd_240.jpeg"
                            alt="MacBook Pro M3 16 inch 512GB">
                    </div>
                    <div class="iphone-title">MacBook Pro M3 16 inch 512GB</div>
                    <div class="iphone-price">
                        <span class="price-new">4.850.000₫</span>
                        <span class="price-old">6.790.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 27%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0036240_macbook-air-m4-15-inch-10-core-gpu-24gb-ram-512gb-ssd_240.jpeg"
                            alt="MacBook Air M4 15 inch 512GB">
                    </div>
                    <div class="iphone-title">MacBook Air M4 15 inch 512GB</div>
                    <div class="iphone-price">
                        <span class="price-new">499.000₫</span>
                        <span class="price-old">690.000₫</span>
                    </div>
                </div>
            </div>
            <div class="iphone-viewall">
                <a href="<?php echo site_url('/mac/'); ?>">Xem tất cả Mac &rarr;</a>
            </div>
        </div>
    </div>
    <div class="iphone-class">
        <h2>Watch</h2>
        <div class="box-iphone">
            <div class="iphone-list">
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 40%</span>
                        <span class="badge new">Mới</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0029160_apple-watch-series-10-nhom-gps-42mm-sport-band_240.jpeg"
                            alt="Apple Watch Series 10">
                    </div>
                    <div class="iphone-title">Apple Watch Series 10</div>
                    <div class="iphone-price">
                        <span class="price-new">1.790.000₫</span>
                        <span class="price-old">2.990.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 42%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0030238_apple-watch-ultra-2-gps-cellular-49mm-ocean-2024_240.png"
                            alt="Apple Watch Series 9">
                    </div>
                    <div class="iphone-title">Apple Watch Series 9</div>
                    <div class="iphone-price">
                        <span class="price-new">3.990.000₫</span>
                        <span class="price-old">6.990.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 15%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0032869_apple-watch-se-gps-cellular-2024-sport-band-size-sm_240.jpeg"
                            alt="Apple Watch SE">
                    </div>
                    <div class="iphone-title">Apple Watch SE</div>
                    <div class="iphone-price">
                        <span class="price-new">2.950.000₫</span>
                        <span class="price-old">3.499.000₫</span>
                    </div>
                </div>
                <div class="iphone-item">
                    <div class="iphone-badges">
                        <span class="badge sale">Giảm 3%</span>
                        <span class="badge installment">Trả góp 0%</span>
                    </div>
                    <div class="iphone-img">
                        <img src="https://shopdunk.com/images/thumbs/0030450_apple-watch-se-gps-2024-sport-band-size-sm_240.jpeg"
                            alt="Apple Watch Ultra 2">
                    </div>
                    <div class="iphone-title">Apple Watch Ultra 2</div>
                    <div class="iphone-price">
                        <span class="price-new">15.890.000₫</span>
                        <span class="price-old">16.490.000₫</span>
                    </div>
                </div>
            </div>
            <div class="iphone-viewall">
                <a href="<?php echo site_url('/watch/'); ?>">Xem tất cả Watch &rarr;</a>
            </div>
        </div>
    </div>
    <div class="topic-block-body">
        <a href="<?php echo site_url('/shop/'); ?>"><img src="https://shopdunk.com/images/uploaded/Trang%20ch%E1%BB%A7/2.jpeg" alt=""></a>
    </div>
</div>

<?php
get_footer();
