<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container">
        <a href="../index.php" class="btn btn-secondary my-3">Tornar a l'índex</a>
        <form action="../controllers/perfil.controller.php" method="POST" enctype="multipart/form-data">
            <h2>Mi Perfil</h2>
            <div class="messages">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                <?php endif; ?>
            </div>
            <div class="profile-preview text-center mb-3">
                <img src="../userProfile/img/<?php echo isset($_SESSION['imagen']) ? $_SESSION['imagen'] : 'default.jpg'; ?>" alt="Foto de Perfil" class="profile-icon">
            </div>
            <div class="form-group">
                <label for="nombre">Nom:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="imagen">Foto de Perfil:</label>
                <input type="file" id="imagen" name="imagen" class="form-control-file" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>