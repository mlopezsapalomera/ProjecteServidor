<?php
//Marcos Lopez Medina

session_start(); // Inicia la sessió

require_once '../model/db.php'; // Connexió a la base de dades

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];

    // Comprovar si les contrasenyes coincideixen
    if ($contraseña !== $confirmar_contraseña) {
        $_SESSION['error_message'] = "Les contrasenyes no coincideixen.";
        $_SESSION['nombre'] = $nombre; // Mantener los datos del formulario
        $_SESSION['email'] = $email; // Mantener los datos del formulario
        header("Location: ../view/Register.vista.php");
        exit();
    }

    // Validar la contrasenya (mínim un caràcter especial, un número, etc.)
    if (!preg_match('/[A-Z]/', $contraseña) || !preg_match('/[0-9]/', $contraseña)) {
        $_SESSION['error_message'] = "La contrasenya ha de contenir almenys una majúscula i un número.";
        $_SESSION['nombre'] = $nombre; // Mantener los datos del formulario
        $_SESSION['email'] = $email; // Mantener los datos del formulario
        header("Location: ../view/Register.vista.php");
        exit();
    }

    // Comprovar si l'usuari ja existeix
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // L'usuari ja existeix
        $_SESSION['error_message'] = "L'usuari ja existeix.";
        $_SESSION['nombre'] = $nombre; // Mantener los datos del formulario
        $_SESSION['email'] = $email; // Mantener los datos del formulario
        $stmt->close();
        header("Location: ../view/Register.vista.php");
        exit();
    }

    // Hashear la contrasenya
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Asignar la imagen por defecto
    $imagen = 'default.jpg';

    // Preparar i executar la consulta d'inserció
    $query = "INSERT INTO usuarios (nom, email, password, imagen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nombre, $email, $contraseña_hash, $imagen);

    if ($stmt->execute()) {
        // Iniciar sesión automáticamente
        $_SESSION['usuario_id'] = $stmt->insert_id; // Desa l'ID de l'usuari a la sessió
        $_SESSION['nombre'] = $nombre; // Desa el nom de l'usuari a la sessió
        $_SESSION['email'] = $email; // Desa el correu electrònic de l'usuari a la sessió
        $_SESSION['rol'] = 'user'; // Desa el rol de l'usuari a la sessió (asumiendo que el rol por defecto es 'user')
        $_SESSION['usuario'] = $email; // Desa l'email de l'usuari a la sessió
        $_SESSION['imagen'] = $imagen; // Desa la imatge de l'usuari a la sessió
        $_SESSION['success_message'] = "Usuari registrat correctament i sessió iniciada.";
    } else {
        $_SESSION['error_message'] = "Error en registrar-se: " . $conn->error;
    }

    $stmt->close();
    
    // Redirigir a l'índex
    header("Location: ../index.php");
    exit();
}
?>