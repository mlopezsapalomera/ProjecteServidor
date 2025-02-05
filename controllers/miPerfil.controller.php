<?php
session_start();
require_once '../model/db.php';
require_once '../articles.php';

if (!isset($_SESSION['usuario'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error_message'] = "ID de usuario no definido.";
    header("Location: ../index.php");
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
            $_SESSION['success_message'] = "Perfil actualizado correctamente.";
            $_SESSION['nombre'] = $nombre;
        } else {
            throw new Exception("Error en actualizar el perfil: " . $conn->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }

    header("Location: ../view/miPerfil.vista.php");
    exit();
} else {
    try {
        $usuario_id = $_SESSION['usuario_id'];
        $pokemons_por_pagina = isset($_GET['pokemons_por_pagina']) ? (int)$_GET['pokemons_por_pagina'] : 5;
        $orden = isset($_GET['orden']) ? $_GET['orden'] : 'asc';
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $offset = ($pagina - 1) * $pokemons_por_pagina;

        $query = "SELECT * FROM pokemons WHERE usuario_id = ? AND visible = TRUE ORDER BY nom $orden LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $usuario_id, $pokemons_por_pagina, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $html = '<div class="pokemons-container">';
        while ($pokemon = $result->fetch_assoc()) {
            $html .= '<div class="pokemon-card">';
            $html .= '<img src="../img/' . htmlspecialchars($pokemon['imatge']) . '" alt="' . htmlspecialchars($pokemon['nom']) . '">';
            $html .= '<div class="pokemon-info">';
            $html .= '<h3>' . htmlspecialchars($pokemon['nom']) . '</h3>';
            $html .= '<p>Força: ' . htmlspecialchars($pokemon['força']) . '</p>';
            $html .= '<p>Vida: ' . htmlspecialchars($pokemon['vida']) . '</p>';
            $html .= '<p>Daño: ' . htmlspecialchars($pokemon['dany']) . '</p>';
            $html .= '<p>Tipus: ' . htmlspecialchars($pokemon['tipus']) . '</p>';
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';

        echo $html;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>