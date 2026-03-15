<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";
require_once "../config/secret.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$usuario_uno = $datos['idPerfilUsuario'] ?? '';
$usuario_dos = $datos['idUsuarioLogin'] ?? '';

try {

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

    if ($resultado['total'] > 0) {

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
                VALUES (:usuario_uno, :usuario_dos, 1)";

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

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "msg" => $e->getMessage()
    ]);
}
    



    
