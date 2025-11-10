<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$username = $datos['username'] ?? '';

if ($username === '') {
    echo json_encode(["status" => "error", "msg" => "Falta username"]);
    exit;
}

try {
    $sql = "SELECT cuenta_publica 
            FROM cuentas 
            WHERE nick_cuenta = :username";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":username" => $username]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        echo json_encode([
            "status" => "ok",
            "isPublica" => (bool)$resultado['isCuentaPublica']
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "msg" => "Cuenta no encontrada"
        ]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}
