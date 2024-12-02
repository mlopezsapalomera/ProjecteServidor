<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Registrar-se</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <a href="../index.php" class="btn back-to-index">Tornar a l'índex</a>
    <form action="../controllers/register.controller.php" method="POST">
        <h2>Registrar-se</h2>
        <div class="messages">
            <?php
            session_start();
            if (isset($_SESSION['error_message'])): ?>
                <div class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
        </div>
        <label for="nombre">Nom:</label>
        <input type="text" id="nombre" name="nombre" value="" required>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="" required>
        <label for="contraseña">Contrasenya:</label>
        <input type="password" id="contraseña" name="contraseña" required>
        <label for="confirmar_contraseña">Confirmar Contrasenya:</label>
        <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" required>
        <button type="submit">Registrar-se</button>
    </form>
</body>
</html>