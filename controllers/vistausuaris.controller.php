<?php
//Marcos Lopez Medina

session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../model/db.php';

$query = "SELECT id, nom, email, rol FROM usuarios";
$result = $conn->query($query);

$html = '<table>';
$html .= '<tr><th>ID</th><th>Nom</th><th>Email</th><th>Rol</th><th>Accions</th></tr>';
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['nom']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['rol']) . '</td>';
    $html .= '<td class="table-actions"><button class="btn btn-danger delete-user" data-user-id="' . $row['id'] . '">Eliminar</button></td>';
    $html .= '</tr>';
}
$html .= '</table>';

echo $html;
?>