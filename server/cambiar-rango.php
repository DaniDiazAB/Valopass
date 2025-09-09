<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$username = $datos['username'] ?? '';
$rango = $datos['rango'] ?? '';


if ($username === '') {
    echo json_encode(["status" => "error", "msg" => "Falta username para identificar la fila"]);
    exit;
}

try {
    $sql = "UPDATE cuentas 
        SET rango_cuenta = :rango
        WHERE username_cuenta = :username";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":username" => $username,
            ":rango" => $rango
        ]);
        echo json_encode(["status" => "ok", "msg" => "Cuenta actualizada correctamente"]);


} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}
