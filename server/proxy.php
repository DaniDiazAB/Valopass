<?php
require_once "db.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
/*
$sql = "SELECT nick_cuenta, tag_cuenta from cuentas";
$stmt = $pdo->prepare($sql);
$stmt->execute([ ]);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultados as $fila) {

    $nombre_cuenta = $fila['nick_cuenta'];
    $tag_cuenta = $fila['tag_cuenta'];
    $url = "https://vaccie.pythonanywhere.com/mmr/$nombre_cuenta/$tag_cuenta/eu";
    $response = file_get_contents($url);

    $partes = explode(",", $response);
    $rango = $partes[0]; 

    if ($response === FALSE) {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo conectar al servidor remoto"]);
    } else {    
        $sql = "UPDATE cuentas 
                SET rango_cuenta = :rango
                WHERE nick_cuenta = :nick AND tag_cuenta = :tag";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":nick" => $nombre_cuenta,
            ":tag" => $tag_cuenta,
            ":rango"    => $rango
        ]);

        echo json_encode($rango);




    }

}
*/


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmtUsuarios = $pdo->query("SELECT id_usuario FROM usuarios");
    $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

    foreach ($usuarios as $usuario) {

        $idUsuario = $usuario['id_usuario'];

        // 🔝 Mayor elo
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

        // 🔻 Menor elo
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

        // Resetear todo
        $stmtReset = $pdo->prepare("
            UPDATE cuentas_usuarios
            SET is_mayor_elo = 0,
                is_menor_elo = 0
            WHERE id_usuario = :id_usuario
        ");
        $stmtReset->execute(['id_usuario' => $idUsuario]);

        // Marcar mayor
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

        // Marcar menor
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

    echo "Mayor y menor elo actualizados correctamente.";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
