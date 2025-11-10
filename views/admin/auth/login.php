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
    <div class="body-login">
        <div class="login-content">
            <!-- Nếu nhập sai sẽ hiện thông báo error -->
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="<?= BASE_URL_ADMIN ?>&action=loginProcess" method="POST">
                <h2>Login</h2>
                <div class="input-box">
                    <input type="text" id="username" placeholder="Username" required />
                    <i class="ri-user-fill"></i>
                </div>
                <div class="input-box">
                    <input type="password" id="password" placeholder="Password" required autocomplete="new-password" />
                    <i class="ri-eye-off-fill toggle-password" id="togglePassword"></i>
                </div>
                <div class="remember">
                    <label><input type="checkbox" />Remember me/</label>
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit" class="btn-login">Login</button>
                <div class="button">
                    <a href="#"> <i class="ri-google-fill"></i> Google </a>--<a href="#">
                        <i class="ri-facebook-fill"></i> Facebook
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="./script.js"></script>

</body>

</html>