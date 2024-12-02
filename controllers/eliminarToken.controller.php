<?php
session_start();
require_once '../model/db.php';

if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];

    // Eliminar el token de la base de datos
    $stmt = $conn->prepare("DELETE FROM user_tokens WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->close();

    // Eliminar la cookie
    setcookie('remember_me', '', time() - 3600, '/');
}
?>