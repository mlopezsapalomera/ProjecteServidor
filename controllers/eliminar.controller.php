<?php
require_once '../model/conexio.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    try {
        // Primero obtenemos el nombre de la imagen
        $stmt = $db->prepare("SELECT imatge FROM animales WHERE id = ?");
        $stmt->execute([$id]);
        $animal = $stmt->fetch();

        // Eliminamos el archivo de imagen
        if ($animal && !empty($animal['imatge'])) {
            $ruta_imagen = "../img/" . $animal['imatge'];
            if (file_exists($ruta_imagen)) {
                unlink($ruta_imagen);
            }
        }

        // Eliminamos el registro de la base de datos
        $stmt = $db->prepare("DELETE FROM animales WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: ../index.php");
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>