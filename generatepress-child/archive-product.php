<?php
get_header();
?>

<div class="container">
    <div class="search-results">
        <?php
        if (have_posts()) :
            echo '<div class="product-grid">';
            while (have_posts()) : the_post();
                echo '<div class="product-item">';
                if (has_post_thumbnail()) {
                    echo '<a href="' . get_permalink() . '">';
                    the_post_thumbnail('medium');
                    echo '</a>';
                }
                echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                $regular_price = get_post_meta(get_the_ID(), '_regular_price', true);
                $sale_price = get_post_meta(get_the_ID(), '_sale_price', true);

                echo '<div class="prd-price">';
                if ($sale_price && $sale_price < $regular_price) {
                    echo '<span class="current-price">' . number_format($sale_price, 0, ',', '.') . '₫</span>';
                    echo '<span class="original-price">' . number_format($regular_price, 0, ',', '.') . '₫</span>';
                } else {
                    echo '<span class="current-price">' . number_format($regular_price, 0, ',', '.') . '₫</span>';
                }
                echo '</div>';
                echo '<a class="product-detail-link" href="' . get_permalink() . '">Xem chi tiết</a>';
                echo '</div>';
            endwhile;
            echo '</div>';

            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('Previous', 'generatepress-child'),
                'next_text' => __('Next', 'generatepress-child'),
            ));
        else :
            echo '<p>Không tìm thấy sản phẩm phù hợp.</p>';
        endif;
        ?>
    </div>
</div>

<?php
get_footer();
