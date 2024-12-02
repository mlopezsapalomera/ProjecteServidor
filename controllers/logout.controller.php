<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió
session_unset(); // Despeja totes les variables de sessió
session_destroy(); // Destrueix la sessió

// Eliminar las cookies de "Remember me"
setcookie('remember_me_email', '', time() - 3600, "/");
setcookie('remember_me_password', '', time() - 3600, "/");

header('Location: ../index.php'); // Redirigeix l'usuari de tornada a la pàgina principal
exit();
?>