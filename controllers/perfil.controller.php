<?php
session_start();
require_once '../model/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $usuario_id = $_SESSION['usuario_id'];

    // Validar que el nombre no esté vacío después de eliminar los espacios en blanco
    if (empty($nombre)) {
        $_SESSION['error_message'] = "El nombre no puede estar vacío.";
        header("Location: ../view/perfil.vista.php");
        exit();
    }

    // Verificar si el nombre de usuario ya existe
    $query = "SELECT * FROM usuarios WHERE nom = ? AND id != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $nombre, $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El nombre de usuario ya existe
        $_SESSION['error_message'] = "El nombre de usuario ya existe.";
        $stmt->close();
        header("Location: ../view/perfil.vista.php");
        exit();
    }

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