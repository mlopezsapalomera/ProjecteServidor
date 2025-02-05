<?php
require '../vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Decoder\QRCodeDecoderException;

session_start();

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['qr_image'])) {
        $qrImagePath = $_FILES['qr_image']['tmp_name'];
        
        // Verificar si el archivo se ha cargado correctamente
        if (!file_exists($qrImagePath)) {
            echo json_encode(['success' => false, 'message' => 'Error: El archivo no se ha cargado correctamente.']);
            exit();
        }

        // Verificar el tipo de archivo
        $fileType = mime_content_type($qrImagePath);
        if (!in_array($fileType, ['image/png', 'image/jpeg', 'image/jpg'])) {
            echo json_encode(['success' => false, 'message' => 'Error: Tipo de archivo no soportado.']);
            exit();
        }

        // Leer el código QR
        $options = new QROptions([
            'readerUseImagickIfAvailable' => true,
            'readerGrayscale'             => true,
            'readerIncreaseContrast'      => true,
        ]);

        $qrcode = new QRCode($options);

        try {
            $result = $qrcode->readFromBlob(file_get_contents($qrImagePath));
            $text = $result->data;

            echo json_encode(['success' => true, 'url' => $text]);
        } catch (QRCodeDecoderException $e) {
            echo json_encode(['success' => false, 'message' => 'Error al leer el código QR: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: No se recibió ninguna imagen.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>
