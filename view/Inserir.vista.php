<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inserir Pokemon</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="icon" href="../img/favicon.png" type="image/png">
    <script src="../scripts/fetchPokemons.js" defer></script>
    <script src="../scripts/autocompletePokemon.js" defer></script>
</head>
<body>
    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>
    <header>
        <h1>Inserir Pokemon</h1>
    </header>
    <main id="main-content">
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
            <label for="pokemon-dropdown">Selecciona un Pokemon:</label>
            <select id="pokemon-dropdown" name="pokemon-dropdown" required></select>
            <label for="força">Força:</label>
            <input type="number" id="força" name="força" required readonly>
            <label for="dany">Dany:</label>
            <input type="number" id="dany" name="dany" required readonly>
            <label for="vida">Vida:</label>
            <input type="number" id="vida" name="vida" required readonly>
            <label for="tipus">Tipus:</label>
            <input type="text" id="tipus" name="tipus" required readonly>
            <label for="imagen">Imatge del Pokemon:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>
            <button type="submit">Inserir</button>
        </form>
    </main>
</body>
</html>