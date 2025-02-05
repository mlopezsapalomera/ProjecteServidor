<?php
session_start();
require_once '../model/db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo "Usuario no autenticado.";
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$query = "SELECT id, nom, descripció, imatge, visible, força, vida, dany, tipus FROM pokemons WHERE usuario_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$html = '<div class="pokemons-container">';
while ($row = $result->fetch_assoc()) {
    $html .= '<div class="pokemon-card">';
    $html .= '<img src="../img/' . htmlspecialchars($row['imatge']) . '" alt="' . htmlspecialchars($row['nom']) . '">';
    $html .= '<div class="pokemon-info">';
    $html .= '<h3>' . htmlspecialchars($row['nom']) . '</h3>';
    $html .= '<p>Força: ' . htmlspecialchars($row['força']) . '</p>';
    $html .= '<p>Vida: ' . htmlspecialchars($row['vida']) . '</p>';
    $html .= '<p>Daño: ' . htmlspecialchars($row['dany']) . '</p>';
    $html .= '<p>Tipus: ' . htmlspecialchars($row['tipus']) . '</p>';
    $html .= '<button class="toggle-visibility" data-id="' . htmlspecialchars($row['id']) . '" data-visible="' . ($row['visible'] ? '1' : '0') . '">';
    $html .= $row['visible'] ? 'Marcar como invisible' : 'Marcar como visible';
    $html .= '</button>';
    $html .= '</div>';
    $html .= '</div>';
}
$html .= '</div>';

echo $html;
?>