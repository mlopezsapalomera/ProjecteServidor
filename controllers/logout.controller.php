<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió
session_unset(); // Despeja totes les variables de sessió
session_destroy(); // Destrueix la sessió

// Mantener la cookie de "Remember me"
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', $_COOKIE['remember_me'], time() + 60, "/");
    setcookie('remember_me_email', $_COOKIE['remember_me_email'], time() + 60, "/");
}

header('Location: ../index.php'); // Redirigeix l'usuari de tornada a la pàgina principal
exit();
?>