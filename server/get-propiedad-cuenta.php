<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['error' => 'Datos JSON inválidos']);
    exit;
}

$nickname = $data['nickname'] ?? null;
$tag = $data['tag'] ?? null;
$id_cuenta = $data['id_cuenta'] ?? null;

try {
    // conseguir id de la cuenta para conseguir el id del usuario
    $sql = "SELECT id_cuenta FROM cuentas WHERE nick_cuenta = :nickname AND tag_cuenta = :tag";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":nickname" => $nickname, ":tag" => $tag]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_cuenta = $resultado['id_cuenta'];
    
    // conseguir id del usuario para luego conseguir el nombre del usuario
    $sql = "SELECT id_usuario FROM cuentas_usuarios WHERE id_cuenta = :id_cuenta";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id_cuenta" => $id_cuenta]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_usuario = $resultado['id_usuario'];

    // conseguir nick del usuario
    $sql = "SELECT nombre_usuario FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id_usuario" => $id_usuario]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_cuenta = $resultado['nombre_usuario'];

    echo json_encode([
        "status" => "ok",
        "nombre_usuario" => $id_cuenta
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}
