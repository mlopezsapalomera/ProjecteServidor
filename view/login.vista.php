<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sessió</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <a href="../index.php" class="btn back-to-index">Tornar a l'índex</a>
    <form id="login-form" action="../controllers/login.controller.php" method="POST">
        <h2>Iniciar Sessió</h2>
        <div class="messages">
            <?php
            session_start();
            if (isset($_SESSION['error_message'])): ?>
                <div class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
        </div>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo isset($_COOKIE['remember_me_email']) ? $_COOKIE['remember_me_email'] : ''; ?>" required>
        <label for="contraseña">Contrasenya:</label>
        <input type="password" id="contraseña" name="contraseña" value="<?php echo isset($_COOKIE['remember_me_password']) ? $_COOKIE['remember_me_password'] : ''; ?>" required>
        <label for="remember_me">Remember me</label>
        <input type="checkbox" id="remember_me" name="remember_me" <?php echo isset($_COOKIE['remember_me_email']) ? 'checked' : ''; ?>>
        <?php
        if (isset($_SESSION["login_attempts"]) && $_SESSION["login_attempts"] >= 3) {
            echo '<div class="g-recaptcha" data-sitekey="6LeeSJAqAAAAABKbnLFeISetFv_QeaPbcS-72n7q"></div>';
        }
        if (isset($errors['captcha'])) {
            $error = $errors['captcha'];
            echo "<div class='invalid-feedback d-block'>$error</div>";
        }
        ?>
        <button type="submit">Iniciar Sessió</button>
    </form>
</body>
</html>