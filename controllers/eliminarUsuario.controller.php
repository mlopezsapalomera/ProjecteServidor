<?php
//Marcos Lopez Medina

session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    echo "Accés denegat.";
    exit();
}


if (!isset($_GET['id'])) {
    echo "No s'ha especificat l'ID de l'usuari.";
    exit();
}

require_once '../model/db.php';

$id = $_GET['id'];

// Obtener las rutas de las imágenes de los animales asociados
$query = "SELECT imatge FROM animales WHERE usuario_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$imagenes = [];
while ($row = $result->fetch_assoc()) {
    $imagenes[] = $row['imatge'];
}
$stmt->close();

// Eliminar las imágenes del servidor
foreach ($imagenes as $imagen) {
    $ruta_imagen = "../img/" . $imagen;
    if (file_exists($ruta_imagen)) {
        unlink($ruta_imagen);
    }
}

// Eliminar los animales asociados al usuario
$query = "DELETE FROM animales WHERE usuario_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Eliminar el usuario
$query = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo "Usuari eliminat correctament.";
} else {
    echo "Error en eliminar l'usuari: " . $conn->error;
}
$stmt->close();
?>