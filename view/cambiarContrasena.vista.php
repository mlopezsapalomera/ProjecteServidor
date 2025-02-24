/ProjecteServidor/view/cambiarContrasena.vista.php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contrasenya</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="icon" href="../img/favicon.png" type="image/png">
</head>
<body>
    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>
    <a href="miPerfil.vista.php" class="btn back-to-index">Tornar al Perfil</a>
    <header>
        <h1>Cambiar Contrasenya</h1>
    </header>
    <main id="main-content">
        <form action="../controllers/cambiarContrasena.controller.php" method="POST">
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
            <label for="current_password">Contraseña Actual:</label>
            <input type="password" id="current_password" name="current_password" required>
            <label for="new_password">Nueva Contraseña:</label>
            <input type="password" id="new_password" name="new_password" required>
            <label for="confirm_new_password">Confirmar Nueva Contraseña:</label>
            <input type="password" id="confirm_new_password" name="confirm_new_password" required>
            <button type="submit">Cambiar Contrasenya</button>
        </form>
    </main>
</body>
</html>