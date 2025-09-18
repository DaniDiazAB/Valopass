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

$isTodas = filter_var($_POST["isTodasCuentas"] ?? false, FILTER_VALIDATE_BOOLEAN);

if ($isTodas) {
    $sql = "SELECT c.id_cuenta,  c.username_cuenta, c.nick_cuenta, c.tag_cuenta, c.password_cuenta, c.rango_cuenta
            FROM cuentas c
            WHERE isCuentaPublica = 1";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT c.id_cuenta, c.username_cuenta, c.nick_cuenta, c.tag_cuenta, c.password_cuenta, c.rango_cuenta
            FROM cuentas c
            INNER JOIN cuentas_usuario cu ON c.id_cuenta = cu.id_cuenta
            WHERE cu.id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
}

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

