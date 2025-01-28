<?php
session_start();
require_once '../model/db.php';

if (!isset($_GET['id'])) {
    echo "No s'ha especificat l'ID de l'usuari.";
    exit();
}

$usuario_id = $_GET['id'];
$pokemons_por_pagina = isset($_GET['pokemons_por_pagina']) ? (int)$_GET['pokemons_por_pagina'] : 5;
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'asc';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $pokemons_por_pagina;

$query = "SELECT * FROM pokemons WHERE usuario_id = ? ORDER BY nom $orden LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $usuario_id, $pokemons_por_pagina, $offset);
$stmt->execute();
$result = $stmt->get_result();

$html = '<div class="pokemons-container">';
while ($pokemon = $result->fetch_assoc()) {
    $imagen = !empty($pokemon['imatge']) ? $pokemon['imatge'] : 'default.jpg';
    $descripcion = !empty($pokemon['descripció']) ? $pokemon['descripció'] : 'No description available.';
    $html .= '<div class="pokemon-card">';
    $html .= '<img src="../img/' . htmlspecialchars($imagen) . '" alt="' . htmlspecialchars($pokemon['nom']) . '">';
    $html .= '<div class="pokemon-info">';
    $html .= '<h3>' . htmlspecialchars($pokemon['nom']) . '</h3>';
    $html .= '<p>' . htmlspecialchars($descripcion) . '</p>';
    $html .= '</div>';
    $html .= '</div>';
}
$html .= '</div>';

echo $html;

$stmt->close();
$conn->close();
?>