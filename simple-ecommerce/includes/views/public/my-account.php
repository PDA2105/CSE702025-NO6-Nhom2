<?php
// filepath: includes/views/public/my-account.php

// Ensure user is logged in
if (!is_user_logged_in()) {
    echo '<script>window.location.href="' . esc_url(home_url('/login/')) . '";</script>';
    return;
}

$current_user = wp_get_current_user();
$order_controller = new Simple_Ecommerce_Order_Controller();
$orders = $order_controller->get_user_orders($current_user->ID);

// Process profile update
$profile_updated = false;
$profile_error = '';

if (isset($_POST['update_profile_submit'])) {
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $display_name = sanitize_text_field($_POST['display_name']);
    
    $user_data = array(
        'ID' => $current_user->ID,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'display_name' => $display_name
    );
    
    // Only update email if it has changed and is valid
    $new_email = sanitize_email($_POST['email']);
    if ($new_email !== $current_user->user_email && is_email($new_email)) {
        $user_data['user_email'] = $new_email;
    }
    
    // Update user
    $result = wp_update_user($user_data);
    
    if (is_wp_error($result)) {
        $profile_error = $result->get_error_message();
    } else {
        $profile_updated = true;
    }
    
    // Handle password update if both fields are filled
    if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        if ($_POST['password'] === $_POST['confirm_password']) {
            wp_set_password($_POST['password'], $current_user->ID);
            
            // Re-authenticate the user
            $creds = array(
                'user_login'    => $current_user->user_login,
                'user_password' => $_POST['password'],
                'remember'      => true
            );
            
            wp_signon($creds, is_ssl());
            
            $profile_updated = true;
        } else {
            $profile_error = 'Passwords do not match.';
        }
    }
}

// Refresh user data after update
$current_user = wp_get_current_user();
?>

<div class="simple-ecommerce-account">
    <h2>My Account</h2>
    
    <div class="account-navigation">
        <ul>
            <li><a href="#dashboard" class="active">Dashboard</a></li>
            <li><a href="#orders">Orders</a></li>
            <li><a href="#profile">Edit Profile</a></li>
            <li><a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a></li>
        </ul>
    </div>
    
    <div class="account-content">
        <!-- Dashboard Tab -->
        <div id="dashboard" class="account-tab active">
            <h3>Dashboard</h3>
            <p>Hello, <?php echo esc_html($current_user->display_name); ?>!</p>
            <p>From your account dashboard, you can view your recent orders and edit your profile information.</p>
        </div>
        
        <!-- Orders Tab -->
        <div id="orders" class="account-tab">
            <h3>Orders</h3>
            
            <?php if (empty($orders)): ?>
                <p>No orders found.</p>
            <?php else: ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo esc_html($order['id']); ?></td>
                                <td><?php echo esc_html(date('F j, Y', strtotime($order['date']))); ?></td>
                                <td><?php echo esc_html(ucfirst($order['status'])); ?></td>
                                <td><?php echo Simple_Ecommerce_Helpers::format_price($order['total']); ?></td>
                                <td>
                                    <a href="<?php echo add_query_arg('view-order', $order['id'], home_url('/my-account/')); ?>">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Profile Tab -->
        <div id="profile" class="account-tab">
            <h3>Edit Profile</h3>
            
            <?php if ($profile_updated): ?>
                <div class="profile-updated">
                    <p>Profile updated successfully.</p>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($profile_error)): ?>
                <div class="profile-error">
                    <p><?php echo esc_html($profile_error); ?></p>
                </div>
            <?php endif; ?>
            
            <form method="post" class="profile-form">
                <div class="form-row">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($current_user->first_name); ?>">
                </div>
                
                <div class="form-row">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($current_user->last_name); ?>">
                </div>
                
                <div class="form-row">
                    <label for="display_name">Display Name</label>
                    <input type="text" name="display_name" id="display_name" value="<?php echo esc_attr($current_user->display_name); ?>">
                </div>
                
                <div class="form-row">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="<?php echo esc_attr($current_user->user_email); ?>">
                </div>
                
                <h4>Password Change</h4>
                
                <div class="form-row">
                    <label for="password">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" id="password">
                </div>
                
                <div class="form-row">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password">
                </div>
                
                <div class="form-row">
                    <input type="submit" name="update_profile_submit" value="Update Profile">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
jQuery(function($) {
    // Tab navigation
    $('.account-navigation a').on('click', function(e) {
        e.preventDefault();
        
        // Update active tab in navigation
        $('.account-navigation a').removeClass('active');
        $(this).addClass('active');
        
        // Show selected tab content
        var tabId = $(this).attr('href');
        $('.account-tab').removeClass('active');
        $(tabId).addClass('active');
    });
    
    // Check if URL contains a view-order parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('view-order')) {
        // Switch to orders tab
        $('.account-navigation a[href="#orders"]').trigger('click');
    }
});
</script>