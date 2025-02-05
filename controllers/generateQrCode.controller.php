<?php
require '../vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};
require_once '../model/db.php';

session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$perfil_url = "http://marcoslopez.cat/view/perfilUsuario.vista.php?id=$usuario_id";

$options = new QROptions([
    'version'    => 5,
    'eccLevel'   => QRCode::ECC_L,
    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
    'scale'      => 5,
]);

$qrcode = new QRCode($options);
$qrCodePath = __DIR__ . "/../img/qr_$usuario_id.png";

try {
    $qrcode->render($perfil_url, $qrCodePath);

    // Guardar la URL del código QR en la base de datos
    $qrCodeUrl = "../img/qr_$usuario_id.png";
    $query = "UPDATE usuarios SET qr_code_url = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $qrCodeUrl, $usuario_id);
    $stmt->execute();

    echo json_encode(['success' => true, 'qrCodeUrl' => $qrCodeUrl]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al generar el código QR: ' . $e->getMessage()]);
}
?>