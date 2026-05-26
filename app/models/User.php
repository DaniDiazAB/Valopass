<?php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Obtener usuario por nombre de usuario
     */
    public function getUserByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener ID del usuario por nombre de usuario
     */
    public function getUserIdByUsername($username) {
        $stmt = $this->pdo->prepare('SELECT id_usuario AS id FROM usuarios WHERE nombre_usuario = :username');
        $stmt->execute(['username' => $username]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['id'] ?? null;
    }

    /**
     * Obtener total de cuentas del usuario
     */
    public function getTotalCuentas($idUsuario) {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) AS total FROM cuentas_usuarios WHERE id_usuario = ?'
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchColumn();
    }

    /**
     * Obtener total de amigos del usuario
     */
    public function getTotalAmigos($idUsuario) {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM usuarios_amigos WHERE id_usuario_uno_usuarios_amigos = ? OR id_usuario_dos_usuarios_amigos = ?'
        );
        $stmt->execute([$idUsuario, $idUsuario]);
        return $stmt->fetchColumn();
    }

    /**
     * Obtener fecha de registro del usuario
     */
    public function getFechaRegistro($idUsuario) {
        $stmt = $this->pdo->prepare(
            'SELECT registro_usuario AS fecha FROM usuarios WHERE id_usuario = ?'
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchColumn();
    }

    /**
     * Obtener cuenta main del usuario
     */
    public function getCuentaMain($idUsuario) {
        $stmt = $this->pdo->prepare(
            'SELECT nombre_cuenta_main, tag_cuenta_main, elo_cuenta_main 
            FROM cuenta_main 
            WHERE id_usuario_cuenta_main = ?'
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener lista de amigos del usuario
     */
    public function getListaAmigos($idUsuario, $limite = null) {
        $stmt = $this->pdo->prepare("
            SELECT u.nombre_usuario, cm.elo_cuenta_main
            FROM usuarios_amigos ua
            JOIN usuarios u 
                ON (u.id_usuario = ua.id_usuario_uno_usuarios_amigos 
                    OR u.id_usuario = ua.id_usuario_dos_usuarios_amigos)
            LEFT JOIN cuenta_main cm
                ON cm.id_usuario_cuenta_main = u.id_usuario
            WHERE (ua.id_usuario_uno_usuarios_amigos = ? 
                OR ua.id_usuario_dos_usuarios_amigos = ?)
            AND u.id_usuario != ?
        ");
        $stmt->execute([$idUsuario, $idUsuario, $idUsuario]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($limite) {
            return array_slice($resultado, 0, $limite);
        }
        return $resultado;
    }

    /**
     * Verificar si existe amistad
     */
    public function verificarAmistad($idUsuario1, $idUsuario2) {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM usuarios_amigos 
            WHERE (id_usuario_uno_usuarios_amigos = ? AND id_usuario_dos_usuarios_amigos = ?)
            OR (id_usuario_uno_usuarios_amigos = ? AND id_usuario_dos_usuarios_amigos = ?)'
        );
        $stmt->execute([$idUsuario1, $idUsuario2, $idUsuario2, $idUsuario1]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Cambiar contraseña del usuario
     */
    public function cambiarPassword($idUsuario, $passwordActual, $passwordNueva) {
        // Obtener contraseña actual
        $stmt = $this->pdo->prepare('SELECT password_usuario FROM usuarios WHERE id_usuario = ?');
        $stmt->execute([$idUsuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña actual
        if (!password_verify($passwordActual, $usuario['password_usuario'])) {
            return ['success' => false, 'mensaje' => 'La contraseña actual es incorrecta'];
        }

        // Actualizar contraseña
        $passwordHash = password_hash($passwordNueva, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare('UPDATE usuarios SET password_usuario = ? WHERE id_usuario = ?');
        if ($stmt->execute([$passwordHash, $idUsuario])) {
            return ['success' => true, 'mensaje' => 'Contraseña actualizada correctamente'];
        }
        return ['success' => false, 'mensaje' => 'Error al actualizar la contraseña'];
    }

    /**
     * Cambiar nombre de usuario
     */
    public function cambiarUsername($idUsuario, $nuevoUsername, $password) {
        // Verificar contraseña
        $stmt = $this->pdo->prepare('SELECT password_usuario FROM usuarios WHERE id_usuario = ?');
        $stmt->execute([$idUsuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($password, $usuario['password_usuario'])) {
            return ['success' => false, 'mensaje' => 'Contraseña incorrecta'];
        }

        // Verificar que el nuevo username no existe
        $stmt = $this->pdo->prepare('SELECT id_usuario FROM usuarios WHERE nombre_usuario = ?');
        $stmt->execute([$nuevoUsername]);
        if ($stmt->fetch()) {
            return ['success' => false, 'mensaje' => 'El nombre de usuario ya existe'];
        }

        // Actualizar username
        $stmt = $this->pdo->prepare('UPDATE usuarios SET nombre_usuario = ? WHERE id_usuario = ?');
        if ($stmt->execute([$nuevoUsername, $idUsuario])) {
            return ['success' => true, 'mensaje' => 'Nombre de usuario actualizado correctamente'];
        }
        return ['success' => false, 'mensaje' => 'Error al actualizar el nombre de usuario'];
    }

    /**
     * Cambiar email del usuario
     */
    public function cambiarEmail($idUsuario, $nuevoEmail, $password) {
        // Verificar contraseña
        $stmt = $this->pdo->prepare('SELECT password_usuario FROM usuarios WHERE id_usuario = ?');
        $stmt->execute([$idUsuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($password, $usuario['password_usuario'])) {
            return ['success' => false, 'mensaje' => 'Contraseña incorrecta'];
        }

        // Verificar que el nuevo email no existe
        $stmt = $this->pdo->prepare('SELECT id_usuario FROM usuarios WHERE email_usuario = ?');
        $stmt->execute([$nuevoEmail]);
        if ($stmt->fetch()) {
            return ['success' => false, 'mensaje' => 'El email ya está registrado'];
        }

        // Actualizar email
        $stmt = $this->pdo->prepare('UPDATE usuarios SET email_usuario = ? WHERE id_usuario = ?');
        if ($stmt->execute([$nuevoEmail, $idUsuario])) {
            return ['success' => true, 'mensaje' => 'Email actualizado correctamente'];
        }
        return ['success' => false, 'mensaje' => 'Error al actualizar el email'];
    }
}
?>
