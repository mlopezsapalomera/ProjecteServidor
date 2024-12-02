<?php
session_start(); // Inicia la sessió
require_once '../model/db.php'; // Connexió a la base de dades

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    $remember_me = isset($_POST['remember_me']);

    // Verificar intents fallits
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    if ($_SESSION['login_attempts'] >= 3) {
        if (!isCaptchaValid()) {
            $_SESSION['error_message'] = $errors['captcha'];
            header("Location: ../view/login.vista.php");
            exit();
        }
    }

    // Preparar la consulta per obtenir l'usuari
    $stmt = $conn->prepare("SELECT id, password, nom, email, rol, imagen FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Comprovar si l'usuari existeix
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password_hash, $nombre, $email, $rol, $imagen);
        $stmt->fetch();

        // Verificar la contrasenya
        if (password_verify($contraseña, $password_hash)) {
            // Inici de sessió exitós
            $_SESSION['usuario_id'] = $id; // Desa l'ID de l'usuari a la sessió
            $_SESSION['nombre'] = $nombre; // Desa el nom de l'usuari a la sessió
            $_SESSION['email'] = $email; // Desa el correu electrònic de l'usuari a la sessió
            $_SESSION['rol'] = $rol; // Desa el rol de l'usuari a la sessió
            $_SESSION['usuario'] = $email; // Desa l'email de l'usuari a la sessió
            $_SESSION['imagen'] = $imagen; // Desa la imatge de l'usuari a la sessió
            $_SESSION['success_message'] = "Inici de sessió exitós!";
            $_SESSION['login_attempts'] = 0; // Reiniciar intents fallits

            if ($remember_me) {
                // Guardar el email y la contraseña en cookies
                setcookie('remember_me_email', $email, time() + (86400 * 30), "/"); // 30 días
                setcookie('remember_me_password', $contraseña, time() + (86400 * 30), "/"); // 30 días
            } else {
                // Eliminar las cookies si no se selecciona "Remember Me"
                setcookie('remember_me_email', '', time() - 3600, "/");
                setcookie('remember_me_password', '', time() - 3600, "/");
            }

            header("Location: ../index.php");
            exit();
        } else {
            // Contrasenya incorrecta
            $_SESSION['error_message'] = "Contrasenya incorrecta.";
            $_SESSION['login_email'] = $email; // Mantener el email en la sesión
            $_SESSION['login_attempts'] += 1; // Incrementar intents fallits
            header("Location: ../view/login.vista.php");
            exit();
        }
    } else {
        // Usuari no trobat
        $_SESSION['error_message'] = "Usuari no trobat.";
        $_SESSION['login_attempts'] += 1; // Incrementar intents fallits
        header("Location: ../view/login.vista.php"); 
        exit();
    }

    // Tancar part de login
    if ($stmt) {
        $stmt->close();
    }
}

/**
 * Comprova si el captcha ha sigut completat i completa l'array global d'errors si n'hi ha
 *
 * @return boolean si el captcha és vàlid o no
 */
function isCaptchaValid()
{
    global $errors;

    // Verificar la resposta de l'API reCAPTCHA 
    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6LeeSJAqAAAAAIqqhX9WLuT7o27_UvsyBAELkQMN&response=' . $_POST['g-recaptcha-response']);

        // Decodificar JSON data de la resposta de l'API
        $responseData = json_decode($verifyResponse);

        if (!$responseData->success) {
            $errors['captcha'] = "Wrong captcha";
            return false;
        }
    } else {
        $errors['captcha'] = "You must check the captcha.";
        return false;
    }

    return true;
}
?>