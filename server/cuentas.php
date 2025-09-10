<?php
include "db.php";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

$sql = "SELECT id_cuenta, username_cuenta, nick_cuenta, tag_cuenta, username_cuenta, password_cuenta FROM cuentas";
$result = $conn->query($sql);

$datos = [];
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($datos);

$conn->close();
?>
