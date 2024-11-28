<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sessió</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container">
        <a href="../index.php" class="btn btn-secondary my-3">Tornar a l'índex</a>
        <form action="../controllers/login.controller.php" method="POST">
            <h2>Iniciar Sessió</h2>
            <div class="messages">
                <?php
                session_start();
                if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($_SESSION['login_email']) ? $_SESSION['login_email'] : ''; unset($_SESSION['login_email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contraseña">Contrasenya:</label>
                <input type="password" id="contraseña" name="contraseña" class="form-control" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" id="remember_me" name="remember_me" class="form-check-input">
                <label for="remember_me" class="form-check-label">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sessió</button>
        </form>
    </div>
</body>
</html>