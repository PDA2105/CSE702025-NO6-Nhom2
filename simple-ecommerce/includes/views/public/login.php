<?php
if (is_user_logged_in()) {
    wp_redirect(home_url('/my-account/'));
    exit;
}

$error = '';
if (isset($_POST['simple_login_submit'])) {
    $creds = [
        'user_login'    => sanitize_text_field($_POST['username']),
        'user_password' => $_POST['password'],
        'remember'      => isset($_POST['remember']) ? true : false
    ];
    $user = wp_signon($creds, is_ssl());

    if (!is_wp_error($user)) {
        wp_redirect(home_url('/my-account/'));
        exit;
    } else {
        $error = $user->get_error_message();
    }
}
?>
<div class="simple-ecommerce-login">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
        <div class="login-error">
            <?php echo esc_html($error); ?>
        </div>
    <?php endif; ?>
    <form method="post" class="simple-login-form">
        <div class="form-row">
            <label for="username">Username or Email</label>
            <input type="text" name="username" id="username" required>
        </div>

        <div class="form-row">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="form-row">
            <label>
                <input type="checkbox" name="remember" value="1">
                Remember Me
            </label>
        </div>

        <div class="form-row">
            <input type="submit" name="simple_login_submit" value="Login">
        </div>

        <div class="login-links">
            <a href="<?php echo wp_lostpassword_url(); ?>">Lost your password?</a>
            <a href="<?php echo home_url('/register/'); ?>">Don't have an account? Register</a>
        </div>
    </form>
</div>
