<?php
    setcookie("usuario", "", time() - 3600, "/");
    setcookie("PHPSESSID", "", time() - 3600, "/");

    unset($_SESSION['usuario']);

    session_start();
    session_unset();
    session_destroy(); 
    echo json_encode(["status" => "ok", "msg" => "Sesión cerrada"]);
?>