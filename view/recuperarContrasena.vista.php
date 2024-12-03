<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Pokedex Global-Recuperar Contraseña</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="icon" href="../img/favicon.png" type="image/png">
</head>
<body>
    <a href="login.vista.php" class="btn back-to-index">Tornar a l'inici de sessió</a>
    <form action="../controllers/recuperarContrasena.controller.php" method="POST">
        <h2>Recuperar Contraseña</h2>
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
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>