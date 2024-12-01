<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <a href="perfil.vista.php" class="btn back-to-index">Tornar al Perfil</a>
    <form action="../controllers/cambiarContrasena.controller.php" method="POST">
        <h2>Cambiar Contraseña</h2>
        <div class="messages">
            <?php
            session_start();
            if (isset($_SESSION['error_message'])): ?>
                <div class="error" style="color: red;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="success" style="color: green;"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
        </div>
        <label for="current_password">Contraseña Actual:</label>
        <input type="password" id="current_password" name="current_password" required>
        <label for="new_password">Nueva Contraseña:</label>
        <input type="password" id="new_password" name="new_password" required>
        <label for="confirm_new_password">Confirmar Nueva Contraseña:</label>
        <input type="password" id="confirm_new_password" name="confirm_new_password" required>
        <button type="submit">Cambiar Contraseña</button>
    </form>
</body>
</html>