<?php
// Marcos Lopez Medina
session_start(); // Inicia la sessió
require 'model/db.php'; // Connexió a la base de dades
require 'articles.php'; // Inclou la lògica per mostrar articles

// Comprova si l'usuari està connectat
$is_logged_in = isset($_SESSION['usuario']);
$is_admin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';

// Obtenir missatges de sessió
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Netejar missatges de sessió
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Obtenir el nombre d'articles per pàgina des del desplegable o establir un valor per defecte
$articulos_por_pagina = isset($_GET['articulos_por_pagina']) ? (int)$_GET['articulos_por_pagina'] : 5;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestió d'Articles</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <header>
        <h1>Llista d'Aanimals</h1>
        <div class="insert-animal">
            <?php if ($is_logged_in): ?>
                <a href="view/Inserir.vista.html" class="btn">Inserir Animal</a>
            <?php endif; ?>
        </div>
        <div class="user-actions">
            <?php if ($is_logged_in): ?>
                <div class="user-profile">
                    <img src="userProfile/img/<?php echo $_SESSION['imagen'] ?? 'default.jpg'; ?>" alt="User Profile" class="profile-icon" id="profile-icon">
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="view/perfil.vista.php">Mi Perfil</a>
                        <a href="view/misAnimales.vista.html">Mis Animales</a>
                        <?php if ($is_admin): ?>
                            <a href="view/vistaUsuaris.vista.html">Vista Usuaris</a>
                        <?php endif; ?>
                        <a href="controllers/logout.controller.php">Tancar Sessió</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="view/login.vista.php" class="btn">Logar-se</a>
                <a href="view/Register.vista.php" class="btn">Registrar-se</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <div class="messages">
            <?php if ($success_message): ?>
                <div class="success" style="color: green;"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="error" style="color: red;"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </div>
        <!-- Formulario para seleccionar el número de artículos por página -->
        <form id="articulos-form" method="GET" action="index.php">
            <label for="articulos_por_pagina">Articles per pàgina:</label>
            <select name="articulos_por_pagina" id="articulos_por_pagina" onchange="document.getElementById('articulos-form').submit();">
                <option value="5" <?php echo $articulos_por_pagina == 5 ? 'selected' : ''; ?>>5</option>
                <option value="10" <?php echo $articulos_por_pagina == 10 ? 'selected' : ''; ?>>10</option>
                <option value="15" <?php echo $articulos_por_pagina == 15 ? 'selected' : ''; ?>>15</option>
                <option value="20" <?php echo $articulos_por_pagina == 20 ? 'selected' : ''; ?>>20</option>
            </select>
        </form>
        <!-- Contenido principal -->
        <?php mostrarAnimales($articulos_por_pagina); ?>
    </main>
    <script>
        document.getElementById('profile-icon').addEventListener('click', () => {
            document.getElementById('dropdown-menu').classList.toggle('show');
        });
    </script>
</body>
</html>