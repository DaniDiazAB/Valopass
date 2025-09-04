<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

// Incluir la conexión
require_once "db.php";

// Recibir JSON desde JS
$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$nick     = $datos['nick'] ?? '';
$tag      = $datos['tag'] ?? '';
$username = $datos['username'] ?? '';
$password = $datos['password'] ?? '';
$eliminar = $datos['eliminar'] ?? '';

if ($username === '') {
    echo json_encode(["status" => "error", "msg" => "Falta username para identificar la fila"]);
    exit;
}

try {
    if ($eliminar){
        $sql = "DELETE FROM cuentas WHERE username_cuenta = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":username" => $username
        ]);

    }else{
        $sql = "UPDATE cuentas 
            SET nick_cuenta = :nick, tag_cuenta = :tag, password_cuenta = :password 
            WHERE username_cuenta = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":nick"     => $nick,
                ":tag"      => $tag,
                ":password" => $password,
                ":username" => $username
            ]);
    }
    

    echo json_encode(["status" => "ok", "msg" => "Cuenta actualizada correctamente"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}
