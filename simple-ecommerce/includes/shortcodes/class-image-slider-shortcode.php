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