<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link rel="icon" href="../img/favicon.png" type="image/png">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>
    <header>
        <h1>Mi Perfil</h1>
    </header>
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

    <div class="profile-preview">
        <button id="generate-qr" class="btn btn-primary">Generar Código QR</button>
        <div id="qr-code-image-container"></div>
    </div>
    <div class="qr-code-reader">
        <form id="qr-reader-form" enctype="multipart/form-data">
            <label for="qr_image">Subir Código QR:</label>
            <input type="file" id="qr_image" name="qr_image" accept="image/*">
            <button type="submit" class="btn btn-primary">Leer Código QR</button>
        </form>
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
        document.getElementById('generate-qr').addEventListener('click', function() {
            fetch('../controllers/generateQrCode.controller.php')
                .then(response => response.json())
                .then(data => {
                    const qrCodeContainer = document.getElementById('qr-code-image-container');
                    qrCodeContainer.innerHTML = `<img src="${data.qrCodeUrl}" alt="Código QR">`;
                });
        });

        document.querySelector('form.profile-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('.profile-preview img').src = data.newImageUrl;
                    alert('Perfil actualizado correctamente.');
                } else {
                    alert('Error al actualizar el perfil: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error al actualizar el perfil: ' + error.message);
            });
        });

        document.getElementById('qr-reader-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('../controllers/readQrCode.controller.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const qrCodeContainer = document.getElementById('qr-code-image-container');
                    qrCodeContainer.innerHTML = `<a href="${data.url}" target="_blank">${data.url}</a>`;
                } else {
                    alert('Error al leer el código QR: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error al leer el código QR: ' + error.message);
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            loadMisPokemons();

            document.getElementById('pokemons_por_pagina').addEventListener('change', () => {
                loadMisPokemons();
            });

            document.getElementById('orden').addEventListener('change', () => {
                loadMisPokemons();
            });
        });

        function loadMisPokemons(pagina = 1) {
            const pokemonsPorPagina = document.getElementById('pokemons_por_pagina').value;
            const orden = document.getElementById('orden').value;
            fetch(`../controllers/miPerfil.controller.php?pagina=${pagina}&pokemons_por_pagina=${pokemonsPorPagina}&orden=${orden}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('mi-pokedex-container').innerHTML = data;
                });
        }

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