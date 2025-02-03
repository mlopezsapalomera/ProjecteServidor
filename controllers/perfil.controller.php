<?php
require '../vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
require_once '../model/db.php';

session_start();

if (!isset($_SESSION['usuario'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $usuario_id = $_SESSION['usuario_id'];

    try {
        if (empty($nombre)) {
            throw new Exception("El nombre no puede estar vacío.");
        }

        $query = "SELECT * FROM usuarios WHERE nom = ? AND id != ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $nombre, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("El nombre de usuario ya existe.");
        }

        if (!empty($_FILES['imagen']['name'])) {
            $imagen = $_FILES['imagen'];
            $imagen_nombre = basename($imagen['name']);
            $imagen_ruta = '../userProfile/img/' . $imagen_nombre;

            if (move_uploaded_file($imagen['tmp_name'], $imagen_ruta)) {
                $query = "UPDATE usuarios SET nom = ?, imagen = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $nombre, $imagen_nombre, $usuario_id);
                $_SESSION['imagen'] = $imagen_nombre;
            } else {
                throw new Exception("Error en subir la imagen.");
            }
        } else {
            $query = "UPDATE usuarios SET nom = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $nombre, $usuario_id);
        }

        if ($stmt->execute()) {
            $_SESSION['nombre'] = $nombre;

            // Generar nuevo código QR
            $perfil_url = "http://marcoslopez.cat/view/perfilUsuario.vista.php?id=$usuario_id";
            $qrCode = new QrCode($perfil_url);
            $writer = new PngWriter();
            $qrCodePath = __DIR__ . "/../img/qr_$usuario_id.png";
            $result = $writer->write($qrCode);
            $result->saveToFile($qrCodePath);

            echo json_encode(['success' => true, 'newImageUrl' => "../userProfile/img/$imagen_nombre"]);
        } else {
            throw new Exception("Error en actualizar el perfil: " . $conn->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>