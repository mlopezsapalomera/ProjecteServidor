<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sessió</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>
    <header>
        <h1>Iniciar Sessió</h1>
    </header>
    <main id="main-content">
        <form action="../controllers/login.controller.php" method="POST">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?php echo isset($_COOKIE['remember_me_email']) ? $_COOKIE['remember_me_email'] : ''; ?>" required>
            <label for="contraseña">Contrasenya:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            <label for="remember_me">Recorda les meves credencials</label>
            <input type="checkbox" id="remember_me" name="remember_me" <?php echo isset($_COOKIE['remember_me']) ? 'checked' : ''; ?>>
            <?php
            if (isset($_SESSION["login_attempts"]) && $_SESSION["login_attempts"] >= 3) {
                echo '<div class="g-recaptcha" data-sitekey="6LeeSJAqAAAAABKbnLFeISetFv_QeaPbcS-72n7q"></div>';
            }
            if (isset($errors['captcha'])) {
                $error = $errors['captcha'];
                echo "<div class='invalid-feedback d-block'>$error</div>";
            }
            ?>
            <button type="submit">Logar-se</button>
            <a href="recuperarContrasena.vista.php" class="btn">No recordes la teva contrasenya?</a>
        </form>
    </main>
</body>
</html>