<?php
session_start(); // Inicia la sessió
require_once '../model/db.php'; // Connexió a la base de dades

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    // Preparar la consulta per obtenir l'usuari
    $stmt = $conn->prepare("SELECT id, password, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Comprovar si l'usuari existeix
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password_hash, $rol);
        $stmt->fetch();

        // Verificar la contrasenya
        if (password_verify($contraseña, $password_hash)) {
            // Inici de sessió exitós
            $_SESSION['usuario'] = $email; // Desa l'email de l'usuari a la sessió
            $_SESSION['usuario_id'] = $id; // Desa l'ID de l'usuari a la sessió
            $_SESSION['rol'] = $rol; // Desa el rol de l'usuari a la sessió
            $_SESSION['success_message'] = "Inici de sessió exitós!";
            header("Location: ../index.php");
            exit();
        } else {
            // Contrasenya incorrecta
            $_SESSION['error_message'] = "Contrasenya incorrecta.";
            header("Location: ../index.php");
            exit();
        }
    } else {
        // Usuari no trobat
        $_SESSION['error_message'] = "Usuari no trobat.";
        header("Location: ../index.php"); 
        exit();
    }

    // Tancar part de login
    if ($stmt) {
        $stmt->close();
    }
}
?>