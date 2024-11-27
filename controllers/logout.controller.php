<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió
session_unset(); // Despeja totes les variables de sessió
session_destroy(); // Destrueix la sessió

// Eliminar la cookie de "Remember me"
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/'); // Establir la cookie amb una data d'expiració passada
}

header('Location: ../index.php'); // Redirigeix l'usuari de tornada a la pàgina principal
exit();
?>