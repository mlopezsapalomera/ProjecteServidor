<?php
function mostrarAnimales($db) {
    try {
        $stmt = $db->query("SELECT a.*, u.nom as usuario_nom 
                           FROM animales a 
                           JOIN usuarios u ON a.usuario_id = u.id");
        
        while($animal = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='animal-card'>";
            echo "<img src='img/" . htmlspecialchars($animal['imatge']) . "'>";
            echo "<h3>" . htmlspecialchars($animal['nom']) . "</h3>";
            echo "<p>" . htmlspecialchars($animal['descripció']) . "</p>";
            echo "<div class='actions'>";
            echo "<a href='views/modificar.vista.html?id=" . $animal['id'] . "' class='btn'>Modificar</a>";
            echo "<a href='views/eliminar.vista.html?id=" . $animal['id'] . "' class='btn'>Eliminar</a>";
            echo "</div>";
            echo "</div>";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>