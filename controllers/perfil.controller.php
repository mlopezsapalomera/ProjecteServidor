<?php
session_start();
require_once '../model/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $usuario_id = $_SESSION['usuario_id'];

    // Manejar la subida de la imagen
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = $_FILES['imagen'];
        $imagen_nombre = basename($imagen['name']);
        $imagen_ruta = '../userProfile/img/' . $imagen_nombre;

        if (move_uploaded_file($imagen['tmp_name'], $imagen_ruta)) {
            $query = "UPDATE usuarios SET nom = ?, imagen = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $nombre, $imagen_nombre, $usuario_id);
            $_SESSION['imagen'] = $imagen_nombre; // Actualizar la imagen en la sesión
        } else {
            $_SESSION['error_message'] = "Error en subir la imagen.";
            header("Location: ../view/perfil.vista.php");
            exit();
        }
    } else {
        $query = "UPDATE usuarios SET nom = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $nombre, $usuario_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Perfil actualizado correctamente.";
        $_SESSION['nombre'] = $nombre; // Actualizar el nombre en la sesión
    } else {
        $_SESSION['error_message'] = "Error en actualizar el perfil: " . $conn->error;
    }

    $stmt->close();
    header("Location: ../view/perfil.vista.php");
    exit();
}
?>