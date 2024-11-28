<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modificar Pokémon</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container">
        <a href="../index.php" class="btn btn-secondary my-3">Tornar a l'índex</a>
        <form action="../controllers/modificar.controller.php" method="POST" enctype="multipart/form-data">
            <h2>Modificar Pokémon</h2>
            <div class="messages">
                <?php
                session_start();
                if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                <?php endif; ?>
            </div>
            <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>" required>
            <div class="form-group">
                <label for="nombre">Nom del Pokémon:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($_GET['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="cuerpo">Descripció del Pokémon:</label>
                <textarea id="cuerpo" name="cuerpo" class="form-control" required><?php echo htmlspecialchars($_GET['cuerpo']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="imagen">Imatge:</label>
                <input type="file" id="imagen" name="imagen" class="form-control-file" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Modificar</button>
        </form>
    </div>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        const nombre = urlParams.get('nombre');
        const cuerpo = urlParams.get('cuerpo');
        const imagen = urlParams.get('imagen');

        document.getElementById('id').value = id;
        document.getElementById('nombre').value = nombre;
        document.getElementById('cuerpo').value = cuerpo;
    </script>
</body>
</html>