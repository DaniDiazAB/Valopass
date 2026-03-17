<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$id_perfil = $datos['idPerfilUsuario'] ?? '';

try {
    $sql = "SELECT * FROM usuarios_amigos";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id_uno" => $id_uno, ":id_dos" => $id_dos]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC); 
    
    echo json_encode([
        "status" => "ok",
        "estado_relacion" => $resultado
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}
