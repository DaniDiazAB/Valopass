<?php
session_start();

header("Content-Type: text/html; charset=UTF-8");
require_once "db.php";


$username     = $_POST['username'] ?? '';
$email      = $_POST['email'] ?? '';
$passwordCuenta = $_POST['password'] ?? '';


try {

    $sql = "SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $existe = $stmt->fetchColumn();

    if ($existe > 0) {
        echo "Este nombre de usuario ya está en uso.";
        $_SESSION["usuario"] = $username;
        $_SESSION["email"] = $email;
        $_SESSION["password"] = $passwordCuenta;
        $_SESSION["passwordConfirmar"] = $passwordCuenta;
        $_SESSION["usuarioRepetido"] = "El nombre de usuario ya existe";


        header("Location: /valopass/crear-usuario");
        exit;

    }else{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $passwordHash = password_hash($passwordCuenta, PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (nombre_usuario, password_usuario, correo_usuario) VALUES (:username, :password, :email)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':password' => $passwordHash,
        ':email'    => $email,
    ]);
        header("Location: /valopass/");

    }

} catch(PDOException $e)  {
   // echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}




?>
