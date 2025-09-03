<?php
$host = "localhost";
$user = "root";  // cambia si tu host tiene otro usuario
$pass = "";      // pon tu contraseña de MySQL si tienes
$dbname = "valopass";


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
