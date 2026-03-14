<?php
session_start();

// Cambia el header a json ya que responderás un JSON
header("Content-Type: application/json; charset=UTF-8");
require_once "db.php";

// LEER JSON RECIBIDO
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Ahora usas $data en lugar de $_POST
$username = $_SESSION["usuario_id"] ?? '';
$nombre_cuenta = $data['nombreCuenta'] ?? '';
$tag_cuenta = $data['tagCuenta'] ?? '';
$elo = 'Gold 2';

if (empty($username) || empty($nombre_cuenta)) {
    echo json_encode(["status" => "error", "msg" => "Datos incompletos"]);
    exit;
}

try {

    $sql = "INSERT INTO cuenta_main (nombre_cuenta_main, tag_cuenta_main, elo_cuenta_main, id_usuario_cuenta_main) VALUES (:nombre_cuenta, :tag_cuenta, :elo, :username)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre_cuenta' => $nombre_cuenta,
        ':tag_cuenta' => $tag_cuenta,
        ':elo' => $elo,
        ':username' => $username
    ]);

    echo json_encode(["status" => "ok"]);

} catch(PDOException $e) {
    echo json_encode([
        "status" => "error",
        "msg" => $e->getMessage(),
        "sql1" => $username,   
    ]);
}
?>