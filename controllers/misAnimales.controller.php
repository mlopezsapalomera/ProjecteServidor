<?php
//Marcos Lopez Medina

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error_message'] = "ID de usuario no definido.";
    header("Location: ../index.php");
    exit();
}

require_once '../model/db.php';
require_once '../articles.php';

$usuario_id = $_SESSION['usuario_id']; // Asegúrate de que el ID del usuario esté almacenado en la sesión
echo mostrarMisAnimales($usuario_id);
?>