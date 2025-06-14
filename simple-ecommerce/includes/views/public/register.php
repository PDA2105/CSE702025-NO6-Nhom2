<?php
// Check if user is already logged in
if (is_user_logged_in()) {
    echo '<script>window.location.href="' . esc_url(home_url('/my-account/')) . '";</script>';
    return;
}

// Check if registration is enabled
if (!get_option('users_can_register')) {
    echo '<p>Registration is currently disabled. Please contact the administrator.</p>';
    return;
}

// Process registration form
$error = '';
$success = false;

if (isset($_POST['simple_register_submit'])) {
    // Get only the required fields
    $username = isset($_POST['username']) ? sanitize_user($_POST['username']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Validate form data
    if (empty($username)) {
        $error = 'Vui lòng nhập tên đăng nhập.';
    } elseif (username_exists($username)) {
        $error = 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.';
    } elseif (empty($email) || !is_email($email)) {
        $error = 'Vui lòng nhập địa chỉ email hợp lệ.';
    } elseif (email_exists($email)) {
        $error = 'Email đã được sử dụng. Vui lòng chọn email khác.';
    } elseif (empty($password)) {
        $error = 'Vui lòng nhập mật khẩu.';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } elseif ($password !== $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } else {
        // Create user with only username, email, password
        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            $error = 'Lỗi tạo tài khoản: ' . $user_id->get_error_message();
        } else {
            // Set user role to customer
            $user = new WP_User($user_id);
            $user->set_role('customer');

            // Send notification email (optional - can be disabled)
            // wp_new_user_notification($user_id, null, 'both');

            $success = true;

            // Auto-login the user after successful registration
            $creds = array(
                'user_login' => $username,
                'user_password' => $password,
                'remember' => true
            );

            $user_login = wp_signon($creds, is_ssl());

            if (!is_wp_error($user_login)) {
                // Redirect to my-account page after successful login
                echo '<script>window.location.href="' . esc_url(home_url('/login/')) . '";</script>';
                return;
            }
        }
    }
}
?>

<div class="simple-ecommerce-register">
    <div class="register-container">
        <div class="box-img-register">
            <img src="https://shopdunk.com/images/uploaded/banner/TND_M402_010%201.jpeg" alt="Register Image" />
        </div>
        <div class="box-ui-register">
            <h2>Đăng ký tài khoản</h2>
            <?php if ($success): ?>
                <div class="register-success">
                    <p>Đăng ký thành công! Bạn đã đăng nhập.</p>
                </div>
            <?php else: ?>
                <?php if (!empty($error)): ?>
                    <div class="register-error">
                        <?php echo esc_html($error); ?>
                    </div>
                <?php endif; ?>
                <form method="post" class="simple-register-form">
                    <div class="form-row3">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" name="username" id="username" required>
                    </div>

                    <div class="form-row3">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="form-row3">
                        <label for="password">Mật khẩu</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    <div class="form-row3">
                        <label for="confirm_password">Xác nhận mật khẩu</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>

                    <div class="form-row3">
                        <input type="submit" name="simple_register_submit" value="Đăng Ký">
                    </div>

                    <div class="register-links">
                        <span>Bạn đã có tài khoản?</span>
                        <span><a href="<?php echo home_url('/login/'); ?>">Đăng nhập ngay</a></span>
                    </div>
                </form>
            <?php endif; ?>
        </div>

    </div>

</div>