<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";
require_once "../config/secret.php";

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$id_usuario = $_SESSION['usuario_id'] ?? null;

$tipo_cambio = $datos['tipoCambio'] ?? '';
$informacion = trim($datos['informacion'] ?? '');
$password    = $datos['password'] ?? '';

if (!$id_usuario) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Sesión no válida"
    ]);
    exit;
}

if (empty($tipo_cambio) || empty($informacion) || empty($password)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "Faltan datos"
    ]);
    exit;
}

$sql = "SELECT password_usuario
        FROM usuarios
        WHERE id_usuario = :id_usuario";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ":id_usuario" => $id_usuario
]);

$passwordHash = $stmt->fetchColumn();

if (!$passwordHash || !password_verify($password, $passwordHash)) {
    echo json_encode([
        "success" => false,
        "mensaje" => "La contraseña es incorrecta"
    ]);
    exit;
}

$texto_log = '';

if ($tipo_cambio === 'password') {

    $nuevaPasswordHash = password_hash($informacion, PASSWORD_DEFAULT);

    $sql = "UPDATE usuarios
            SET password_usuario = :password
            WHERE id_usuario = :id_usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":password"   => $nuevaPasswordHash,
        ":id_usuario" => $id_usuario
    ]);

    $texto_log = "La contraseña se ha cambiado";
} elseif ($tipo_cambio === 'username') {

    $sql = "SELECT COUNT(*)
            FROM usuarios
            WHERE nombre_usuario = :informacion
            AND id_usuario != :id_usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":informacion" => $informacion,
        ":id_usuario"  => $id_usuario
    ]);

    $nombreExiste = $stmt->fetchColumn() > 0;

    if ($nombreExiste) {
        echo json_encode([
            "success" => false,
            "mensaje" => "El nombre de usuario ya está en uso"
        ]);
        exit;
    }

    $sql = "UPDATE usuarios
            SET nombre_usuario = :informacion
            WHERE id_usuario = :id_usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":informacion" => $informacion,
        ":id_usuario"  => $id_usuario
    ]);

    $texto_log = "El nombre de usuario se ha cambiado";
} elseif ($tipo_cambio === 'email') {

    $sql = "UPDATE usuarios
            SET correo_usuario = :informacion
            WHERE id_usuario = :id_usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":informacion" => $informacion,
        ":id_usuario"  => $id_usuario
    ]);

    $texto_log = "El correo se ha cambiado";
} else {
    echo json_encode([
        "success" => false,
        "mensaje" => "Tipo de cambio no válido"
    ]);
    exit;
}

echo json_encode([
    "success"     => true,
    "mensaje"     => $texto_log,
    "tipoCambio"  => $tipo_cambio,
    "informacion" => $informacion
]);
