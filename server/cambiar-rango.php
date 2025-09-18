<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");


require_once "db.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$username = $datos['username'] ?? '';
$rango = $datos['rango'] ?? '';

$rangos = ["Iron 1","Iron 2","Iron 3","Bronze 1","Bronze 2","Bronze 3","Silver 1","Silver 2","Silver 3",
    "Gold 1","Gold 2","Gold 3","Platinum 1","Platinum 2","Platinum 3","Diamond 1","Diamond 2","Diamond 3",
    "Ascendant 1","Ascendant 2","Ascendant 3","Immortal 1","Immortal 2","Immortal 3","Radiant"];



if ($username === '') {
    echo json_encode(["status" => "error", "msg" => "Falta username para identificar la fila"]);
    exit;
}

try {

    if (in_array($miRango, $rangos)) {
       $sql = "UPDATE cuentas 
        SET rango_cuenta = :rango
        WHERE username_cuenta = :username";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":username" => $username,
            ":rango" => $rango
        ]);
        echo json_encode(["status" => "ok", "msg" => "Cuentas actualizadas correctamente"]);
    } else {
        echo json_encode(["status" => "ok", "msg" => "Cuentas no actualizadas"]);
    }

    


} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}
