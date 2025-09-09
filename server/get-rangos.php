<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$username = $datos['username'] ?? '';

if ($username === '') {
    echo json_encode(["status" => "error", "msg" => "Falta username para identificar la fila"]);
    exit;
}

try {
    $sql = "SELECT rango_cuenta FROM cuentas WHERE username_cuenta = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":username" => $username,
    ]);

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fila) {
        // Si encontró algo
        echo json_encode([$fila["rango_cuenta"]]);
    } else {
        // Si no encontró resultados
        echo json_encode(["status" => "error", "msg" => "No se encontró la cuenta"]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}

