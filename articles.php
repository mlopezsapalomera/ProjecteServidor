<?php
//Marcos Lopez Medina
require_once 'model/db.php'; 

function mostrarPokemons($pokemons_por_pagina = 5) {
    global $conn;

    // Obtenir el nombre total de pokemons que pertanyen a usuaris existents
    $consultaTotal = $conn->query("SELECT COUNT(*) AS total 
                                   FROM pokemons p 
                                   JOIN usuarios u ON p.usuario_id = u.id");
    $total_pokemons = $consultaTotal->fetch_assoc()['total'];

    // Calcular el nombre total de pàgines
    $total_paginas = ceil($total_pokemons / $pokemons_por_pagina);

    // Obtenir la pàgina actual des de la URL o establir 1 com a valor per defecte
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $pagina_actual = max(1, min($pagina_actual, $total_paginas)); 

    // Calcular l'índex del primer pokemon de la pàgina actual
    $inicio = ($pagina_actual - 1) * $pokemons_por_pagina;

    try {
        // Preparar la consulta per obtenir els pokemons de la pàgina actual que pertanyen a usuaris existents
        $consultaPokemons = $conn->prepare("SELECT p.*, u.nom as usuario_nom 
                                             FROM pokemons p 
                                             JOIN usuarios u ON p.usuario_id = u.id 
                                             LIMIT ?, ?");
        $consultaPokemons->bind_param("ii", $inicio, $pokemons_por_pagina);
        $consultaPokemons->execute();
        $resultados = $consultaPokemons->get_result();

        // Generar la llista de pokemons
        $html = '<div class="pokemons-container">';
        while ($pokemon = $resultados->fetch_assoc()) {
            $html .= "<div class='pokemon-card'>";

            // Imagen con comprobación
            $imagen = !empty($pokemon['imatge']) ? $pokemon['imatge'] : 'default.jpg';
            $html .= "<img src='img/" . htmlspecialchars($imagen) . "' alt='" . htmlspecialchars($pokemon['nom']) . "'>";

            // Información del pokemon
            $html .= "<h3>" . htmlspecialchars($pokemon['nom']) . "</h3>";
            $html .= "<p>" . htmlspecialchars($pokemon['descripció']) . "</p>";
            $html .= "<p>Propietario: " . htmlspecialchars($pokemon['usuario_nom']) . "</p>";

            // Botones de acción con verificación de sesión
            if (isset($_SESSION['usuario_id']) && ($_SESSION['usuario_id'] == $pokemon['usuario_id'] || $_SESSION['rol'] === 'admin')) {
                $html .= "<div class='actions'>";
                $html .= "<a href='view/modificar.vista.php?id=" . $pokemon['id'] . "&nombre=" . urlencode($pokemon['nom']) . "&cuerpo=" . urlencode($pokemon['descripció']) . "&imagen=" . urlencode($pokemon['imatge']) . "' class='btn'>Modificar</a>";
                $html .= "<a href='view/Esborrar.vista.html?id=" . $pokemon['id'] . "&imagen=" . $pokemon['imatge'] . "' class='btn btn-danger'>Eliminar</a>";
                $html .= "</div>";
            }

            $html .= "</div>";
        }

        // Si no hay pokemons
        if ($resultados->num_rows == 0) {
            $html .= "<p>No hi ha pokemons disponibles.</p>";
        }

        $html .= '</div>';

        // Generar els enllaços de paginació
        $html .= '<div class="pagination">';
        if ($pagina_actual > 1) {
            $html .= '<a href="?pagina=' . ($pagina_actual - 1) . '&pokemons_por_pagina=' . $pokemons_por_pagina . '">« Anterior</a>';
        }
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                $html .= '<span>' . $i . '</span>';
            } else {
                $html .= '<a href="?pagina=' . $i . '&pokemons_por_pagina=' . $pokemons_por_pagina . '">' . $i . '</a>';
            }
        }
        if ($pagina_actual < $total_paginas) {
            $html .= '<a href="?pagina=' . ($pagina_actual + 1) . '&pokemons_por_pagina=' . $pokemons_por_pagina . '">Següent »</a>';
        }
        $html .= '</div>';

        echo $html;

    } catch (Exception $e) {
        error_log("Error en mostrarPokemons: " . $e->getMessage());
        echo "<p class='error'>Hi ha hagut un error al carregar els pokemons.</p>";
    }
}

