<?php
session_start();

class CreateUserController {
    
    public static function index() {
        $usuario_repetido = "";

        $usuario = $_SESSION['usuario'] ?? '';
        $email = $_SESSION['email'] ?? '';
        $password = $_SESSION['password'] ?? '';
        $passwordConfirmar = $_SESSION['passwordConfirmar'] ?? '';
        $usuario_repetido = $_SESSION['usuarioRepetido'] ?? '';

        unset($_SESSION['usuario']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        unset($_SESSION['passwordConfirmar']);
        unset($_SESSION['usuarioRepetido']);

        if (isset($_SESSION["usuario_id"])) {
            header("Location: /valopass/");
            exit;
        }
        
        require_once __DIR__ . '/../views/create/create-user.php';
    }
    
}

?>