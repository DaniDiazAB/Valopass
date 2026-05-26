<?php
    $username = $_SESSION["usuario"] ?? ''; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/valopass/public/resources/favicon.ico">

    <title>Valopass - Gestor de cuentas de Valorant</title>
    <link rel="stylesheet" type="text/css" href="/valopass/public/assets/styles/style.css">

    <script>
        const usernameSesion = <?php echo json_encode($username); ?>;
        
    </script>
</head>
<body>
    <h1 id="title"><img class="logo" src="/valopass/public/resources/logo.png" alt="Valopass"></img></h1>
    
</body>
    <script src="/valopass/public/assets/js/script.js"></script>


</html>