<?php
require_once __DIR__ . '../env.php';

function connexio() {
    //Dades connexio a BD.
    $host = DB_HOST;
    $nomBD = DB_NAME;
    $usuari = DB_USER;
    $contra = DB_PASS;

    //Connexió.
    try {
        $connexio = new PDO("mysql:host=$host;dbname=$nomBD", $usuari, $contra);
        $connexio->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connexio;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>