<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";
require_once "../config/secret.php";


$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$passwordPlano = $datos['password'];
$passwordCifrado = encriptar($passwordPlano);

$nick     = $datos['nick'] ?? '';
$tag      = $datos['tag'] ?? '';
$username = $datos['username'] ?? '';
$isPublica = $datos['isPublica'] ?? '';

//echo json_encode(["status" => $username]);


if ($username === '') {
    echo json_encode(["status" => "error", "msg" => $nick]);
    exit;
}

try {
    $sql = "SELECT COUNT(*) as total FROM cuentas WHERE username_cuenta = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['total'] > 0) {
        echo json_encode(["status" => "error", "msg" => "El usuario ya existe en la BBDD"]);
        exit;
    } else {
      


    $sql = "INSERT INTO cuentas (nick_cuenta, tag_cuenta, username_cuenta, password_cuenta, cuenta_visible_amigos, cuenta_publica) VALUES
    (:nick, :tag, :username, :passwordCuenta, 1, :isPublica)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":nick" => $nick,
        ":tag" => $tag,
        ":username" => $username,
        ":passwordCuenta" => $passwordCifrado,
        ":isPublica" => $isPublica,
    ]);
    
    $idCuenta = $pdo->lastInsertId();
    $usuarioId = $_SESSION['usuario_id'] ?? null;

    $sql = "INSERT INTO cuentas_usuarios (id_cuenta, id_usuario) VALUES
    ($idCuenta, $usuarioId)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        
    ]);
    

    echo json_encode(["status" => "ok", "msg" => "Cuenta agregada correctamente"]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}


function encriptar($textoPlano) {
    return openssl_encrypt($textoPlano, METHOD, SECRET_KEY, 0, SECRET_IV);
}

function desencriptar($textoCifrado) {
    return openssl_decrypt($textoCifrado, METHOD, SECRET_KEY, 0, SECRET_IV);
}
    

