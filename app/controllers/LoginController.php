<?php
session_start();
$username = $_SESSION['usuario'] ?? '';

class LoginController {
    public function index() {
        require_once __DIR__ . '/../views/auth/login.php';
    }
}

if (isset($_SESSION["usuario_id"])) {
    header("Location: /valopass/login");
    exit;
}


?>