<?php
require_once 'model/db.php'; 

function mostrarPokemons($pokemons_por_pagina = 5, $orden = 'asc', $search_term = '') {
    global $conn;

    // Verificar que el número de pokemons por página no sea inferior a 5
    $pokemons_por_pagina = max(5, $pokemons_por_pagina);

    // Verificar que el orden sea válido
    $orden = ($orden === 'desc') ? 'DESC' : 'ASC';

    // Preparar la consulta base
    $query = "SELECT p.*, u.nom as usuario_nom, u.id as usuario_id 
              FROM pokemons p 
              JOIN usuarios u ON p.usuario_id = u.id 
              WHERE p.nom LIKE ? OR u.nom LIKE ? 
              ORDER BY p.nom $orden 
              LIMIT ?, ?";

    // Calcular el número total de pokemons que coinciden con el término de búsqueda
    $consultaTotal = $conn->prepare("SELECT COUNT(*) AS total 
                                     FROM pokemons p 
                                     JOIN usuarios u ON p.usuario_id = u.id 
                                     WHERE p.nom LIKE ? OR u.nom LIKE ?");
    $search_term_like = '%' . $search_term . '%';
    $consultaTotal->bind_param("ss", $search_term_like, $search_term_like);
    $consultaTotal->execute();
    $total_pokemons = $consultaTotal->get_result()->fetch_assoc()['total'];

    // Calcular el número total de páginas
    $total_paginas = ceil($total_pokemons / $pokemons_por_pagina);

    // Obtener la página actual desde la URL o establecer 1 como valor por defecto
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $pagina_actual = max(1, min($pagina_actual, $total_paginas)); 

    // Calcular el índice del primer pokemon de la página actual
    $inicio = ($pagina_actual - 1) * $pokemons_por_pagina;

    try {
        // Preparar la consulta para obtener los pokemons de la página actual que coinciden con el término de búsqueda
        $consultaPokemons = $conn->prepare($query);
        $consultaPokemons->bind_param("ssii", $search_term_like, $search_term_like, $inicio, $pokemons_por_pagina);
        $consultaPokemons->execute();
        $result = $consultaPokemons->get_result();

        echo '<div class="pokemons-container">';
        while ($pokemon = $result->fetch_assoc()) {
            $imagen = isset($pokemon['imatge']) ? $pokemon['imatge'] : 'default.jpg';
            $descripcion = isset($pokemon['descripció']) ? $pokemon['descripció'] : 'No description available.';
            echo '<div class="pokemon-card">';
            echo '<img src="img/' . htmlspecialchars($imagen) . '" alt="Pokemon Image" onclick="openImageModal(this.src)">';
            echo '<div class="pokemon-info">';
            echo '<h3>' . htmlspecialchars($pokemon['nom']) . '</h3>';
            echo '<p>' . htmlspecialchars($descripcion) . '</p>';
            echo '<p>Força: ' . htmlspecialchars($pokemon['força']) . '</p>';
            echo '<p>Vida: ' . htmlspecialchars($pokemon['vida']) . '</p>';
            echo '<p>Daño: ' . htmlspecialchars($pokemon['dany']) . '</p>';
            echo '<p>Tipus: ' . htmlspecialchars($pokemon['tipus']) . '</p>';
            echo '<a href="view/perfilUsuario.vista.php?id=' . htmlspecialchars($pokemon['usuario_id']) . '" class="btn">Ver Perfil de ' . htmlspecialchars($pokemon['usuario_nom']) . '</a>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';

        // Paginación
        echo '<div class="pagination">';
        if ($pagina_actual > 1) {
            echo '<a href="?pagina=' . ($pagina_actual - 1) . '&pokemons_por_pagina=' . $pokemons_por_pagina . '&orden=' . $orden . '&search=' . $search_term . '">&laquo; Anterior</a>';
        }
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                echo '<span class="current-page">' . $i . '</span>';
            } else {
                echo '<a href="?pagina=' . $i . '&pokemons_por_pagina=' . $pokemons_por_pagina . '&orden=' . $orden . '&search=' . $search_term . '">' . $i . '</a>';
            }
        }
        if ($pagina_actual < $total_paginas) {
            echo '<a href="?pagina=' . ($pagina_actual + 1) . '&pokemons_por_pagina=' . $pokemons_por_pagina . '&orden=' . $orden . '&search=' . $search_term . '">Siguiente &raquo;</a>';
        }
        echo '</div>';

    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

function mostrarMisPokemons($usuario_id, $pokemons_por_pagina = 5, $orden = 'asc', $pagina = 1) {
    global $conn;

    $inicio = ($pagina - 1) * $pokemons_por_pagina;

    $query = "SELECT * FROM pokemons WHERE usuario_id = ? ORDER BY nom $orden LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $usuario_id, $inicio, $pokemons_por_pagina);
    $stmt->execute();
    $resultados = $stmt->get_result();

    $html = '<div class="pokemons-container">';
    while ($pokemon = $resultados->fetch_assoc()) {
        $html .= "<div class='pokemon-card'>";
        $html .= "<img src='../img/" . htmlspecialchars($pokemon['imatge']) . "' alt='" . htmlspecialchars($pokemon['nom']) . "'>";
        $html .= "<h3>" . htmlspecialchars($pokemon['nom']) . "</h3>";
        $html .= "<p>" . htmlspecialchars($pokemon['descripció']) . "</p>";
        $html .= "</div>";
    }
    $html .= '</div>';

    // Paginación
    $consultaTotal = $conn->prepare("SELECT COUNT(*) AS total FROM pokemons WHERE usuario_id = ?");
    $consultaTotal->bind_param("i", $usuario_id);
    $consultaTotal->execute();
    $total_pokemons = $consultaTotal->get_result()->fetch_assoc()['total'];
    $total_paginas = ceil($total_pokemons / $pokemons_por_pagina);

    $html .= '<div class="pagination">';
    if ($pagina > 1) {
        $html .= '<a href="#" class="pagination-link" data-page="' . ($pagina - 1) . '">« Anterior</a>';
    }
    for ($i = 1; $i <= $total_paginas; $i++) {
        if ($i == $pagina) {
            $html .= '<span class="pagination-link current-page">' . $i . '</span>';
        } else {
            $html .= '<a href="#" class="pagination-link" data-page="' . $i . '">' . $i . '</a>';
        }
    }
    if ($pagina < $total_paginas) {
        $html .= '<a href="#" class="pagination-link" data-page="' . ($pagina + 1) . '">Següent »</a>';
    }
    $html .= '</div>';

    return $html;
}
?>
