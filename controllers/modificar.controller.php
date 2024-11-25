<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $descripcio = $_POST['descripcio'];

    try {
        if (!empty($_FILES['imatge']['name'])) {
            $imagen = $_FILES['imatge'];
            $imagen_nombre = time() . '_' . $imagen['name'];
            $ruta_destino = "../img/" . $imagen_nombre;

            if (move_uploaded_file($imagen['tmp_name'], $ruta_destino)) {
                $stmt = $db->prepare("UPDATE animales SET nom = ?, descripció = ?, imatge = ? WHERE id = ?");
                $stmt->execute([$nom, $descripcio, $imagen_nombre, $id]);
            }
        } else {
            $stmt = $db->prepare("UPDATE animales SET nom = ?, descripció = ? WHERE id = ?");
            $stmt->execute([$nom, $descripcio, $id]);
        }
        header("Location: ../index.php");
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>