<?php
// Check if user is already logged in
if (is_user_logged_in()) {
    echo '<script>window.location.href="' . esc_url(home_url('/my-account/')) . '";</script>';
    return;
}

// Check if registration is enabled
if (!get_option('users_can_register')) {
    ?>
    <p>Registration is currently disabled. Please contact the administrator.</p>
    <?php
    return;
}

// Process registration form
$error = '';
$success = false;

if (isset($_POST['simple_register_submit'])) {
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate form data
    if (empty($username)) {
        $error = 'Please enter a username.';
    } elseif (empty($email) || !is_email($email)) {
        $error = 'Please enter a valid email address.';
    } elseif (empty($password)) {
        $error = 'Please enter a password.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Create the user
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            $error = $user_id->get_error_message();
        } else {
            // Set the user role
            $user = new WP_User($user_id);
            $user->set_role('customer');
            
            // Send notification
            wp_new_user_notification($user_id, null, 'both');
            
            $success = true;
            
            // Auto-login the user
            $creds = array(
                'user_login'    => $username,
                'user_password' => $password,
                'remember'      => true
            );
            
            $user = wp_signon($creds, is_ssl());
            echo '<script>window.location.href="' . esc_url(home_url('/login/')) . '";</script>';
            return;
           
        }
    }
}
?>

<div class="simple-ecommerce-register">
    <h2>Register</h2>
    
    <?php if ($success): ?>
        <div class="register-success">
            <p>Registration successful! You are now logged in.</p>
        </div>
    <?php else: ?>
        
        <?php if (!empty($error)): ?>
            <div class="register-error">
                <?php echo esc_html($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="post" class="simple-register-form">
            <div class="form-row">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            
            <div class="form-row">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            
            <div class="form-row">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            
            <div class="form-row">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            
            <div class="form-row">
                <input type="submit" name="simple_register_submit" value="Register">
            </div>
            
            <div class="register-links">
                <a href="<?php echo home_url('/login/'); ?>">Already have an account? Login</a>
            </div>
        </form>
    <?php endif; ?>
</div>