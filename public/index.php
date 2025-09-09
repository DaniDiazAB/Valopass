<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    echo json_encode([
        "status" => "error",
        "msg" => "No has iniciado sesión"
    ]);
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valopass</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1 id="title">Valopass</h1>
    
</body>
    <script src="script.js"></script>

</html>