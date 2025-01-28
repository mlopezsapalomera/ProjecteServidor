<?php
require_once __DIR__ . '/../env.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");

function getUserById($id) {
    global $conn;
    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateUser($id, $nombre, $imagen = null) {
    global $conn;
    if ($imagen) {
        $query = "UPDATE usuarios SET nom = ?, imagen = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nombre, $imagen, $id);
    } else {
        $query = "UPDATE usuarios SET nom = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $nombre, $id);
    }
    return $stmt->execute();
}

function getPokemonsByUserId($usuario_id, $orden, $inicio, $pokemons_por_pagina) {
    global $conn;
    $query = "SELECT * FROM pokemons WHERE usuario_id = ? ORDER BY nom $orden LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $usuario_id, $inicio, $pokemons_por_pagina);
    $stmt->execute();
    return $stmt->get_result();
}

function countPokemonsByUserId($usuario_id) {
    global $conn;
    $query = "SELECT COUNT(*) AS total FROM pokemons WHERE usuario_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}
?>