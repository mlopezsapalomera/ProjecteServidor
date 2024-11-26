<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sessió</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <a href="../index.php" class="btn back-to-index">Tornar a l'índex</a>
    <form action="../controllers/login.controller.php" method="POST">
        <h2>Iniciar Sessió</h2>
        <div class="messages">
            <?php
            session_start();
            if (isset($_SESSION['error_message'])): ?>
                <div class="error" style="color: red;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
        </div>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo isset($_SESSION['login_email']) ? $_SESSION['login_email'] : ''; unset($_SESSION['login_email']); ?>" required>
        <label for="contraseña">Contrasenya:</label>
        <input type="password" id="contraseña" name="contraseña" required>
        <button type="submit">Iniciar Sessió</button>
    </form>
</body>
</html>