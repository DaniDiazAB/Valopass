<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$nick     = $datos['nick'] ?? '';
$tag      = $datos['tag'] ?? '';
$username = $datos['username'] ?? '';
$password = $datos['password'] ?? '';
$eliminar = $datos['eliminar'] ?? '';
$isPublica = $datos['isPublica'] ?? '';

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
        echo json_encode(["status" => "ok", "msg" => "Cuenta eliminada"]);

    }else{
        // se saca el id de la cuenta, con ese id, se va a la tabla cuentas_usuarios y se comprueba
        // si el id del usuario coincide con el id en el que se ha iniciado sesion
        $sql = "SELECT id_cuenta FROM cuentas WHERE username_cuenta = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":username" => $username]);
        $idCuenta = $stmt->fetch(PDO::FETCH_ASSOC); 
        $id_cuenta_valor = $idCuenta['id_cuenta'];

        /*
        $sql = "SELECT id_usuario FROM cuentas_usuarios WHERE id:cuenta = :usuario";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":usuario" => $resultado]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC); 
        */

        $sql = "SELECT id_usuario FROM cuentas_usuarios WHERE id_cuenta = :cuenta";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":cuenta" => $id_cuenta_valor]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_usuario_valor = $usuario['id_usuario'];

        if ($id_usuario_valor == $id_cuenta_valor){
            $sql = "UPDATE cuentas 
            SET nick_cuenta = :nick, tag_cuenta = :tag, password_cuenta = :password, cuenta_publica = :isPublica
            WHERE username_cuenta = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":nick"     => $nick,
                ":tag"      => $tag,
                ":password" => $password,
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
