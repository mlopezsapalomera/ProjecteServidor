<?php
session_start(); // Inicia la sessió
require_once '../model/db.php'; // Connexió a la base de dades

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

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
            header("Location: ../index.php");
            exit();
        } else {
            // Contrasenya incorrecta
            $_SESSION['error_message'] = "Contrasenya incorrecta.";
            $_SESSION['login_email'] = $email; // Mantener el email en la sesión
            header("Location: ../view/login.vista.php");
            exit();
        }
    } else {
        // Usuari no trobat
        $_SESSION['error_message'] = "Usuari no trobat.";
        header("Location: ../view/login.vista.php"); 
        exit();
    }

    // Tancar part de login
    if ($stmt) {
        $stmt->close();
    }
}
?>