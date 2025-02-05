<?php
//Marcos Lopez Medina

session_start();
if (!isset($_SESSION['usuario'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
} // Inicia la sessió
require_once '../model/db.php'; // Connexió a la base de dades

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenir dades del formulari
    $id = $_POST['id']; // ID del Pokémon

    // Manejar la subida de la imagen
    $imagen = $_FILES['imagen'];
    $imagen_nombre = basename($imagen['name']);
    $imagen_ruta = '../img/' . $imagen_nombre;

    if (!empty($imagen['tmp_name'])) {
        // Mover la imagen subida a la carpeta de destino
        if (move_uploaded_file($imagen['tmp_name'], $imagen_ruta)) {
            // Actualizar Pokémon con nueva imagen
            $query = "UPDATE pokemons SET imatge = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $imagen_nombre, $id);
        } else {
            $_SESSION['error_message'] = "Error en subir la imagen.";
            header("Location: ../view/modificar.vista.php?id=$id");
            exit();
        }
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Pokémon modificado correctamente.";
    } else {
        $_SESSION['error_message'] = "Error en modificar el Pokémon: " . $conn->error;
    }

    $stmt->close();
    
    // Redirigir a miPerfil.vista.php
    header("Location: ../view/miPerfil.vista.php");
    exit();
}

$conn->close();
?>