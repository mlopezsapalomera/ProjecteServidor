<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inserir Pokemon</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="container">
        <a href="../index.php" class="btn btn-secondary my-3">Tornar a l'índex</a>
        <?php
        session_start();
        if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>
        <form action="../controllers/insertar.controller.php" method="POST" enctype="multipart/form-data">
            <h2>Inserir Pokemon</h2>
            <div class="form-group">
                <label for="nombre">Nom del Pokemon:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="cuerpo">Descripció del Pokemon:</label>
                <textarea id="cuerpo" name="cuerpo" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="imagen">Imatge del Pokemon:</label>
                <input type="file" id="imagen" name="imagen" class="form-control-file" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Inserir</button>
        </form>
    </div>
</body>
</html>