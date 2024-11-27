<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió
require_once '../model/db.php'; // Connexió a la base de dades

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenir dades del formulari
    $id = $_POST['id']; // ID del Pokémon
    $nombre = trim($_POST['nombre']);
    $cuerpo = trim($_POST['cuerpo']);

    // Validar que los campos no estén vacíos después de eliminar los espacios en blanco
    if (empty($nombre) || empty($cuerpo)) {
        $_SESSION['error_message'] = "El nombre y la descripción no pueden estar vacíos.";
        header("Location: ../view/modificar.vista.php?id=$id&nombre=$nombre&cuerpo=$cuerpo");
        exit();
    }

    // Manejar la subida de la imagen
    $imagen = $_FILES['imagen'];
    $imagen_nombre = basename($imagen['name']);
    $imagen_ruta = '../img/' . $imagen_nombre;

    if (!empty($imagen['tmp_name'])) {
        // Mover la imagen subida a la carpeta de destino
        if (move_uploaded_file($imagen['tmp_name'], $imagen_ruta)) {
            // Actualizar Pokémon con nueva imagen
            $query = "UPDATE pokemons SET nom = ?, descripció = ?, imatge = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $nombre, $cuerpo, $imagen_nombre, $id);
        } else {
            $_SESSION['error_message'] = "Error en subir la imagen.";
            header("Location: ../view/modificar.vista.php?id=$id&nombre=$nombre&cuerpo=$cuerpo");
            exit();
        }
    } else {
        // Actualizar Pokémon sin cambiar la imagen
        $query = "UPDATE pokemons SET nom = ?, descripció = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nombre, $cuerpo, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Pokémon modificado correctamente.";
    } else {
        $_SESSION['error_message'] = "Error en modificar el Pokémon: " . $conn->error;
    }

    $stmt->close();
    
    // Redirigir a l'índex
    header("Location: ../index.php");
    exit();
}

$conn->close();
?>