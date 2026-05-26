<?php
session_start();

class ProfileController {
    private $pdo;
    private $userModel;
    private $loginUsername;
    private $profileUsername;

    public function __construct($pdo) {
        require_once __DIR__ . '/../models/User.php';
        
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
        $this->loginUsername = $_SESSION['usuario'] ?? '';
        $this->profileUsername = $_GET['user'] ?? '';
    }

    public function show() {
        $existe_perfil = true;
        $error = "";
        $usuario = null;
        $is_mismo_perfil = false;
        $is_cuenta_main = false;
        $id_usuario_perfil = null;
        $id_usuario_login = null;
        $total_cuentas_usuario = 0;
        $total_amigos_usuario = 0;
        $fecha_registro = '';
        $nombre = '';
        $tag = '';
        $elo = '';
        $lista_amistades = [];

        if (!empty($this->profileUsername)) {
            $usuario = $this->userModel->getUserByUsername($this->profileUsername);

            if (!$usuario) {
                $error = "Perfil no encontrado";
                $existe_perfil = false;
            } else {
                // Obtener IDs
                $id_usuario_perfil = $this->userModel->getUserIdByUsername($this->profileUsername);
                $id_usuario_login = $this->userModel->getUserIdByUsername($this->loginUsername);

                // Verificar si es el mismo perfil
                if ($id_usuario_perfil == $id_usuario_login) {
                    $is_mismo_perfil = true;
                }

                // Obtener datos del usuario
                $total_cuentas_usuario = $this->userModel->getTotalCuentas($id_usuario_perfil);
                $total_amigos_usuario = $this->userModel->getTotalAmigos($id_usuario_perfil);
                $fecha_registro = $this->userModel->getFechaRegistro($id_usuario_perfil);

                // Obtener cuenta main
                $resultado_main = $this->userModel->getCuentaMain($id_usuario_perfil);
                if ($resultado_main) {
                    $nombre = $resultado_main['nombre_cuenta_main'] ?? '';
                    $tag = $resultado_main['tag_cuenta_main'] ?? '';
                    $elo = $resultado_main['elo_cuenta_main'] ?? '';
                    if ($nombre !== '') {
                        $is_cuenta_main = true;
                    }
                }

                // Obtener lista de amigos (máximo 4)
                $lista_amistades = $this->userModel->getListaAmigos($id_usuario_perfil, 4);
            }
        } else {
            $error = "No se ha especificado ningún perfil.";
            $existe_perfil = false;
        }

        // Pasar variables a la vista
        require_once __DIR__ . '/../views/profile/perfil.php';
    }

    /**
     * Cambiar contraseña (AJAX)
     */
    public function cambiarPassword() {
        if (!isset($_SESSION['usuario_id'])) {
            return json_encode(['success' => false, 'mensaje' => 'No autorizado']);
        }

        $passwordActual = $_POST['password_actual'] ?? '';
        $passwordNueva = $_POST['password_nueva'] ?? '';
        $passwordConfirmar = $_POST['password_confirmar'] ?? '';

        if (empty($passwordActual) || empty($passwordNueva) || empty($passwordConfirmar)) {
            return json_encode(['success' => false, 'mensaje' => 'Todos los campos son requeridos']);
        }

        if ($passwordNueva !== $passwordConfirmar) {
            return json_encode(['success' => false, 'mensaje' => 'Las contraseñas no coinciden']);
        }

        $resultado = $this->userModel->cambiarPassword($_SESSION['usuario_id'], $passwordActual, $passwordNueva);
        return json_encode($resultado);
    }

    /**
     * Cambiar nombre de usuario (AJAX)
     */
    public function cambiarUsername() {
        if (!isset($_SESSION['usuario_id'])) {
            return json_encode(['success' => false, 'mensaje' => 'No autorizado']);
        }

        $nuevoUsername = $_POST['nuevo_usuario'] ?? '';
        $password = $_POST['password_confirmar'] ?? '';

        if (empty($nuevoUsername) || empty($password)) {
            return json_encode(['success' => false, 'mensaje' => 'Todos los campos son requeridos']);
        }

        $resultado = $this->userModel->cambiarUsername($_SESSION['usuario_id'], $nuevoUsername, $password);
        return json_encode($resultado);
    }

    /**
     * Cambiar email (AJAX)
     */
    public function cambiarEmail() {
        if (!isset($_SESSION['usuario_id'])) {
            return json_encode(['success' => false, 'mensaje' => 'No autorizado']);
        }

        $nuevoEmail = $_POST['nuevo_email'] ?? '';
        $password = $_POST['password_confirmar'] ?? '';

        if (empty($nuevoEmail) || empty($password)) {
            return json_encode(['success' => false, 'mensaje' => 'Todos los campos son requeridos']);
        }

        if (!filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL)) {
            return json_encode(['success' => false, 'mensaje' => 'Email inválido']);
        }

        $resultado = $this->userModel->cambiarEmail($_SESSION['usuario_id'], $nuevoEmail, $password);
        return json_encode($resultado);
    }

    /**
     * Obtener lista completa de amigos (AJAX)
     */
    public function getListaAmigosCompleta() {
        $id_usuario_perfil = $this->userModel->getUserIdByUsername($this->profileUsername);
        $lista_amigos = $this->userModel->getListaAmigos($id_usuario_perfil);
        return json_encode(['amigos' => $lista_amigos]);
    }
}
?>
