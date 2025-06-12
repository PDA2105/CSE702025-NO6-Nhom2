<?php

class Simple_Ecommerce_Import_Controller
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_import_menu'));
        add_action('wp_ajax_simple_ecommerce_import_products', array($this, 'handle_import'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function add_import_menu()
    {
        add_submenu_page(
            'edit.php?post_type=product',
            'Import Products',
            'Import Products',
            'manage_options',
            'import-products',
            array($this, 'import_page')
        );
    }

    public function enqueue_scripts($hook)
    {
        if ($hook === 'product_page_import-products') {
            wp_enqueue_script('jquery');
        }
    }

    public function import_page()
    {
        ?>
        <div class="wrap">
            <h1>Import Products from CSV</h1>
            
            <div class="import-instructions">
                <h3>CSV Format Instructions</h3>
                <p>Your CSV file should contain the following columns (in this exact order):</p>
                <ol>
                    <li><strong>name</strong> - Product name (required)</li>
                    <li><strong>description</strong> - Product description</li>
                    <li><strong>regular_price</strong> - Regular price (required)</li>
                    <li><strong>sale_price</strong> - Sale price (optional)</li>
                    <li><strong>stock</strong> - Stock quantity</li>
                    <li><strong>sku</strong> - Product SKU</li>
                    <li><strong>category</strong> - Product category name</li>
                    <li><strong>image_url</strong> - Product image URL (optional)</li>
                </ol>
            </div>

            <form id="import-form" enctype="multipart/form-data">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="csv_file">Select CSV File</label>
                        </th>
                        <td>
                            <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
                            <p class="description">Select a CSV file to import products.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="update_existing">Update Existing Products</label>
                        </th>
                        <td>
                            <input type="checkbox" name="update_existing" id="update_existing" value="1">
                            <label for="update_existing">Update products if SKU already exists</label>
                        </td>
                    </tr>
                </table>
                
                <?php wp_nonce_field('simple_ecommerce_import_nonce', 'import_nonce'); ?>
                
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Import Products">
                </p>
            </form>
            
            <div id="import-progress" style="display: none;">
                <h3>Import Progress</h3>
                <div id="progress-bar" style="width: 100%; background: #f1f1f1; border-radius: 3px;">
                    <div id="progress-fill" style="width: 0%; height: 20px; background: #4CAF50; border-radius: 3px; transition: width 0.3s;"></div>
                </div>
                <p id="progress-text">Preparing import...</p>
            </div>
            
            <div id="import-results" style="display: none;">
                <h3>Import Results</h3>
                <div id="results-content"></div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('#import-form').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData();
                var fileInput = $('#csv_file')[0];
                
                if (!fileInput.files.length) {
                    alert('Please select a CSV file.');
                    return;
                }
                
                formData.append('action', 'simple_ecommerce_import_products');
                formData.append('csv_file', fileInput.files[0]);
                formData.append('update_existing', $('#update_existing').is(':checked') ? 1 : 0);
                formData.append('import_nonce', $('#import_nonce').val());
                
                $('#import-progress').show();
                $('#import-results').hide();
                $('#submit').prop('disabled', true);
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#submit').prop('disabled', false);
                        $('#import-progress').hide();
                        $('#import-results').show();
                        
                        if (response.success) {
                            $('#results-content').html(
                                '<div class="notice notice-success"><p><strong>Import completed successfully!</strong></p>' +
                                '<ul>' +
                                '<li>Products imported: ' + response.data.imported + '</li>' +
                                '<li>Products updated: ' + response.data.updated + '</li>' +
                                '<li>Products skipped: ' + response.data.skipped + '</li>' +
                                '<li>Errors: ' + response.data.errors + '</li>' +
                                '</ul></div>'
                            );
                            
                            if (response.data.error_messages.length > 0) {
                                $('#results-content').append(
                                    '<div class="notice notice-warning"><p><strong>Error Details:</strong></p>' +
                                    '<ul><li>' + response.data.error_messages.join('</li><li>') + '</li></ul></div>'
                                );
                            }
                        } else {
                            $('#results-content').html(
                                '<div class="notice notice-error"><p><strong>Import failed:</strong> ' + response.data + '</p></div>'
                            );
                        }
                    },
                    error: function() {
                        $('#submit').prop('disabled', false);
                        $('#import-progress').hide();
                        $('#import-results').show();
                        $('#results-content').html(
                            '<div class="notice notice-error"><p><strong>Import failed:</strong> Server error occurred.</p></div>'
                        );
                    }
                });
            });
        });
        </script>
        <?php
    }

    public function handle_import()
    {
        // Check nonce and permissions
        if (!wp_verify_nonce($_POST['import_nonce'], 'simple_ecommerce_import_nonce') || !current_user_can('manage_options')) {
            wp_send_json_error('Invalid request or insufficient permissions.');
            return;
        }

        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error('No file uploaded or upload error.');
            return;
        }

        $file_path = $_FILES['csv_file']['tmp_name'];
        $update_existing = isset($_POST['update_existing']) && $_POST['update_existing'] == '1';

        $results = $this->process_csv_import($file_path, $update_existing);

        wp_send_json_success($results);
    }

    private function process_csv_import($file_path, $update_existing = false)
    {
        $results = array(
            'imported' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => 0,
            'error_messages' => array()
        );

        if (($handle = fopen($file_path, 'r')) !== FALSE) {
            $header = fgetcsv($handle); // Read header row
            $row_number = 1;

            while (($row = fgetcsv($handle)) !== FALSE) {
                $row_number++;
                
                if (count($row) < 3) { // At minimum need name, description, regular_price
                    $results['errors']++;
                    $results['error_messages'][] = "Row {$row_number}: Insufficient data columns";
                    continue;
                }

                $product_data = array(
                    'name' => isset($row[0]) ? sanitize_text_field($row[0]) : '',
                    'description' => isset($row[1]) ? wp_kses_post($row[1]) : '',
                    'regular_price' => isset($row[2]) ? floatval($row[2]) : 0,
                    'sale_price' => isset($row[3]) && !empty($row[3]) ? floatval($row[3]) : '',
                    'stock' => isset($row[4]) ? intval($row[4]) : 0,
                    'sku' => isset($row[5]) ? sanitize_text_field($row[5]) : '',
                    'category' => isset($row[6]) ? sanitize_text_field($row[6]) : '',
                    'image_url' => isset($row[7]) ? esc_url($row[7]) : ''
                );

                // Validate required fields
                if (empty($product_data['name']) || $product_data['regular_price'] <= 0) {
                    $results['errors']++;
                    $results['error_messages'][] = "Row {$row_number}: Missing required fields (name or regular_price)";
                    continue;
                }

                // Check if product exists by SKU
                $existing_product_id = null;
                if (!empty($product_data['sku'])) {
                    $existing_product_id = $this->get_product_by_sku($product_data['sku']);
                }

                if ($existing_product_id && !$update_existing) {
                    $results['skipped']++;
                    continue;
                }

                try {
                    if ($existing_product_id && $update_existing) {
                        // Update existing product
                        $this->update_product($existing_product_id, $product_data);
                        $results['updated']++;
                    } else {
                        // Create new product
                        $this->create_product($product_data);
                        $results['imported']++;
                    }
                } catch (Exception $e) {
                    $results['errors']++;
                    $results['error_messages'][] = "Row {$row_number}: " . $e->getMessage();
                }
            }

            fclose($handle);
        } else {
            throw new Exception('Could not open CSV file for reading.');
        }

        return $results;
    }

    private function get_product_by_sku($sku)
    {
        global $wpdb;
        
        $product_id = $wpdb->get_var($wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_sku' AND meta_value = %s",
            $sku
        ));

        return $product_id;
    }

    private function create_product($product_data)
    {
        // Create the post
        $post_data = array(
            'post_title' => $product_data['name'],
            'post_content' => $product_data['description'],
            'post_status' => 'publish',
            'post_type' => 'product'
        );

        $product_id = wp_insert_post($post_data);

        if (is_wp_error($product_id)) {
            throw new Exception('Failed to create product: ' . $product_id->get_error_message());
        }

        // Add meta data
        $this->save_product_meta($product_id, $product_data);

        // Handle category
        if (!empty($product_data['category'])) {
            $this->assign_product_category($product_id, $product_data['category']);
        }

        // Handle image
        if (!empty($product_data['image_url'])) {
            $this->set_product_image($product_id, $product_data['image_url']);
        }

        return $product_id;
    }

    private function update_product($product_id, $product_data)
    {
        // Update the post
        $post_data = array(
            'ID' => $product_id,
            'post_title' => $product_data['name'],
            'post_content' => $product_data['description']
        );

        $result = wp_update_post($post_data);

        if (is_wp_error($result)) {
            throw new Exception('Failed to update product: ' . $result->get_error_message());
        }

        // Update meta data
        $this->save_product_meta($product_id, $product_data);

        // Handle category
        if (!empty($product_data['category'])) {
            $this->assign_product_category($product_id, $product_data['category']);
        }

        // Handle image
        if (!empty($product_data['image_url'])) {
            $this->set_product_image($product_id, $product_data['image_url']);
        }

        return $product_id;
    }

    private function save_product_meta($product_id, $product_data)
    {
        update_post_meta($product_id, '_regular_price', $product_data['regular_price']);
        update_post_meta($product_id, '_price', $product_data['regular_price']); // Default price is regular price
        
        if ($product_data['sale_price'] !== '') {
            update_post_meta($product_id, '_sale_price', $product_data['sale_price']);
            update_post_meta($product_id, '_price', $product_data['sale_price']); // Override with sale price
        }
        
        update_post_meta($product_id, '_stock', $product_data['stock']);
        
        if (!empty($product_data['sku'])) {
            update_post_meta($product_id, '_sku', $product_data['sku']);
        }
    }    private function assign_product_category($product_id, $category_name)
    {
        // Check if taxonomy exists, if not register it
        if (!taxonomy_exists('product_category')) {
            $this->register_product_category_taxonomy();
        }

        // Get or create category term
        $term = term_exists($category_name, 'product_category');
        
        if (!$term) {
            // Create new term
            $term = wp_insert_term($category_name, 'product_category');
            if (is_wp_error($term)) {
                return;
            }
            $term_id = $term['term_id'];
        } else {
            // Use existing term - term_exists returns array with term_id and term_taxonomy_id
            $term_id = is_array($term) ? $term['term_id'] : $term;
        }

        // Assign category to product using the correct term ID
        wp_set_object_terms($product_id, array(intval($term_id)), 'product_category');
    }

    private function register_product_category_taxonomy()
    {
        register_taxonomy('product_category', 'product', array(
            'labels' => array(
                'name' => 'Product Categories',
                'singular_name' => 'Product Category'
            ),
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'product-category'),
            'show_in_rest' => true,
        ));
    }

    private function set_product_image($product_id, $image_url)
    {
        // Download and set featured image
        $image_id = $this->upload_image_from_url($image_url, $product_id);
        if ($image_id) {
            set_post_thumbnail($product_id, $image_id);
        }
    }

    private function upload_image_from_url($image_url, $post_id)
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Download image
        $temp_file = download_url($image_url);
        if (is_wp_error($temp_file)) {
            return false;
        }

        // Get file info
        $file_info = pathinfo($image_url);
        $filename = $file_info['basename'];

        // Prepare file array for wp_handle_sideload
        $file_array = array(
            'name' => $filename,
            'tmp_name' => $temp_file,
        );

        // Upload file
        $image_id = media_handle_sideload($file_array, $post_id);

        // Clean up temp file
        @unlink($temp_file);

        if (is_wp_error($image_id)) {
            return false;
        }

        return $image_id;
    }
}
