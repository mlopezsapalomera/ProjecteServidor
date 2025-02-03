<?php
//Marcos Lopez Medina

session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../model/db.php';

$result = getUsersExcludingAdmin();

$html = '<table>';
$html .= '<tr><th>ID</th><th>NickName</th><th>Email</th><th>Accions</th></tr>';
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['nom']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
    $html .= '<td class="table-actions"><button class="btn btn-danger delete-user" data-user-id="' . $row['id'] . '">Eliminar</button></td>';
    $html .= '</tr>';
}
$html .= '</table>';

echo $html;
?>