<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar-se</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>
    <header>
        <h1>Registrar-se</h1>
    </header>
    <main id="main-content">
        <form action="../controllers/register.controller.php" method="POST">
            <label for="nombre">Nom:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="contraseña">Contrasenya:</label>
            <input type="password" id="contraseña" name="contraseña" required>
            <label for="confirmar_contraseña">Confirmar Contrasenya:</label>
            <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" required>
            <button type="submit">Registrar-se</button>
        </form>
    </main>
</body>
</html>