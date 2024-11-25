<?php
require_once '/model/conexio.php';
require_once 'mostraranimales.php';

$db = connexio(); // Llamada a la función de conexión
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestió d'Animals</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="views/insertar.vista.html" class="btn">Afegir Animal</a>
    </nav>
    
    <div class="animals-container">
        <?php mostrarAnimales($db); ?>
    </div>
</body>
</html>