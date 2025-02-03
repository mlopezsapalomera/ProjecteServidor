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

    if (insertFriend($usuario_id, $friend_id)) {
        echo "Amigo añadido correctamente.";
    } else {
        echo "Error al añadir amigo: " . $conn->error;
    }
    exit();
}

$result = getFriendsByUserId($usuario_id);

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