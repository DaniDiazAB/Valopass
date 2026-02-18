<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";
require_once "../config/secret.php";

$input = file_get_contents("php://input");


$username = $_SESSION['usuario'] ?? '';

if (!$username) {
    echo json_encode(["status" => "error", "msg" => "No autenticado"]);
    exit;
}

$stmt = $pdo->prepare('SELECT id_usuario FROM usuarios WHERE nombre_usuario = :username');
$stmt->execute(['username' => $username]);

$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resultado) {
    echo json_encode(["status" => "error", "msg" => "Usuario no encontrado"]);
    exit;
}

$id_usuario_perfil = $resultado['id_usuario'];


try {
    $sql = "DELETE FROM cuenta_main WHERE id_usuario_cuenta_main = :id_usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":id_usuario" => $id_usuario_perfil,
    ]);

    echo json_encode(["status" => "ok", "msg" => "Cuenta agregada correctamente"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}
