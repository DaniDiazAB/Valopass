<?php
session_start();
include "db.php";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario");
    $stmt->execute([":nombre_usuario" => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password_usuario"])) {
        // Guardar en sesión
        $_SESSION["usuario_id"] = $user["id_usuario"];
        $_SESSION["usuario"] = $username;
        header("Location: ../public/index.php");
        exit;
    } else {
        $_SESSION["error"] = "Usuario o contraseña incorrectos ❌";
        header("Location: ../public/views/login.html");
        exit;
    }
}
?>

