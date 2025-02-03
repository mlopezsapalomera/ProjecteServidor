<?php
require '../vendor/autoload.php';
use Zxing\QrReader;
require_once '../model/db.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['qr_image'])) {
        $qrImagePath = $_FILES['qr_image']['tmp_name'];
        $qrReader = new QrReader($qrImagePath);
        $text = $qrReader->text();

        if ($text) {
            // Eliminar la URL del código QR de la base de datos
            $query = "UPDATE usuarios SET qr_code_url = NULL WHERE qr_code_url = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $text);
            $stmt->execute();

            echo json_encode(['success' => true, 'url' => $text]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo leer el código QR']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No se recibió ninguna imagen']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>