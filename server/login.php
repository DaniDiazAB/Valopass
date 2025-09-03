<?php
session_start();
$host = "localhost";
$user = "root";  // cambia si tu host tiene otro usuario
$pass = "";      // pon tu contraseña de MySQL si tienes
$dbname = "valopass";


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $remember = isset($_POST["remember"]);

    // Buscar usuario en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario");
    $stmt->execute(["nombre_usuario" => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password_usuario"])) {
        // Cookie: 7 días si recordar, sino hasta cerrar navegador
        $cookieTime = $remember ? time() + (86400 * 7) : 0;
        setcookie("usuario", $username, $cookieTime, "/");

        header("Location: ../public/index.html");
        exit;
    } else {
        $_SESSION["error"] = "Usuario o contraseña incorrectos ❌";
        header("Location: ../public/views/login.html");
        exit;
    }
}
?>
