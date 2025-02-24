<?php
session_start(); // Inicia la sessió
require 'model/db.php'; // Connexió a la base de dades
require 'articles.php'; // Inclou la lògica per mostrar pokemons
require 'env.php';

// Conexión a la base de datos
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Verificar si existeix la cookie de "Remember me"
if (!isset($_SESSION['usuario_id']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];

    // Preparar la consulta per obtenir l'usuari
    $stmt = $conn->prepare("SELECT user_id, expiry FROM user_tokens WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $expiry);
        $stmt->fetch();

        // Verificar si el token ha expirado
        if (strtotime($expiry) > time()) {
            // Obtenir les dades de l'usuari
            $stmt = $conn->prepare("SELECT id, nom, email, rol, imagen FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $nombre, $email, $rol, $imagen);
            $stmt->fetch();

            // Iniciar sessió
            $_SESSION['usuario_id'] = $id;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['email'] = $email;
            $_SESSION['rol'] = $rol;
            $_SESSION['usuario'] = $email;
            $_SESSION['imagen'] = $imagen;
        } else {
            // Eliminar el token si ha expirado
            $stmt = $conn->prepare("DELETE FROM user_tokens WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            setcookie('remember_me', '', time() - 3600, '/'); // Eliminar la cookie
        }
    }

    if ($stmt) {
        $stmt->close();
    }
}

// Comprova si l'usuari està connectat
$is_logged_in = isset($_SESSION['usuario']);
$is_admin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';

// Obtenir missatges de sessió
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Netejar missatges de sessió
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Obtenir el nombre de pokemons per pàgina des del desplegable o establir un valor per defecte
$pokemons_per_pagina = isset($_GET['pokemons_por_pagina']) ? (int)$_GET['pokemons_por_pagina'] : 5;

// Obtenir l'ordre dels pokemons des del desplegable o establir un valor per defecte
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'asc';

// Obtenir el terme de cerca
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>
    <header>
        <h1>Pokedex Global</h1>
        <div class="user-actions">
            <?php if ($is_logged_in): ?>
                <div class="user-profile">
                    <img src="userProfile/img/<?php echo $_SESSION['imagen'] ?? 'default.jpg'; ?>" alt="Foto de perfil de <?php echo $_SESSION['nombre']; ?>" class="profile-icon" id="profile-icon">
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="view/miPerfil.vista.php" class="btn">Mi Perfil</a>
                        <a href="view/vistaAjax.php" class="btn" id="view-articles">Ver Artículos</a>
                        <?php if ($is_admin): ?>
                            <a href="view/vistaUsuaris.vista.html" class="btn">Vista Usuaris</a>
                        <?php endif; ?>
                        <a href="controllers/logout.controller.php" class="btn">Tancar Sessió</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="view/login.vista.php" class="btn btn-primary">Logar-se</a>
                <a href="view/Register.vista.php" class="btn btn-secondary">Registrar-se</a>
            <?php endif; ?>
        </div>
    </header>
    <main id="main-content">
        <div class="main-left">
            <!-- Formulario para seleccionar el número de pokemons por página, el orden y buscar por nombre -->
            <form id="pokemons-form" method="GET" action="index.php">
                <label for="pokemons_por_pagina" class="label-pokemons">Pokemons per pàgina:</label>
                <select name="pokemons_por_pagina" id="pokemons_por_pagina" class="select-pokemons" onchange="document.getElementById('pokemons-form').submit();">
                    <option value="5" <?php echo $pokemons_per_pagina == 5 ? 'selected' : ''; ?>>5</option>
                    <option value="10" <?php echo $pokemons_per_pagina == 10 ? 'selected' : ''; ?>>10</option>
                    <option value="15" <?php echo $pokemons_per_pagina == 15 ? 'selected' : ''; ?>>15</option>
                    <option value="20" <?php echo $pokemons_per_pagina == 20 ? 'selected' : ''; ?>>20</option>
                </select>
                <label for="orden" class="label-orden">Orden:</label>
                <select name="orden" id="orden" class="select-orden" onchange="document.getElementById('pokemons-form').submit();">
                    <option value="asc" <?php echo $orden == 'asc' ? 'selected' : ''; ?>>Ascendent</option>
                    <option value="desc" <?php echo $orden == 'desc' ? 'selected' : ''; ?>>Descendent</option>
                </select>
            </form>
        </div>
        <div class="main-center">
            <!-- Contenido principal -->
            <?php
            // Mostrar los pokemons utilizando la función mostrarPokemons
            echo mostrarPokemons($pokemons_per_pagina, $orden, $search_term);
            ?>
        </div>
        <div class="main-right">
            <div class="search-insert-container">
                <div class="search-insert-box">
                    <div class="search">
                        <form action="index.php" method="GET">
                            <label for="search" class="label-search">Buscar Pokemon:</label>
                            <input type="text" name="search" id="search" class="search-box" placeholder="Buscar Pokemon" value="<?php echo $search_term; ?>">
                        </form>
                    </div>
                    <?php if ($is_logged_in): ?>
                        <div class="insert-animal">
                            <a href="view/Inserir.vista.php" class="btn btn-primary">Inserir Pokemon</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.getElementById('profile-icon').addEventListener('click', () => {
            document.getElementById('dropdown-menu').classList.toggle('show');
        });

        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').style.display = 'block';
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = 'none';
        }
    </script>

    <!-- Modal for displaying the image in large size -->
    <div id="imageModal">
        <span onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="">
    </div>
</body>
</html>