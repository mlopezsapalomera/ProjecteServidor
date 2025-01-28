<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

require_once '../model/db.php';

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friend_id = $_POST['friend_id'];

    $query = "INSERT INTO friends (user_id, friend_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $usuario_id, $friend_id);

    if ($stmt->execute()) {
        echo "Amigo añadido correctamente.";
    } else {
        echo "Error al añadir amigo: " . $conn->error;
    }

    $stmt->close();
    exit();
}

$query = "SELECT u.id, u.nom, u.imagen FROM friends f JOIN usuarios u ON f.friend_id = u.id WHERE f.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$html = '<div class="friends-grid">';
while ($row = $result->fetch_assoc()) {
    $html .= '<div class="friend-card">';
    $html .= '<img src="../userProfile/img/' . htmlspecialchars($row['imagen']) . '" alt="Friend Image">';
    $html .= '<p>' . htmlspecialchars($row['nom']) . '</p>';
    $html .= '</div>';
}
$html .= '</div>';

echo $html;
?>