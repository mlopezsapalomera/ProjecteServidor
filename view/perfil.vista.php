<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <a href="../index.php" class="btn back-to-index">Tornar a l'índex</a>
    <form action="../controllers/perfil.controller.php" method="POST" enctype="multipart/form-data">
        <h2>Mi Perfil</h2>
        <div class="messages">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="success" style="color: green;"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error" style="color: red;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
        </div>
        <div class="profile-preview">
            <img src="../userProfile/img/<?php echo isset($_SESSION['imagen']) ? $_SESSION['imagen'] : 'default.jpg'; ?>" alt="Foto de Perfil" class="profile-icon">
        </div>
        <label for="nombre">Nom:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ''; ?>" required>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" readonly>
        <label for="imagen">Foto de Perfil:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">
        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>