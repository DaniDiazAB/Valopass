<?php
require_once "db.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

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
    $rango = $partes[0]; // "Gold 3"

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

