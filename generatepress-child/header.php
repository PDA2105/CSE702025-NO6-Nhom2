<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div class="custom-header">
        <div class="header-left">
            <a href="/thanhtung/">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/logoIstoreNew.svg" alt="Logo" class="logo" />
            </a>
        </div>

        <nav class="header-menu">
            <ul>
                <li><a href="<?php echo site_url('/shop/'); ?>">Store</a></li>
                <li><a href="<?php echo site_url('/iPhone/'); ?>">iPhone</a></li>
                <li><a href="<?php echo site_url('/iPad/'); ?>">iPad</a></li>
                <li><a href="<?php echo site_url('/mac/'); ?>">Mac</a></li>
                <li><a href="<?php echo site_url('/watch/'); ?>">Watch</a></li>
                <li><a href="<?php echo site_url('/phu-kien/'); ?>">Phụ Kiện</a></li>
                <li><a href="<?php echo site_url('/am-thanh/'); ?>">Âm Thanh</a></li>
                <li><a href="<?php echo site_url('/camera/'); ?>">Camera</a></li>
                <li><a href="<?php echo site_url('/gia-dung/'); ?>">Gia Dụng</a></li>
                <li><a href="<?php echo site_url('/may-luot/'); ?>">Máy Lướt</a></li>
                <li><a href="<?php echo site_url('/dich-vu/'); ?>">Dịch Vụ</a></li>
            </ul>
        </nav>

        <div class="header-right">
            <button class="search-button" aria-label="Tìm kiếm">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
            </button>
            <div class="header-search-dropdown">
                <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                    <input type="search" name="s" placeholder="Tìm kiếm sản phẩm..." />
                    <input type="hidden" name="post_type" value="product">
                    <button type="submit" class="search-button">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                            </path>
                        </svg>
                    </button>
                </form>
            </div>

            <a href="<?php echo site_url('/cart/'); ?>" class="cart-icon" aria-label="Giỏ hàng">
                <svg xmlns="http://www.w3.org/2000/svg" height="28" viewBox="0 0 24 24" width="28" fill="white">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 
            2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 
            2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zM7.16 
            14l.84-2h7.45c.75 0 1.41-.41 
            1.75-1.03l3.58-6.49a1.003 1.003 0 00-.88-1.48H5.21l-.94-2H1v2h2l3.6 
            7.59-1.35 2.44C4.52 15.37 5.48 17 7 
            17h12v-2H7.16z" />
                </svg>
            </a>

            <a href="<?php echo site_url('/my-account/'); ?>" class="user-icon" aria-label="Tài khoản">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="white">
                    <path d="M12 12c2.67 0 8 1.34 8 
            4v2H4v-2c0-2.66 5.33-4 8-4zm0-2c-1.66 
            0-3-1.34-3-3s1.34-3 3-3 3 1.34 
            3 3-1.34 3-3 3z" />
                </svg>
            </a>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var searchBtn = document.querySelector('.search-button');
            var searchDropdown = document.querySelector('.header-search-dropdown');
            var searchInput = searchDropdown ? searchDropdown.querySelector('input[type="search"]') : null;

            if (searchBtn && searchDropdown) {
                searchBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    searchDropdown.classList.toggle('show');
                    if (searchDropdown.classList.contains('show') && searchInput) {
                        setTimeout(function () {
                            searchInput.focus();
                        }, 100);
                    }
                });
                document.addEventListener('click', function (e) {
                    if (!searchDropdown.contains(e.target) && e.target !== searchBtn) {
                        searchDropdown.classList.remove('show');
                    }
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        searchDropdown.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>

</html>