<?php
session_start();
require_once '../model/db.php';
require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';
require_once '../PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Validar que las contraseñas no estén vacías y que coincidan
    if (empty($new_password) || empty($confirm_new_password)) {
        $_SESSION['error_message'] = "Todos los campos son obligatorios.";
        header("Location: ../view/restablecerContrasena.vista.php?token=$token");
        exit();
    }

    if ($new_password !== $confirm_new_password) {
        $_SESSION['error_message'] = "Las nuevas contraseñas no coinciden.";
        header("Location: ../view/restablecerContrasena.vista.php?token=$token");
        exit();
    }

    // Validar la nueva contraseña (mínimo una mayúscula y un número)
    if (!preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
        $_SESSION['error_message'] = "La nueva contraseña debe contener al menos una mayúscula y un número.";
        header("Location: ../view/restablecerContrasena.vista.php?token=$token");
        exit();
    }

    // Verificar el token
    $query = "SELECT email FROM password_resets WHERE token = ? AND expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $email = $result->fetch_assoc()['email'];

        // Hashear la nueva contraseña
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $query = "UPDATE usuarios SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $new_password_hash, $email);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Contrasenya restablida correctament.";
            // Eliminar el token
            $query = "DELETE FROM password_resets WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
        } else {
            $_SESSION['error_message'] = "Error en restablir la contrasenya: " . $conn->error;
        }
    } else {
        $_SESSION['error_message'] = "El token és invàlid o ha expirat.";
    }

    header("Location: ../view/restablecerContrasena.vista.php?token=$token");
    exit();
}
?>