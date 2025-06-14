<?php
/**
 * Simple Ecommerce Image Slider Shortcode
 * 
 * USAGE: [se_banner_slider image_ids="123,456,789"]
 * 
 * @package Simple_Ecommerce
 * @subpackage Shortcodes
 * @version 1.0.1
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SE Banner Slider Shortcode Class
 * Uses unique prefix 'se-banner-' to avoid conflicts with theme sliders
 */
class SE_Banner_Slider_Shortcode {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_shortcode('se_banner_slider', array($this, 'render_slider'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Enqueue CSS and JS assets
     */
    public function enqueue_scripts()
    {
        // Check if constants are defined
        if (!defined('SIMPLE_ECOMMERCE_URL') || !defined('SIMPLE_ECOMMERCE_VERSION')) {
            return;
        }

        // Enqueue CSS and JS with proper versioning
        wp_enqueue_style(
            'se-banner-slider',
            SIMPLE_ECOMMERCE_URL . 'public/css/simple-image-slider.css',
            array(),
            SIMPLE_ECOMMERCE_VERSION
        );

        wp_enqueue_script(
            'se-banner-slider',
            SIMPLE_ECOMMERCE_URL . 'public/js/simple-image-slider.js',
            array('jquery'),
            SIMPLE_ECOMMERCE_VERSION,
            true
        );
    }

    /**
     * Render the slider shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function render_slider($atts) {
        // Parse shortcode attributes with sanitization
        $atts = shortcode_atts(array(
            'image_ids' => '',
            'width' => '1200',
            'height' => '400',
            'autoplay' => 'true',
            'autoplay_speed' => '5000',
            'show_dots' => 'true',
            'show_arrows' => 'true',
            'transition_speed' => '500',
        ), $atts, 'se_banner_slider');
        
        // Sanitize numeric values
        $atts['width'] = absint($atts['width']);
        $atts['height'] = absint($atts['height']);
        $atts['autoplay_speed'] = absint($atts['autoplay_speed']);
        $atts['transition_speed'] = absint($atts['transition_speed']);
        
        // Set reasonable limits
        $atts['width'] = min(max($atts['width'], 200), 2000);
        $atts['height'] = min(max($atts['height'], 100), 1000);
        
        // Validate image_ids
        if (empty($atts['image_ids'])) {
            return '<div class="se-banner-error">Error: No image IDs provided for SE Banner Slider</div>';
        }
        
        // Parse image IDs with better validation
        $image_ids = array_map('trim', explode(',', $atts['image_ids']));
        $image_ids = array_map('absint', $image_ids);
        $image_ids = array_filter($image_ids, function($id) {
            return $id > 0;
        });
        
        if (empty($image_ids)) {
            return '<div class="se-banner-error">Error: Invalid image IDs for SE Banner Slider</div>';
        }
        
        // Validate images exist
        $valid_images = array();
        foreach ($image_ids as $image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'full');
            if ($image_url) {
                $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                $title_text = get_the_title($image_id);
                
                $valid_images[] = array(
                    'id' => $image_id,
                    'url' => $image_url,
                    'alt' => !empty($alt_text) ? $alt_text : $title_text,
                    'title' => $title_text
                );
            }
        }
        
        if (empty($valid_images)) {
            return '<div class="se-banner-error">Error: No valid images found for SE Banner Slider</div>';
        }
        
        // Generate unique slider ID
        static $slider_count = 0;
        $slider_count++;
        $slider_id = 'se-banner-slider-' . $slider_count;
        
        // Sanitize boolean attributes
        $autoplay = ($atts['autoplay'] === 'true') ? 'true' : 'false';
        $show_dots = ($atts['show_dots'] === 'true') ? 'true' : 'false';
        $show_arrows = ($atts['show_arrows'] === 'true') ? 'true' : 'false';
        
        // Start output buffering
        ob_start();
        ?>
        
        <div class="se-banner-container" style="max-width: <?php echo esc_attr($atts['width']); ?>px;">
            <div id="<?php echo esc_attr($slider_id); ?>" 
                 class="se-banner-slider" 
                 data-autoplay="<?php echo esc_attr($autoplay); ?>"
                 data-autoplay-speed="<?php echo esc_attr($atts['autoplay_speed']); ?>"
                 data-transition-speed="<?php echo esc_attr($atts['transition_speed']); ?>"
                 style="height: <?php echo esc_attr($atts['height']); ?>px;">
                
                <!-- Slider Track -->
                <div class="se-banner-track">
                    <?php foreach ($valid_images as $index => $image): ?>
                        <div class="se-banner-slide <?php echo $index === 0 ? 'se-banner-active' : ''; ?>">
                            <img src="<?php echo esc_url($image['url']); ?>" 
                                 alt="<?php echo esc_attr($image['alt']); ?>" 
                                 title="<?php echo esc_attr($image['title']); ?>"
                                 loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>" />
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($show_arrows === 'true' && count($valid_images) > 1): ?>
                <!-- Navigation Arrows -->
                <button class="se-banner-arrow se-banner-prev" aria-label="Previous slide">
                    <span class="se-banner-arrow-icon">❮</span>
                </button>
                <button class="se-banner-arrow se-banner-next" aria-label="Next slide">
                    <span class="se-banner-arrow-icon">❯</span>
                </button>
                <?php endif; ?>
                  <?php if ($show_dots === 'true' && count($valid_images) > 1): ?>
                <!-- Dots Navigation -->
                <div class="se-banner-dots">
                    <?php foreach ($valid_images as $index => $image): ?>
                        <span class="se-banner-dot <?php echo $index === 0 ? 'se-banner-active' : ''; ?>" 
                              data-slide="<?php echo esc_attr($index); ?>"
                              role="button"
                              tabindex="0"
                              aria-label="Go to slide <?php echo esc_attr($index + 1); ?>"></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php
        return ob_get_clean();
    }
}

// Initialize the shortcode
new SE_Banner_Slider_Shortcode();

/**
 * RECOMMENDED IMAGE SIZES FOR OPTIMAL DISPLAY:
 * - Banner images: 1200x400px (3:1 ratio)
 * - For retina displays: 2400x800px
 * - Supported formats: JPG, PNG, WebP
 * - Max file size: 500KB for best performance
 * 
 * CUSTOMIZATION GUIDE:
 * - To change max-width: modify the 'width' attribute in shortcode
 * - To change height: modify the 'height' attribute in shortcode
 * - Example: [se_banner_slider image_ids="123,456" width="1000" height="300"]
 */

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