function mostrarMisPokemons($usuario_id, $pokemons_por_pagina = 5) {
    global $conn;

    // Obtenir el nombre total de pokemons del usuario
    $consultaTotal = $conn->prepare("SELECT COUNT(*) AS total FROM pokemons WHERE usuario_id = ?");
    $consultaTotal->bind_param("i", $usuario_id);
    $consultaTotal->execute();
    $total_pokemons = $consultaTotal->get_result()->fetch_assoc()['total'];

    // Calcular el nombre total de pàgines
    $total_paginas = ceil($total_pokemons / $pokemons_por_pagina);

    // Obtenir la pàgina actual des de la URL o establir 1 com a valor per defecte
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $pagina_actual = max(1, min($pagina_actual, $total_paginas)); 

    // Calcular l'índex del primer pokemon de la pàgina actual
    $inicio = ($pagina_actual - 1) * $pokemons_por_pagina;

    try {
        // Preparar la consulta per obtenir els pokemons del usuario de la pàgina actual
        $consultaPokemons = $conn->prepare("SELECT * FROM pokemons WHERE usuario_id = ? LIMIT ?, ?");
        $consultaPokemons->bind_param("iii", $usuario_id, $inicio, $pokemons_por_pagina);
        $consultaPokemons->execute();
        $resultados = $consultaPokemons->get_result();

        // Generar la llista de pokemons
        $html = '<div class="pokemons-container">';
        while ($pokemon = $resultados->fetch_assoc()) {
            $html .= "<div class='pokemon-card'>";

            // Imagen con comprobación
            $imagen = !empty($pokemon['imatge']) ? $pokemon['imatge'] : 'default.jpg';
            $html .= "<img src='../img/" . htmlspecialchars($imagen) . "' alt='" . htmlspecialchars($pokemon['nom']) . "'>";

            // Información del pokemon
            $html .= "<h3>" . htmlspecialchars($pokemon['nom']) . "</h3>";
            $html .= "<p>" . htmlspecialchars($pokemon['descripció']) . "</p>";

            // Botones de acción
            if (isset($_SESSION['usuario_id']) && ($_SESSION['usuario_id'] == $pokemon['usuario_id'] || $_SESSION['rol'] === 'admin')) {
                $html .= "<div class='actions'>";
                $html .= "<a href='../view/modificar.vista.php?id=" . $pokemon['id'] . "&nombre=" . urlencode($pokemon['nom']) . "&cuerpo=" . urlencode($pokemon['descripció']) . "&imagen=" . urlencode($pokemon['imatge']) . "' class='btn'>Modificar</a>";
                $html .= "<a href='../view/Esborrar.vista.html?id=" . $pokemon['id'] . "&imagen=" . $pokemon['imatge'] . "' class='btn btn-danger'>Eliminar</a>";
                $html .= "</div>";
            }

            $html .= "</div>";
        }

        // Si no hay pokemons
        if ($resultados->num_rows == 0) {
            $html .= "<p>No hi ha pokemons disponibles.</p>";
        }

        $html .= '</div>';

        // Generar els enllaços de paginació
        $html .= '<div class="pagination">';
        if ($pagina_actual > 1) {
            $html .= '<a href="?pagina=' . ($pagina_actual - 1) . '&pokemons_por_pagina=' . $pokemons_por_pagina . '">« Anterior</a>';
        }
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                $html .= '<span>' . $i . '</span>';
            } else {
                $html .= '<a href="?pagina=' . $i . '&pokemons_por_pagina=' . $pokemons_por_pagina . '">' . $i . '</a>';
            }
        }
        if ($pagina_actual < $total_paginas) {
            $html .= '<a href="?pagina=' . ($pagina_actual + 1) . '&pokemons_por_pagina=' . $pokemons_por_pagina . '">Següent »</a>';
        }
        $html .= '</div>';

        echo $html;

    } catch (Exception $e) {
        error_log("Error en mostrarMisPokemons: " . $e->getMessage());
        echo "<p class='error'>Hi ha hagut un error al carregar els pokemons.</p>";
    }
}
?>
