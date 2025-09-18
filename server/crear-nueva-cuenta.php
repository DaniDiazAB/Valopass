<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$nick     = $datos['nick'] ?? '';
$tag      = $datos['tag'] ?? '';
$username = $datos['username'] ?? '';
$passwordCuenta = $datos['password'] ?? '';
$isPublica = $datos['isPublica'] ?? '';

if ($username === '') {
    echo json_encode(["status" => "error", "msg" => "Falta username para identificar la fila"]);
    exit;
}

try {
    $sql = "INSERT INTO cuentas (nick_cuenta, tag_cuenta, username_cuenta, password_cuenta, isVisibleAmigos, isCuentaPublica) VALUES
    (:nick, :tag, :username, :passwordCuenta, 1, :isPublica)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":nick" => $nick,
        ":tag" => $tag,
        ":username" => $username,
        ":passwordCuenta" => $passwordCuenta,
        ":isPublica" => $isPublica,
    ]);
    
    $idCuenta = $pdo->lastInsertId();
    $usuarioId = $_SESSION['usuario_id'] ?? null;

    $sql = "INSERT INTO cuentas_usuario (id_cuenta, id_usuario) VALUES
    ($idCuenta, $usuarioId)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        
    ]);
    

    echo json_encode(["status" => "ok", "msg" => "Cuenta agregada correctamente"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}
