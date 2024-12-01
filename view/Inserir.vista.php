<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Inserir Pokemon</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <a href="../index.php" class="btn back-to-index">Tornar a l'índex</a>
    <?php
    session_start();
    ?>
    <div class="messages">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>
    </div>
    <form action="../controllers/insertar.controller.php" method="POST" enctype="multipart/form-data">
        <h2>Inserir Pokemon</h2>
        <label for="nombre">Nom del Pokemon:</label>
        <input type="text" id="nombre" name="nombre" required>
        <label for="cuerpo">Descripció del Pokemon:</label>
        <textarea id="cuerpo" name="cuerpo" required></textarea>
        <label for="imagen">Imatge del Pokemon:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>
        <button type="submit">Inserir</button>
    </form>
</body>
</html>