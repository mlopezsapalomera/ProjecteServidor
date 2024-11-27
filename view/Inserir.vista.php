<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Inserir Article</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <?php
    session_start();
    if (isset($_SESSION['error_message'])): ?>
        <div class="error" style="color: red;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>
    <form action="../controllers/insertar.controller.php" method="POST" enctype="multipart/form-data">
        <h2>Inserir Article</h2>
        <label for="nombre">Nom de l'animal:</label>
        <input type="text" id="nombre" name="nombre" required>
        <label for="cuerpo">Descripció de l'animal:</label>
        <textarea id="cuerpo" name="cuerpo" required></textarea>
        <label for="imagen">Imatge de l'animal:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>
        <button type="submit">Inserir</button>
    </form>
</body>
</html>