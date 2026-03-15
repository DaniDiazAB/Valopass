<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";
require_once "../config/secret.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$usuario_uno = $datos['idPerfilUsuario'] ?? '';
$usuario_dos = $datos['idUsuarioLogin'] ?? '';
$tipo_solicitud = $datos['tipoSolicitud'] ?? '';

try {
    if ($tipo_solicitud === 'amistad') {
        agregarEliminarAmistad($usuario_uno, $usuario_dos, $pdo);
    }

    if ($tipo_solicitud === 'bloqueo') {
        bloquearUsuario($usuario_uno, $usuario_dos, $pdo);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "msg" => $e->getMessage()
    ]);
}


function comprobarAmistad($usuario_uno, $usuario_dos, $pdo){

    $sql = "SELECT COUNT(*) as total
            FROM usuarios_amigos 
            WHERE (id_usuario_uno_usuarios_amigos = :id_uno AND id_usuario_dos_usuarios_amigos = :id_dos) 
               OR (id_usuario_uno_usuarios_amigos = :id_dos AND id_usuario_dos_usuarios_amigos = :id_uno)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":id_uno" => $usuario_uno,
        ":id_dos" => $usuario_dos
    ]);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado['total']  ?? 0;
}

function agregarEliminarAmistad($usuario_uno, $usuario_dos, $pdo){
    if (comprobarAmistad($usuario_uno, $usuario_dos, $pdo) > 0) {

        $sql = "DELETE FROM usuarios_amigos 
                WHERE (id_usuario_uno_usuarios_amigos = :id_uno AND id_usuario_dos_usuarios_amigos = :id_dos) 
                   OR (id_usuario_uno_usuarios_amigos = :id_dos AND id_usuario_dos_usuarios_amigos = :id_uno)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":id_uno" => $usuario_uno,
            ":id_dos" => $usuario_dos
        ]);

        echo json_encode([
            "status" => "ok",
            "msg" => "Amistad eliminada"
        ]);
    } else {

        $sql = "INSERT INTO usuarios_amigos 
                (id_usuario_uno_usuarios_amigos, id_usuario_dos_usuarios_amigos, estado_usuarios_amigos) 
                VALUES (:usuario_uno, :usuario_dos, 2)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":usuario_uno" => $usuario_uno,
            ":usuario_dos" => $usuario_dos
        ]);

        echo json_encode([
            "status" => "ok",
            "msg" => "Amistad creada"
        ]);
    }
}

function bloquearUsuario($usuario_uno, $usuario_dos, $pdo){
    if (comprobarAmistad($usuario_uno, $usuario_dos, $pdo) > 0) {

    $sql = "DELETE FROM usuarios_amigos 
                WHERE (id_usuario_uno_usuarios_amigos = :id_uno AND id_usuario_dos_usuarios_amigos = :id_dos) 
                   OR (id_usuario_uno_usuarios_amigos = :id_dos AND id_usuario_dos_usuarios_amigos = :id_uno)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":id_uno" => $usuario_uno,
            ":id_dos" => $usuario_dos
        ]);

        $sql = "INSERT INTO usuarios_amigos 
                (id_usuario_uno_usuarios_amigos, id_usuario_dos_usuarios_amigos, estado_usuarios_amigos) 
                VALUES (:usuario_uno, :usuario_dos, 3)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":usuario_uno" => $usuario_uno,
            ":usuario_dos" => $usuario_dos
        ]);

        echo json_encode([
            "status" => "ok",
            "msg" => "Usuario bloqueado"
        ]);


    }

}
