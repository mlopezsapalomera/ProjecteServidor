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

function getUsersExcludingAdmin() {
    global $conn;
    $query = "SELECT id, nom, email, rol FROM usuarios WHERE rol != 'admin'";
    return $conn->query($query);
}

function deleteUserById($id) {
    global $conn;
    $query = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function getPokemonsImagesByUserId($id) {
    global $conn;
    $query = "SELECT imatge FROM pokemons WHERE usuario_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result();
}

function deletePokemonsByUserId($id) {
    global $conn;
    $query = "DELETE FROM pokemons WHERE usuario_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function deleteToken($token) {
    global $conn;
    $query = "DELETE FROM user_tokens WHERE token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    return $stmt->execute();
}

function insertPokemon($nom, $força, $dany, $vida, $tipus, $imagen_nombre, $usuario_id) {
    global $conn;
    $query = "INSERT INTO pokemons (nom, força, dany, vida, tipus, imatge, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("siiissi", $nom, $força, $dany, $vida, $tipus, $imagen_nombre, $usuario_id);
    return $stmt->execute();
}

function insertFriend($usuario_id, $friend_id) {
    global $conn;
    $query = "INSERT INTO friends (user_id, friend_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $usuario_id, $friend_id);
    return $stmt->execute();
}

function getFriendsByUserId($usuario_id) {
    global $conn;
    $query = "SELECT u.id, u.nom, u.imagen FROM friends f JOIN usuarios u ON f.friend_id = u.id WHERE f.user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    return $stmt->get_result();
}

function insertUser($nombre, $email, $contraseña_hash, $imagen) {
    global $conn;
    $query = "INSERT INTO usuarios (nom, email, password, imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nombre, $email, $contraseña_hash, $imagen);
    return $stmt->execute();
}

function getUserByEmail($email) {
    global $conn;
    $query = "SELECT id, password, nom, email, rol, imagen FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    return $stmt->get_result();
}

function insertToken($id, $token, $expiry) {
    global $conn;
    $query = "INSERT INTO user_tokens (user_id, token, expiry) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $id, $token, $expiry);
    return $stmt->execute();
}

function getPasswordByUserId($usuario_id) {
    global $conn;
    $query = "SELECT password FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updatePassword($new_password_hash, $usuario_id) {
    global $conn;
    $query = "UPDATE usuarios SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_password_hash, $usuario_id);
    return $stmt->execute();
}

function insertPasswordReset($email, $token, $expiry) {
    global $conn;
    $query = "INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $token, $expiry);
    return $stmt->execute();
}

function getEmailByToken($token) {
    global $conn;
    $query = "SELECT email FROM password_resets WHERE token = ? AND expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    return $stmt->get_result();
}

function deletePasswordReset($email) {
    global $conn;
    $query = "DELETE FROM password_resets WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    return $stmt->execute();
}

function getPokemonsByUserIdWithPagination($usuario_id, $pokemons_por_pagina, $offset, $orden) {
    global $conn;
    $query = "SELECT * FROM pokemons WHERE usuario_id = ? ORDER BY nom $orden LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $usuario_id, $pokemons_por_pagina, $offset);
    $stmt->execute();
    return $stmt->get_result();
}

function deletePokemonById($id) {
    global $conn;
    $query = "DELETE FROM pokemons WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function updatePokemon($nombre, $cuerpo, $imagen_nombre, $id) {
    global $conn;
    if ($imagen_nombre) {
        $query = "UPDATE pokemons SET nom = ?, descripció = ?, imatge = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $nombre, $cuerpo, $imagen_nombre, $id);
    } else {
        $query = "UPDATE pokemons SET nom = ?, descripció = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nombre, $cuerpo, $id);
    }
    return $stmt->execute();
}

function togglePokemonVisibility($id, $visible) {
    global $conn;
    $query = "UPDATE pokemons SET visible = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $visible, $id);
    return $stmt->execute();
}
?>