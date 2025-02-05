<?php
session_start();
require_once '../model/db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$article_id = $data['id'];
$visible = $data['visible'];

if (togglePokemonVisibility($article_id, $visible)) {
    echo json_encode(['success' => true, 'visible' => $visible]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la visibilidad del artículo.']);
}
?>