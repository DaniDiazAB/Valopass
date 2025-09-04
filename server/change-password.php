
<?php
/*
session_start();
$host = "localhost";
$user = "root";  
$pass = "";      
$dbname = "valopass";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["nombre_usuario"] ?? "");
    $newPassword = $_POST["nueva_password"] ?? "";

    if ($username && $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE usuarios SET password_usuario = :password WHERE nombre_usuario = :username");
        $stmt->execute([
            "password" => $hashed,
            "username" => $username
        ]);

        if ($stmt->rowCount() > 0) {
            $_SESSION["success"] = "Contraseña cambiada correctamente ✅";
            header("Location: ../public/views/login.html");
            exit;
        } else {
            $_SESSION["error"] = "Usuario no encontrado ❌";
            header("Location: ../public/views/forgot_password.html");
            exit;
        }
    }
}
*/
?>
