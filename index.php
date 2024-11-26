<?php
// Marcos Lopez Medina
session_start(); // Inicia la sessió
require 'model/db.php'; // Connexió a la base de dades
require 'articles.php'; // Inclou la lògica per mostrar articles

// Comprova si l'usuari està connectat
$is_logged_in = isset($_SESSION['usuario']);

// Obtenir missatges de sessió
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Netejar missatges de sessió
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
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
        <h1>Llista d'Articles</h1>
        <div class="user-actions">
            <?php if ($is_logged_in): ?>
                <a href="controllers/logout.controller.php" class="btn">Tancar Sessió</a>
                <a href="view/misAnimales.vista.html" class="btn">Mis Animales</a>
                <a href="view/Inserir.vista.html" class="btn">Inserir Animal</a>
            <?php else: ?>
                <a href="view/login.vista.html" class="btn">Logar-se</a>
                <a href="view/Register.vista.html" class="btn">Registrar-se</a>
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

        <div class="articulos-list">
            <?php
                // Mostra la llista d'articles carregats des de la BD
                echo mostrarAnimales();
            ?>
        </div>
    </main>
</body>
</html>
