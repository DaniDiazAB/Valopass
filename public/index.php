<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: /valopass/login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/valopass/public/resources/favicon.ico">

    <title>Valopass - Gestor de cuentas de Valorant</title>
    <link rel="stylesheet" type="text/css" href="/valopass/public/style.css">
</head>
<body>
    <h1 id="title"><img class="logo" src="/valopass/public/resources/logo.png"></img></h1>
    
</body>
    <script src="/valopass/public/script.js"></script>


</html>