<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió
require_once '../model/db.php'; // Connexió a la base de dades

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenir dades del formulari
    $id = $_POST['id']; // ID de l'article
    $nombre = $_POST['nombre'];
    $cuerpo = $_POST['cuerpo'];

    // Manejar la subida de la imagen
    $imagen = $_FILES['imagen'];
    $imagen_nombre = basename($imagen['name']);
    $imagen_ruta = '../img/' . $imagen_nombre;

    if (!empty($imagen['tmp_name'])) {
        // Mover la imagen subida a la carpeta de destino
        if (move_uploaded_file($imagen['tmp_name'], $imagen_ruta)) {
            // Actualizar artículo con nueva imagen
            $query = "UPDATE animales SET nom = ?, descripció = ?, imatge = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $nombre, $cuerpo, $imagen_nombre, $id);
        } else {
            $_SESSION['error_message'] = "Error en subir la imagen.";
            header("Location: ../index.php");
            exit();
        }
    } else {
        // Actualizar artículo sin cambiar la imagen
        $query = "UPDATE animales SET nom = ?, descripció = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nombre, $cuerpo, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Article modificat correctament.";
    } else {
        $_SESSION['error_message'] = "Error en modificar l'article: " . $conn->error;
    }

    $stmt->close();
    
    // Redirigir a l'índex
    header("Location: ../index.php");
    exit();
}

$conn->close();
?>