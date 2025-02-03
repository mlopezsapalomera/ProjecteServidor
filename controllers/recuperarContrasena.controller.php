<?php
session_start();
require '../vendor/autoload.php';
require_once '../model/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $result = getUserByEmail($email);

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', time() + 3600);

        insertPasswordReset($email, $token, $expiry);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'm.lopez5@sapalomera.cat';
            $mail->Password = 'xwos uafv ixrl ismx';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('pokedexGlobal@example.com', 'Pokedex Global');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperar Contraseña';
            $mail->Body    = "Feu clic al següent enllaç per restablir la contrasenya: <a href='http://marcoslopez.cat/view/restablecerContrasena.vista.php?token=$token'>Restablecer Contraseña</a>";

            $mail->send();
            $_SESSION['success_message'] = "S'ha enviat un correu electrònic amb les instruccions per restablir la contrasenya.";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Error en enviar el correu electrònic: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['error_message'] = "El correu electrònic no està enregistrat.";
    }

    header("Location: ../view/recuperarContrasena.vista.php");
    exit();
}
?>