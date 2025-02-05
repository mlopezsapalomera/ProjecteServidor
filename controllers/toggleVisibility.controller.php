<?php
require_once '../model/db.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$pokemon_id = $_GET['id'];
$visible = $_GET['visible'];

$query = "UPDATE pokemons SET visible = ? WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $visible, $pokemon_id, $_SESSION['usuario_id']);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la visibilidad']);
}
?>