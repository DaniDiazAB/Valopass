<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";
require_once "../config/secret.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$nick = $datos['nombreCuentaMain'] ?? '';
$tag  = $datos['tagCuentaMain'] ?? '';

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


$url = "https://vaccie.pythonanywhere.com/mmr/$nick/$tag/eu";
$response = file_get_contents($url);

$partes = explode(",", $response);
$rango = $partes[0]; 

try {

    $sql = "INSERT INTO cuenta_main 
            (nombre_cuenta_main, tag_cuenta_main, elo_cuenta_main, id_usuario_cuenta_main) 
            VALUES (:nick, :tag, :rango, :id_usuario)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":nick" => $nick,
        ":tag" => $tag,
        "rango" => $rango,
        ":id_usuario" => $id_usuario_perfil,
    ]);

    echo json_encode(["status" => "ok", "msg" => "Cuenta agregada correctamente"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}

