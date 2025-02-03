<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
}
require_once '../model/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios.";
        header("Location: ../view/cambiarContrasena.vista.php");
        exit();
    }

    if ($new_password !== $confirm_new_password) {
        $_SESSION['error_message'] = "Las nuevas contraseñas no coinciden.";
        header("Location: ../view/cambiarContrasena.vista.php");
        exit();
    }

    if (!preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
        $_SESSION['error_message'] = "La nueva contraseña debe contener al menos una mayúscula y un número.";
        header("Location: ../view/cambiarContrasena.vista.php");
        exit();
    }

    $password_hash = getPasswordByUserId($usuario_id)['password'];

    if (!password_verify($current_password, $password_hash)) {
        $_SESSION['error_message'] = "La contraseña actual es incorrecta.";
        header("Location: ../view/cambiarContrasena.vista.php");
        exit();
    }

    if (password_verify($new_password, $password_hash)) {
        $_SESSION['error_message'] = "La nueva contraseña no puede ser igual a la anterior.";
        header("Location: ../view/cambiarContrasena.vista.php");
        exit();
    }

    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    if (updatePassword($new_password_hash, $usuario_id)) {
        $_SESSION['success_message'] = "Contraseña cambiada correctamente.";
    } else {
        $_SESSION['error_message'] = "Error al cambiar la contraseña: " . $conn->error;
    }

    header("Location: ../view/cambiarContrasena.vista.php");
    exit();
}
?>