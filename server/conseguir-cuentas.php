<?php
session_start();
include "db.php";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "msg" => "Error en la conexión: " . $conn->connect_error]));
}

$usuarioId = $_SESSION['usuario_id'] ?? null;

if (!$usuarioId) {
    echo json_encode([ "status" => "error", "msg" => "No has iniciado sesión" ]);
    exit;
}


$sql = "SELECT c.id_cuenta,  c.username_cuenta, c.nick_cuenta, c.tag_cuenta, c.password_cuenta, c.rango_cuenta FROM cuentas c";
$stmt = $conn->prepare($sql);

$stmt->execute();
$result = $stmt->get_result();

$datos = [];
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($datos);

$stmt->close();
$conn->close();
?>