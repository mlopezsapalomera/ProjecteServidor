<?php
require_once 'config.php';
require_once 'mostraranimales.php';
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