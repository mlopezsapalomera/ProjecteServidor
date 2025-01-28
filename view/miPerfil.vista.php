<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Pokedex Global-Mi Perfil</title>
    <link rel="icon" href="../img/favicon.png" type="image/png">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <a href="../index.php" class="btn back-to-index">Tornar a l'índex</a>
    <form class="profile-form" action="../controllers/perfil.controller.php" method="POST" enctype="multipart/form-data">
        <h2>Mi Perfil</h2>
        <div class="messages">
            <?php
            session_start();
            if (isset($_SESSION['success_message'])): ?>
                <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>
        </div>
        <div class="profile-preview">
            <img src="../userProfile/img/<?php echo isset($_SESSION['imagen']) ? $_SESSION['imagen'] : 'default.jpg'; ?>" alt="Foto de Perfil" class="profile-icon">
            <img src="../img/qr_<?php echo $_SESSION['usuario_id']; ?>.png" alt="Código QR">
        </div>
        <label for="nombre">Nom:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : ''; ?>" required>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" readonly>
        <label for="imagen">Foto de Perfil:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">
        <button type="submit">Guardar Cambios</button>
    </form>
    <div class="profile-preview">
        <a href="cambiarContrasena.vista.php" class="btn btn-secondary">Cambiar Contraseña</a>
    </div>
    <div class="friends-list">
        <div id="friends-container">
            <!-- Lista de amigos se cargará aquí -->
        </div>
    </div>
    <form id="pokemons-form">
        <label for="pokemons_por_pagina" class="label-pokemons">Pokemons per pàgina:</label>
        <select name="pokemons_por_pagina" id="pokemons_por_pagina" class="select-pokemons">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
        </select>
        <label for="orden" class="label-orden">Orden:</label>
        <select name="orden" id="orden" class="select-orden">
            <option value="asc">Ascendent</option>
            <option value="desc">Descendent</option>
        </select>
    </form>
    <div id="mi-pokedex-container">
        <!-- El contenido se cargará aquí desde miPerfil.controller.php -->
    </div>
    <script>
        function loadMisPokemons(pagina = 1) {
            const pokemonsPorPagina = document.getElementById('pokemons_por_pagina').value;
            const orden = document.getElementById('orden').value;
            fetch(`../controllers/miPerfil.controller.php?pagina=${pagina}&pokemons_por_pagina=${pokemonsPorPagina}&orden=${orden}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('mi-pokedex-container').innerHTML = data;
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadMisPokemons();

            document.getElementById('pokemons_por_pagina').addEventListener('change', () => {
                loadMisPokemons();
            });

            document.getElementById('orden').addEventListener('change', () => {
                loadMisPokemons();
            });
        });

        function loadFriends() {
            fetch('../controllers/friends.controller.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('friends-container').innerHTML = data;
                });
        }

        document.addEventListener('DOMContentLoaded', loadFriends);
    </script>
</body>
</html>