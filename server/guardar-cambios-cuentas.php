<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";
require_once "../config/secret.php";


$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$user_login = $_SESSION["usuario_id"];

$nick     = $datos['nick'] ?? '';
$tag      = $datos['tag'] ?? '';
$username = $datos['username'] ?? '';
$eliminar = $datos['eliminar'] ?? '';
$isPublica = $datos['isPublica'] ?? '';
$passwordPlano = $datos['password'];
$passwordCifrado = encriptar($passwordPlano);

if ($username === '') {
    echo json_encode(["status" => "error", "msg" => "Falta username para identificar la fila"]);
    exit;
}

try {
    if ($eliminar){
        $sql = "SELECT id_cuenta FROM cuentas WHERE username_cuenta = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":username" => $username]);
        $id_cuenta = $stmt->fetchColumn();

        $sql = "DELETE FROM cuentas WHERE username_cuenta = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":username" => $username
        ]);

        $sql = "DELETE FROM cuentas_usuarios WHERE id_cuenta = :id_cuenta";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":id_cuenta" => $id_cuenta]);

        echo json_encode(["status" => "ok", "msg" => "Cuenta eliminada"]);

    }else{
        // se saca el id de la cuenta, con ese id, se va a la tabla cuentas_usuarios y se comprueba
        // si el id del usuario coincide con el id en el que se ha iniciado sesion
        $sql = "SELECT id_cuenta FROM cuentas WHERE username_cuenta = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":username" => $username]);
        $idCuenta = $stmt->fetch(PDO::FETCH_ASSOC); 
        $id_cuenta_valor = $idCuenta['id_cuenta']; // ID de la tabla cuentas


        $sql = "SELECT id_usuario FROM cuentas_usuarios WHERE id_cuenta = :cuenta";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":cuenta" => $id_cuenta_valor]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_usuario_valor = $usuario['id_usuario']; // id_usuario de la tabla cuentas_usuarios

    
        if ($id_usuario_valor == $user_login){
            $sql = "UPDATE cuentas 
            SET nick_cuenta = :nick, tag_cuenta = :tag, password_cuenta = :passwordCifrado, cuenta_publica = :isPublica
            WHERE username_cuenta = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":nick"     => $nick,
                ":tag"      => $tag,
                ":passwordCifrado" => $passwordCifrado,
                ":username" => $username,
                ":isPublica" => $isPublica
            ]);
            echo json_encode(true);

        }else{
            echo json_encode(false);

        }
        
        //echo json_encode(["status" => "ok", "msg" => "Cuenta actualizada correctamente", "nick" => $nick, "Publica" => $isPublica, "Sesion" => $_SESSION["usuario_id"]]);
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
    