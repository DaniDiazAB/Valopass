<?php

session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";
require_once "../config/secret.php";

// Actualizador de los rangos de las cuentas
$sql = "SELECT id_cuenta, nick_cuenta, tag_cuenta FROM cuentas";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$cuentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$resultados = [];
foreach ($cuentas as $cuenta) {
    $nick = rawurlencode($cuenta['nick_cuenta']);
    $tag = rawurlencode($cuenta['tag_cuenta']);
    $url = "https://vaccie.pythonanywhere.com/mmr/$nick/$tag/eu";
    $respuesta = @file_get_contents($url);
    $rango = null;

    if ($respuesta !== false) {
        $texto = strip_tags($respuesta);
        if (preg_match('/^(.*?)\,/', $texto, $matches)) {
            $rango = trim($matches[1]);
        }
    }

    $resultados[] = [
        "id_cuenta" => $cuenta['id_cuenta'],
        "nick_cuenta" => $cuenta['nick_cuenta'],
        "tag_cuenta" => $cuenta['tag_cuenta'],
        "rango" => $rango
    ];

    $sql = "UPDATE cuentas 
            SET rango_cuenta = :rango
            WHERE nick_cuenta = :nick_cuenta
            AND tag_cuenta = :tag_cuenta";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":nick_cuenta" => $cuenta['nick_cuenta'],
        ":tag_cuenta" => $cuenta['tag_cuenta'],
        ":rango" => $rango,
    ]);


    // Actualizador de mayor y menor elo de las cuentas 
    $stmtUsuarios = $pdo->query("SELECT id_usuario FROM usuarios");
    $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

     foreach ($usuarios as $usuario) {
        $idUsuario = $usuario['id_usuario'];

        $stmtMayor = $pdo->prepare("
            SELECT cu.id_cuenta
            FROM cuentas_usuarios cu
            JOIN cuentas c ON cu.id_cuenta = c.id_cuenta
            JOIN rango_nivel rn ON c.rango_cuenta = rn.nombre_rango_nivel
            WHERE cu.id_usuario = :id_usuario
            ORDER BY 
                (rn.nivel_rango_nivel = 26) ASC,
                rn.nivel_rango_nivel DESC
            LIMIT 1
        ");
        $stmtMayor->execute(['id_usuario' => $idUsuario]);
        $cuentaMayor = $stmtMayor->fetch(PDO::FETCH_ASSOC);

        $stmtMenor = $pdo->prepare("
            SELECT cu.id_cuenta
            FROM cuentas_usuarios cu
            JOIN cuentas c ON cu.id_cuenta = c.id_cuenta
            JOIN rango_nivel rn ON c.rango_cuenta = rn.nombre_rango_nivel
            WHERE cu.id_usuario = :id_usuario
            ORDER BY rn.nivel_rango_nivel ASC
            LIMIT 1
        ");
        $stmtMenor->execute(['id_usuario' => $idUsuario]);
        $cuentaMenor = $stmtMenor->fetch(PDO::FETCH_ASSOC);

        $stmtReset = $pdo->prepare("
            UPDATE cuentas_usuarios
            SET is_mayor_elo = 0,
                is_menor_elo = 0
            WHERE id_usuario = :id_usuario
        ");
        $stmtReset->execute(['id_usuario' => $idUsuario]);

        if ($cuentaMayor) {
            $stmtUpdateMayor = $pdo->prepare("
                UPDATE cuentas_usuarios
                SET is_mayor_elo = 1
                WHERE id_usuario = :id_usuario
                AND id_cuenta = :id_cuenta
            ");
            $stmtUpdateMayor->execute([
                'id_usuario' => $idUsuario,
                'id_cuenta' => $cuentaMayor['id_cuenta']
            ]);
        }

        if ($cuentaMenor) {
            $stmtUpdateMenor = $pdo->prepare("
                UPDATE cuentas_usuarios
                SET is_menor_elo = 1
                WHERE id_usuario = :id_usuario
                AND id_cuenta = :id_cuenta
            ");
            $stmtUpdateMenor->execute([
                'id_usuario' => $idUsuario,
                'id_cuenta' => $cuentaMenor['id_cuenta']
            ]);
        }
    }

}

echo json_encode([
    "status" => "ok",
    "resultados" => $resultados,

]);
