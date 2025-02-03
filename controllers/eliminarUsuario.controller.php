<?php
//Marcos Lopez Medina

session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    echo "Accés denegat.";
    exit();
}


if (!isset($_GET['id'])) {
    echo "No s'ha especificat l'ID de l'usuari.";
    exit();
}

require_once '../model/db.php';

$id = $_GET['id'];

$result = getPokemonsImagesByUserId($id);

$imagenes = [];
while ($row = $result->fetch_assoc()) {
    $imagenes[] = $row['imatge'];
}

// Eliminar las imágenes del servidor
foreach ($imagenes as $imagen) {
    $ruta_imagen = "../img/" . $imagen;
    if (file_exists($ruta_imagen)) {
        unlink($ruta_imagen);
    }
}

// Eliminar los pokemons asociados al usuario
deletePokemonsByUserId($id);

// Eliminar el usuario
if (deleteUserById($id)) {
    echo "Usuari eliminat correctament.";
} else {
    echo "Error en eliminar l'usuari: " . $conn->error;
}
?>