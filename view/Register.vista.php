<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar-se</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container">
        <a href="../index.php" class="btn btn-secondary my-3">Tornar a l'índex</a>
        <form action="../controllers/register.controller.php" method="POST">
            <h2>Registrar-se</h2>
            <div class="messages">
                <?php
                session_start();
                if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="nombre">Nom:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="contraseña">Contrasenya:</label>
                <input type="password" id="contraseña" name="contraseña" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirmar_contraseña">Confirmar Contrasenya:</label>
                <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar-se</button>
        </form>
    </div>
</body>
</html>