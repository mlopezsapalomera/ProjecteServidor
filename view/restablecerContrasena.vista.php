<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Pokedex Global-Restablecer Contraseña</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="icon" href="../img/favicon.png" type="image/png">
</head>
<body>
    <a href="login.vista.php" class="btn back-to-index">Tornar a l'inici de sessió</a>
    <form action="../controllers/restablecerContrasena.controller.php" method="POST">
        <h2>Restablecer Contraseña</h2>
        <div class="messages">
            <?php
            session_start();
            if (isset($_SESSION['error_message'])): ?>
                <div class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
        </div>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>" required>
        <label for="new_password">Nova Contrasenya:</label>
        <input type="password" id="new_password" name="new_password" required>
        <label for="confirm_new_password">Confirmar Nova Contrasenya:</label>
        <input type="password" id="confirm_new_password" name="confirm_new_password" required>
        <button type="submit">Restablir contrasenya</button>
    </form>
</body>
</html>