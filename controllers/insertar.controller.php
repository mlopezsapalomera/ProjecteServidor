<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió
require_once '../model/db.php'; // Connexió a la base de dades

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $cuerpo = trim($_POST['cuerpo']);
    $usuario_id = $_SESSION['usuario_id']; // Asegúrate de que el ID del usuario esté almacenado en la sesión

    // Validar que los campos no estén vacíos después de eliminar los espacios en blanco
    if (empty($nombre) || empty($cuerpo)) {
        $_SESSION['error_message'] = "El nombre y la descripción no pueden estar vacíos.";
        header("Location: ../view/Inserir.vista.php");
        exit();
    }

    // Manejar la subida de la imagen
    $imagen = $_FILES['imagen'];
    $imagen_nombre = basename($imagen['name']);
    $imagen_ruta = '../img/' . $imagen_nombre;

    // Mover la imagen subida a la carpeta de destino
    if (move_uploaded_file($imagen['tmp_name'], $imagen_ruta)) {
        // Preparar i executar la consulta d'inserció
        $query = "INSERT INTO animales (nom, descripció, imatge, usuario_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $nombre, $cuerpo, $imagen_nombre, $usuario_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Animal insertat correctament.";
        } else {
            $_SESSION['error_message'] = "Error en insertar l'animal: " . $conn->error;
        }
        
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Error en subir la imagen.";
    }
    
    // Redirigir a l'índex
    header("Location: ../index.php");
    exit();
}
?>