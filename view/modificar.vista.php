<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Pokedex Global-Modificar Pokémon</title>
    <link rel="icon" href="../img/favicon.png" type="image/png">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <a href="../index.php" class="btn back-to-index">Tornar a l'índex</a>
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
        <label for="nombre">Nom del Pokémon:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_GET['nombre']); ?>" required>
        <label for="cuerpo">Descripció del Pokémon:</label>
        <textarea id="cuerpo" name="cuerpo" required><?php echo htmlspecialchars($_GET['cuerpo']); ?></textarea>
        <label for="imagen">Imatge:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">
        <button type="submit">Modificar</button>
    </form>

    <script>
        // Obtener los parámetros de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        const nombre = urlParams.get('nombre');
        const cuerpo = urlParams.get('cuerpo');
        const imagen = urlParams.get('imagen');

        // Asignar los valores a los campos del formulario
        document.getElementById('id').value = id;
        document.getElementById('nombre').value = nombre;
        document.getElementById('cuerpo').value = cuerpo;
    </script>
</body>
</html>