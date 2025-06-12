<?php
if (!defined('ABSPATH')) exit; 

if (is_user_logged_in()) {
    wp_redirect(home_url('/my-account/'));
    exit;
}

$error = '';

if (isset($_POST['simple_login_submit'])) {
    $creds = [
        'user_login' => sanitize_text_field($_POST['username']),
        'user_password' => $_POST['password'],
        'remember' => isset($_POST['remember']) ? true : false
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
<div class="login-container">
    <div class="box-img">
        <div class="img-login">
            <img src="https://shopdunk.com/images/uploaded/banner/VNU_M492_08%201.jpeg" alt="Login Image" />
        </div>
    </div>
    <div class="box-login">
        <div class="simple-ecommerce-login">
            <h2>Đăng Nhập</h2>
            <?php if (!empty($error)): ?>
                <div class="login-error">
                    <?php echo esc_html($error); ?>
                </div>
            <?php endif; ?>
            <form method="post" class="simple-login-form">
                <div class="form-row1">
                    <label for="username">Tên đăng nhập hoặc Email</label>
                    <input type="text" name="username" id="username" required>
                </div>

                <div class="form-row1">
                    <label for="password">Mật Khẩu</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="form-row2">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1" />
                        Nhớ mật khẩu
                    </label>
                    <a href="<?php echo wp_lostpassword_url(); ?>">Quên mật khẩu?</a>
                </div>

                <div class="form-row">
                    <input type="submit" name="simple_login_submit" value="Đăng Nhập">
                </div>                <div class="login-links">
                    <span>Bạn chưa có tài khoản?</span>
                    <span><a href="<?php echo home_url('/register/'); ?>">Đăng kí ngay</a></span>
                </div>
            </form>
        </div>
    </div>
</div>
