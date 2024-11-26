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