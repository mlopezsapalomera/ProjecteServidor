<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Pokedex Global-Modificar Pokémon</title>
    <link rel="icon" href="../img/favicon.png" type="image/png">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <a href="miPerfil.vista.php" class="btn back-to-index">Tornar a l'índex</a>
    <form action="../controllers/modificar.controller.php" method="POST" enctype="multipart/form-data">
        <h2>Modificar Pokémon</h2>
        <div class="messages">
            <?php
            session_start();
            if (isset($_SESSION['error_message'])): ?>
                <div class="error" style="color: red;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
        </div>
        <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>" required>
        <label for="imagen">Imatge del Pokemon:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>
        <button type="submit">Modificar</button>
    </form>
</body>
</html>