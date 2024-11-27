<?php
//Marcos Lopez Medina
require_once 'model/db.php'; 

function mostrarAnimales($articulos_por_pagina = 5) {
    global $conn;

    // Obtenir el nombre total d'articles que pertanyen a usuaris existents
    $consultaTotal = $conn->query("SELECT COUNT(*) AS total 
                                   FROM animales a 
                                   JOIN usuarios u ON a.usuario_id = u.id");
    $total_articulos = $consultaTotal->fetch_assoc()['total'];

    // Calcular el nombre total de pàgines
    $total_paginas = ceil($total_articulos / $articulos_por_pagina);

    // Obtenir la pàgina actual des de la URL o establir 1 com a valor per defecte
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $pagina_actual = max(1, min($pagina_actual, $total_paginas)); 

    // Calcular l'índex del primer article de la pàgina actual
    $inicio = ($pagina_actual - 1) * $articulos_por_pagina;

    try {
        // Preparar la consulta per obtenir els articles de la pàgina actual que pertanyen a usuaris existents
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
            if (isset($_SESSION['usuario_id']) && ($_SESSION['usuario_id'] == $animal['usuario_id'] || $_SESSION['rol'] === 'admin')) {
                $html .= "<div class='actions'>";
                $html .= "<a href='view/modificar.vista.html?id=" . $animal['id'] . "&nombre=" . urlencode($animal['nom']) . "&cuerpo=" . urlencode($animal['descripció']) . "&imagen=" . urlencode($animal['imatge']) . "' class='btn'>Modificar</a>";
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
            $html .= '<a href="?pagina=' . ($pagina_actual - 1) . '&articulos_por_pagina=' . $articulos_por_pagina . '">« Anterior</a>';
        }
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                $html .= '<span>' . $i . '</span>';
            } else {
                $html .= '<a href="?pagina=' . $i . '&articulos_por_pagina=' . $articulos_por_pagina . '">' . $i . '</a>';
            }
        }
        if ($pagina_actual < $total_paginas) {
            $html .= '<a href="?pagina=' . ($pagina_actual + 1) . '&articulos_por_pagina=' . $articulos_por_pagina . '">Següent »</a>';
        }
        $html .= '</div>';

        echo $html;

    } catch (Exception $e) {
        error_log("Error en mostrarAnimales: " . $e->getMessage());
        echo "<p class='error'>Hi ha hagut un error al carregar els animals.</p>";
    }
}

function mostrarMisAnimales($usuario_id, $articulos_por_pagina = 5) {
    global $conn;

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
            if (isset($_SESSION['usuario_id']) && ($_SESSION['usuario_id'] == $animal['usuario_id'] || $_SESSION['rol'] === 'admin')) {
                $html .= "<div class='actions'>";
                $html .= "<a href='../view/modificar.vista.html?id=" . $animal['id'] . "&nombre=" . urlencode($animal['nom']) . "&cuerpo=" . urlencode($animal['descripció']) . "&imagen=" . urlencode($animal['imatge']) . "' class='btn'>Modificar</a>";
                $html .= "<a href='../view/Esborrar.vista.html?id=" . $animal['id'] . "&imagen=" . $animal['imatge'] . "' class='btn btn-danger'>Eliminar</a>";
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
            $html .= '<a href="#" class="pagination-link" data-page="' . ($pagina_actual - 1) . '">« Anterior</a>';
        }
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                $html .= '<span>' . $i . '</span>';
            } else {
                $html .= '<a href="#" class="pagination-link" data-page="' . $i . '">' . $i . '</a>';
            }
        }
        if ($pagina_actual < $total_paginas) {
            $html .= '<a href="#" class="pagination-link" data-page="' . ($pagina_actual + 1) . '">Següent »</a>';
        }
        $html .= '</div>';

        echo $html;

    } catch (Exception $e) {
        error_log("Error en mostrarMisAnimales: " . $e->getMessage());
        echo "<p class='error'>Hi ha hagut un error al carregar els animals.</p>";
    }
}
?>
