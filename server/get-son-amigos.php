<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$id_uno = $datos['idPerfilUsuario'] ?? '';
$id_dos = $datos['idUsuarioLogin'] ?? '';

try {
    $sql = "SELECT COUNT(*) 
            FROM usuarios_amigos 
            WHERE (id_usuario_uno_usuarios_amigos = :id_uno AND id_usuario_dos_usuarios_amigos = :id_dos) 
               OR (id_usuario_uno_usuarios_amigos = :id_dos AND id_usuario_dos_usuarios_amigos = :id_uno)"; 
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id_uno" => $id_uno, ":id_dos" => $id_dos]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($resultado) {
        echo json_encode([
            "status" => "ok",
            "son_amigos" => (bool)$resultado['COUNT(*)']
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "msg" => "No son amigos"
        ]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}
