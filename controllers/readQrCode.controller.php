<?php
require '../vendor/autoload.php';
use Zxing\QrReader;
require_once '../model/db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['qr_image'])) {
        $qrImagePath = $_FILES['qr_image']['tmp_name'];
        
        // Verificar si el archivo se ha cargado correctamente
        if (!file_exists($qrImagePath)) {
            die('Error: El archivo no se ha cargado correctamente.');
        }

        // Verificar el tipo de archivo
        $fileType = mime_content_type($qrImagePath);
        if (!in_array($fileType, ['image/png', 'image/jpeg', 'image/jpg'])) {
            die('Error: Tipo de archivo no soportado.');
        }

        // Leer el código QR
        $qrReader = new QrReader($qrImagePath);
        $text = $qrReader->text();

        if ($text) {
            // Si el QR tiene una URL válida, redirigir al usuario
            header("Location: " . $text);
            exit();
        } else {
            die('Error: No se pudo leer el código QR.');
        }
    } else {
        die('Error: No se recibió ninguna imagen.');
    }
} catch (Exception $e) {
    die('Error del servidor: ' . $e->getMessage());
}
?>
