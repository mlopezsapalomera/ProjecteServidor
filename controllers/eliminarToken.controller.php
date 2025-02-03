<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("HTTP/1.1 403 Forbidden");
    exit();
}
require_once '../model/db.php';

if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    deleteToken($token);
    setcookie('remember_me', '', time() - 3600, '/');
}
?>