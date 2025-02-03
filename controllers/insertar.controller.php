<?php
//Marcos Lopez Medina

session_start();
if (!isset($_SESSION['usuario'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
} // Inicia la sessió
require_once '../model/db.php'; // Connexió a la base de dades

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['pokemon-dropdown']);
    $força = (int)$_POST['força'];
    $dany = (int)$_POST['dany'];
    $vida = (int)$_POST['vida'];
    $tipus = trim($_POST['tipus']);
    $usuario_id = $_SESSION['usuario_id']; // Asegúrate de que el ID del usuario esté almacenado en la sesión

    // Manejar la subida de la imagen
    $imagen = $_FILES['imagen'];
    $imagen_nombre = basename($imagen['name']);
    $imagen_ruta = '../img/' . $imagen_nombre;

    // Mover la imagen subida a la carpeta de destino
    if (move_uploaded_file($imagen['tmp_name'], $imagen_ruta)) {
        // Preparar y ejecutar la consulta de inserción
        if (insertPokemon($nom, $força, $dany, $vida, $tipus, $imagen_nombre, $usuario_id)) {
            $_SESSION['success_message'] = "Pokemon insertat correctament.";
        } else {
            $_SESSION['error_message'] = "Error en insertar el Pokemon: " . $conn->error;
        }
    } else {
        $_SESSION['error_message'] = "Error en subir la imagen.";
    }
    
    // Redirigir a l'índex
    header("Location: ../index.php");
    exit();
}
?>