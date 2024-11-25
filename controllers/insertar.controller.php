<?php
require_once '../model/conexio.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $descripcio = $_POST['descripcio'];
    $usuario_id = 1; // Por defecto el admin

    // Procesar imagen
    $imagen = $_FILES['imatge'];
    $imagen_nombre = time() . '_' . $imagen['name'];
    $ruta_destino = "../img/" . $imagen_nombre;

    if (move_uploaded_file($imagen['tmp_name'], $ruta_destino)) {
        try {
            $stmt = $db->prepare("INSERT INTO animales (nom, descripció, imatge, usuario_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom, $descripcio, $imagen_nombre, $usuario_id]);
            header("Location: ../index.php");
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>