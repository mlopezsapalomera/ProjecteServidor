<?php
session_start();
require_once '../model/db.php';
require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';
require_once '../PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Verificar si el correo electrónico está registrado
    $query = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generar un token único
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', time() + 3600); // 1 hora de validez

        // Guardar el token en la base de datos
        $query = "INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $email, $token, $expiry);
        $stmt->execute();

        // Enviar el correo electrónico
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Configura tu servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'm.lopez5@sapalomera.cat'; // Configura tu correo electrónico
            $mail->Password = 'xwos uafv ixrl ismx'; // Configura tu contraseña
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('pokedexGlobal@example.com', 'Pokedex Global');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperar Contraseña';
            $mail->Body    = "Feu clic al següent enllaç per restablir la contrasenya: <a href='http://localhost/ProjecteServidor/view/restablecerContrasena.vista.php?token=$token'>Restablecer Contraseña</a>";

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