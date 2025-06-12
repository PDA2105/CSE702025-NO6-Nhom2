<?php
class Simple_Image_Slider_Shortcode
{    public function __construct()
    {
        add_shortcode('simple_image_slider', array($this, 'render'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
    }

    public function render($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts(array(
            'images'   => '',
            'style'    => 'default',
            'height'   => '300',
            'autoplay' => 'false',
            'speed'    => '3000',
            'dots'     => 'true',
            'arrows'   => 'true',
            'class'    => ''
        ), $atts);

        // Validate dữ liệu
        $image_ids = array_filter(explode(',', $atts['images']));
        $height    = intval($atts['height']);
        $speed     = intval($atts['speed']);

        $validated = $this->validate_images($image_ids);
        if (is_wp_error($validated)) {
            return $this->handle_error($validated);
        }

        return $this->generate_slider_html($validated, $atts);
    }
    private function process_images($image_ids)
    {
        $images = array();

        foreach ($image_ids as $id) {
            $id = intval($id); // Đảm bảo ID là số nguyên

            if ($id > 0) {
                $image_url = wp_get_attachment_image_url($id, 'large'); // Lấy URL ảnh kích thước 'large'
                $image_alt = get_post_meta($id, '_wp_attachment_image_alt', true); // Lấy alt text

                if ($image_url) {
                    $images[] = array(
                        'id'  => $id,
                        'url' => $image_url,
                        'alt' => $image_alt ?: 'Slider Image' // Nếu không có alt, dùng mặc định
                    );
                }
            }
        }

        return $images;
    }

    private function generate_slider_html($image_ids, $atts)
    {
        $images = $this->process_images($image_ids);

        if (empty($images)) {
            return '<p>No images found for slider.</p>';
        }

        $slider_id    = 'slider-' . uniqid();
        $style_class  = 'slider-style-' . sanitize_html_class($atts['style']);
        $custom_class = !empty($atts['class']) ? ' ' . sanitize_html_class($atts['class']) : '';

        $html  = '<div class="simple-image-slider-container ' . $style_class . $custom_class . '">';
        $html .= '<div class="simple-image-slider" id="' . $slider_id . '" data-autoplay="' . $atts['autoplay'] . '" data-speed="' . $atts['speed'] . '">';        // Slider container
        $html .= '<div class="slider-container">';
        $html .= '<div class="slides-wrapper" id="slides-' . $slider_id . '">';
        foreach ($images as $index => $image) {
            $html .= '<div class="slide">';
            $html .= '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '">';
            $html .= '</div>';
        }
        $html .= '</div>'; // .slides-wrapper
        $html .= '</div>'; // .slider-container

        // Navigation arrows
        if ($atts['arrows'] === 'true') {
            $html .= $this->generate_arrows();
        }

        // Navigation dots
        if ($atts['dots'] === 'true') {
            $html .= $this->generate_dots($images);
        }

        $html .= '</div>'; // .simple-image-slider
        $html .= '</div>'; // .simple-image-slider-container

        return $html;
    }    private function generate_arrows()
    {
        return '
        <button class="slider-arrow prev" onclick="moveSlide(-1)">
            <span>&#8249;</span>
        </button>
        <button class="slider-arrow next" onclick="moveSlide(1)">
            <span>&#8250;</span>
        </button>';
    }    private function generate_dots($images)
    {
        $html = '<div class="slider-dots">';
        foreach ($images as $index => $image) {
            $active_class = $index === 0 ? ' active' : '';
            $html .= '<span class="dot' . $active_class . '" onclick="goToSlide(' . $index . ')"></span>';
        }
        $html .= '</div>';
        return $html;
    }
    private function validate_images($image_ids)
    {
        if (empty($image_ids)) {
            return new WP_Error('no_images', 'No images provided for slider.');
        }

        $valid_images = array();
        foreach ($image_ids as $id) {
            $id = intval($id);
            if ($id > 0 && wp_attachment_is_image($id)) {
                $valid_images[] = $id;
            }
        }

        if (empty($valid_images)) {
            return new WP_Error('invalid_images', 'No valid images found for slider.');
        }

        return $valid_images;
    }

    private function handle_error($error)
    {
        if (current_user_can('edit_posts')) {
            return '<div class="slider-error">Slider Error: ' . esc_html($error->get_error_message()) . '</div>';
        }
        return '';
    }

    public function enqueue_frontend_scripts()
    {
        wp_enqueue_style(
            'simple-image-slider',
            plugin_dir_url(__FILE__) . '../../public/css/simple-image-slider.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'simple-image-slider',
            plugin_dir_url(__FILE__) . '../../public/js/simple-image-slider.js',
            array(),
            '1.0.0',
            true
        );
    }
}

// Khởi tạo class
new Simple_Image_Slider_Shortcode();
