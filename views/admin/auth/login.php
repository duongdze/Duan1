<?php
$error = '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
    <link rel="stylesheet" href="<?= BASE_ASSETS_ADMIN ?>css/style.css">

</head>

<body>
    <div class="login-wrapper">
        <div class="login-container">
            <!-- Nếu nhập sai sẽ hiện thông báo error -->
            <?php if (!empty($error)) : ?>
                <div class="login-alert alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="<?= BASE_URL_ADMIN ?>&action=loginProcess" method="POST" class="login-form">
                <h2 class="login-title">Login</h2>
                <div class="login-input-group">
                    <input type="email" id="email" name="email" placeholder="Email" required class="login-input" />
                    <i class="ri-user-fill login-input__icon"></i>
                </div>
                <div class="login-input-group">
                    <input type="password" id="password" name="password" placeholder="Password" required autocomplete="new-password" class="login-input" />
                    <i class="ri-eye-off-fill login-input__icon login-input__toggle-password" id="togglePassword"></i>
                </div>
                <div class="login-remember">
                    <label class="login-remember__label"><input type="checkbox" class="login-remember__checkbox" />Remember me/</label>
                    <a href="#" class="login-remember__link">Forgot Password?</a>
                </div>
                <button type="submit" class="login-submit">Login</button>
                <div class="login-social">
                    <a href="#" class="login-social__link"> <i class="ri-google-fill login-social__icon"></i> Google </a>--<a href="#" class="login-social__link">
                        <i class="ri-facebook-fill login-social__icon"></i> Facebook
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="./script.js"></script>

</body>

</html>