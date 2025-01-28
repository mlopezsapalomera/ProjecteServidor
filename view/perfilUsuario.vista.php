<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Pokedex Global-Perfil Usuario</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="icon" href="../img/favicon.png" type="image/png">
</head>
<body>
    <a href="../index.php" class="btn back-to-index">Tornar a l'índex</a>
    <?php
    session_start();
    require_once '../model/db.php';

    if (!isset($_GET['id'])) {
        echo "<div class='error'>No s'ha especificat l'ID de l'usuari.</div>";
        exit();
    }

    $usuario_id = $_GET['id'];
    $query = "SELECT nom, email, imagen FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<div class='error'>Usuari no trobat.</div>";
        exit();
    }

    $usuario = $result->fetch_assoc();
    ?>
    <form class="profile-form">
        <h2>Perfil de Usuario</h2>
        <div class="profile-preview">
            <img src="../userProfile/img/<?php echo htmlspecialchars($usuario['imagen']); ?>" alt="Foto de Perfil" class="profile-icon">
        </div>
        <label for="nombre">Nom:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nom']); ?>" readonly>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" readonly>
    </form>
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
        <!-- El contenido se cargará aquí desde perfilUsuario.controller.php -->
    </div>
    <script>
        function loadUserPokemons(pagina = 1) {
            const pokemonsPorPagina = document.getElementById('pokemons_por_pagina').value;
            const orden = document.getElementById('orden').value;
            fetch(`../controllers/perfilUsuario.controller.php?id=<?php echo $usuario_id; ?>&pagina=${pagina}&pokemons_por_pagina=${pokemonsPorPagina}&orden=${orden}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('mi-pokedex-container').innerHTML = data;
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadUserPokemons();

            document.getElementById('pokemons_por_pagina').addEventListener('change', () => {
                loadUserPokemons();
            });

            document.getElementById('orden').addEventListener('change', () => {
                loadUserPokemons();
            });
        });
    </script>
</body>
</html>