<?php
//Marcos Lopez Medina
require_once 'model/db.php'; 

function mostrarAnimales() {
    global $conn;

    // Nombre d'articles per pàgina
    $articulos_por_pagina = 5;

    // Obtenir el nombre total d'articles
    $consultaTotal = $conn->query("SELECT COUNT(*) AS total FROM animales");
    $total_articulos = $consultaTotal->fetch_assoc()['total'];

    // Calcular el nombre total de pàgines
    $total_paginas = ceil($total_articulos / $articulos_por_pagina);

    // Obtenir la pàgina actual des de la URL o establir 1 com a valor per defecte
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $pagina_actual = max(1, min($pagina_actual, $total_paginas)); 

    // Calcular l'índex del primer article de la pàgina actual
    $inicio = ($pagina_actual - 1) * $articulos_por_pagina;

    try {
        // Preparar la consulta per obtenir tots els articles de la pàgina actual
        $consultaArticulos = $conn->prepare("SELECT a.*, u.nom as usuario_nom 
                                             FROM animales a 
                                             JOIN usuarios u ON a.usuario_id = u.id 
                                             LIMIT ?, ?");
        $consultaArticulos->bind_param("ii", $inicio, $articulos_por_pagina);
        $consultaArticulos->execute();
        $resultados = $consultaArticulos->get_result();

        // Generar la llista d'articles
        $html = '<div class="animals-container">';
        while ($animal = $resultados->fetch_assoc()) {
            $html .= "<div class='animal-card'>";

            // Imagen con comprobación
            $imagen = !empty($animal['imatge']) ? $animal['imatge'] : 'default.jpg';
            $html .= "<img src='img/" . htmlspecialchars($imagen) . "' alt='" . htmlspecialchars($animal['nom']) . "'>";

            // Información del animal
            $html .= "<h3>" . htmlspecialchars($animal['nom']) . "</h3>";
            $html .= "<p>" . htmlspecialchars($animal['descripció']) . "</p>";
            $html .= "<p>Propietario: " . htmlspecialchars($animal['usuario_nom']) . "</p>";

            // Botones de acción con verificación de sesión
            if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $animal['usuario_id']) {
                $html .= "<div class='actions'>";
                $html .= "<a href='view/Modificar.vista.html?id=" . $animal['id'] . "' class='btn'>Modificar</a>";
                $html .= "<a href='view/Esborrar.vista.html?id=" . $animal['id'] . "&imagen=" . $animal['imatge'] . "' class='btn btn-danger'>Eliminar</a>";
                $html .= "</div>";
            }

            $html .= "</div>";
        }

        // Si no hay animales
        if ($resultados->num_rows == 0) {
            $html .= "<p>No hi ha animals disponibles.</p>";
        }

        $html .= '</div>';

        // Generar els enllaços de paginació
        $html .= '<div class="pagination">';
        if ($pagina_actual > 1) {
            $html .= '<a href="?pagina=' . ($pagina_actual - 1) . '">« Anterior</a>';
        }
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                $html .= '<span>' . $i . '</span>';
            } else {
                $html .= '<a href="?pagina=' . $i . '">' . $i . '</a>';
            }
        }
        if ($pagina_actual < $total_paginas) {
            $html .= '<a href="?pagina=' . ($pagina_actual + 1) . '">Següent »</a>';
        }
        $html .= '</div>';

        echo $html;

    } catch (Exception $e) {
        error_log("Error en mostrarAnimales: " . $e->getMessage());
        echo "<p class='error'>Hi ha hagut un error al carregar els animals.</p>";
    }
}

function mostrarMisAnimales($usuario_id) {
    global $conn;

    // Nombre d'articles per pàgina
    $articulos_por_pagina = 5;

    // Obtenir el nombre total d'articles del usuario
    $consultaTotal = $conn->prepare("SELECT COUNT(*) AS total FROM animales WHERE usuario_id = ?");
    $consultaTotal->bind_param("i", $usuario_id);
    $consultaTotal->execute();
    $total_articulos = $consultaTotal->get_result()->fetch_assoc()['total'];

    // Calcular el nombre total de pàgines
    $total_paginas = ceil($total_articulos / $articulos_por_pagina);

    // Obtenir la pàgina actual des de la URL o establir 1 com a valor per defecte
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $pagina_actual = max(1, min($pagina_actual, $total_paginas)); 

    // Calcular l'índex del primer article de la pàgina actual
    $inicio = ($pagina_actual - 1) * $articulos_por_pagina;

    try {
        // Preparar la consulta per obtenir els articles del usuario de la pàgina actual
        $consultaArticulos = $conn->prepare("SELECT * FROM animales WHERE usuario_id = ? LIMIT ?, ?");
        $consultaArticulos->bind_param("iii", $usuario_id, $inicio, $articulos_por_pagina);
        $consultaArticulos->execute();
        $resultados = $consultaArticulos->get_result();

        // Generar la llista d'articles
        $html = '<div class="animals-container">';
        while ($animal = $resultados->fetch_assoc()) {
            $html .= "<div class='animal-card'>";

            // Imagen con comprobación
            $imagen = !empty($animal['imatge']) ? $animal['imatge'] : 'default.jpg';
            $html .= "<img src='../img/" . htmlspecialchars($imagen) . "' alt='" . htmlspecialchars($animal['nom']) . "'>";

            // Información del animal
            $html .= "<h3>" . htmlspecialchars($animal['nom']) . "</h3>";
            $html .= "<p>" . htmlspecialchars($animal['descripció']) . "</p>";

            // Botones de acción
            $html .= "<div class='actions'>";
            $html .= "<a href='vista/Modificar.html?id=" . $animal['id'] . "' class='btn'>Modificar</a>";
            $html .= "<a href='/vista/Esborrar.php?id=" . $animal['id'] . "&imagen=" . $animal['imatge'] . "' class='btn btn-danger'>Eliminar</a>";
            $html .= "</div>";

            $html .= "</div>";
        }

        // Si no hay animales
        if ($resultados->num_rows == 0) {
            $html .= "<p>No hi ha animals disponibles.</p>";
        }

        $html .= '</div>';

        // Generar els enllaços de paginació
        $html .= '<div class="pagination">';
        if ($pagina_actual > 1) {
            $html .= '<a href="?pagina=' . ($pagina_actual - 1) . '">« Anterior</a>';
        }
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                $html .= '<span>' . $i . '</span>';
            } else {
                $html .= '<a href="?pagina=' . $i . '">' . $i . '</a>';
            }
        }
        if ($pagina_actual < $total_paginas) {
            $html .= '<a href="?pagina=' . ($pagina_actual + 1) . '">Següent »</a>';
        }
        $html .= '</div>';

        echo $html;

    } catch (Exception $e) {
        error_log("Error en mostrarMisAnimales: " . $e->getMessage());
        echo "<p class='error'>Hi ha hagut un error al carregar els animals.</p>";
    }
}
?>
