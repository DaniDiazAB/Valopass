<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$id_perfil = $datos['idPerfilUsuario'] ?? '';


if ($id_perfil === '') {
    echo json_encode(["status" => "error", "msg" => "Falta username para identificar la fila"]);
    exit;
}


try {
    $sql = "SELECT c.*, cu.is_mayor_elo, cu.is_menor_elo
            FROM cuentas_usuarios cu
            JOIN cuentas c ON cu.id_cuenta = c.id_cuenta
            WHERE cu.id_usuario = :id_perfil
            AND (cu.is_mayor_elo = 1 OR cu.is_menor_elo = 1)
            ORDER BY cu.is_mayor_elo DESC, cu.is_menor_elo DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":id_perfil" => $id_perfil,
    ]);

    $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($filas) {
        echo json_encode($filas);
    } else {
        echo json_encode([
            "status" => "error",
            "msg" => "No se encontraron cuentas con mayor o menor elo" . $sql
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "msg" => $e->getMessage()
    ]);
}

