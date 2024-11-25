<?php
$db_host = 'db239154.dns-servicio.com';
$db_name = 'ddb239154';
$db_user = 'ddb239154';
$db_pass = '4{W{3j}CJRF%RF';

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>