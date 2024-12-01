<?php
session_start();
require_once '../model/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Validar que las contraseñas no estén vacías y que coincidan
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

    // Validar la nueva contraseña (mínimo una mayúscula y un número)
    if (!preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
        $_SESSION['error_message'] = "La nueva contraseña debe contener al menos una mayúscula y un número.";
        header("Location: ../view/cambiarContrasena.vista.php");
        exit();
    }

    // Verificar la contraseña actual
    $query = "SELECT password FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($password_hash);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_password, $password_hash)) {
        $_SESSION['error_message'] = "La contraseña actual es incorrecta.";
        header("Location: ../view/cambiarContrasena.vista.php");
        exit();
    }

    // Verificar que la nueva contraseña no sea igual a la anterior
    if (password_verify($new_password, $password_hash)) {
        $_SESSION['error_message'] = "La nueva contraseña no puede ser igual a la anterior.";
        header("Location: ../view/cambiarContrasena.vista.php");
        exit();
    }

    // Hashear la nueva contraseña
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Actualizar la contraseña en la base de datos
    $query = "UPDATE usuarios SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_password_hash, $usuario_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Contraseña cambiada correctamente.";
    } else {
        $_SESSION['error_message'] = "Error al cambiar la contraseña: " . $conn->error;
    }

    $stmt->close();
    header("Location: ../view/cambiarContrasena.vista.php");
    exit();
}
?>