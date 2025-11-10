<?php
session_start();

$usuario = $_SESSION['usuario'] ?? '';
$mensaje = $_SESSION['mensaje'] ?? '';

unset($_SESSION['usuario']);
unset($_SESSION['mensaje']);

if (isset($_SESSION["usuario_id"])) {
    header("Location: /valopass/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="/valopass/public/resources/favicon.ico">

  <title>Login - Valopass</title>
  <link rel="stylesheet" href="/valopass/public/views/styles/login.css">

</head>

<body>
  <img class="logo" src="/valopass/public/resources/logo.png"></img>
  <div id="div-login" class="login-container">
    <h2>Iniciar Sesión</h2>
    <form id="login" action="/valopass/server/login.php" method="POST">
      <div class="form-group">
        <label for="username">Nombre de usuario</label>
        <input type="text" id="username" name="username" placeholder="Tu usuario" value="<?= htmlspecialchars($usuario) ?>" required>
      </div>

      <div class="form-group" id="login-form">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
        <span class="toggle-password" onclick="togglePassword()">👁️</span>
      </div>

      <div class="msg-err-login">
        <p class="mensaje-login"><?= htmlspecialchars($mensaje) ?></p>
      <div>

      <button id="btn-login" type="submit" class="login-btn">Entrar</button>
      <a href="/valopass/aviso" class="forgot-password">AVISO LEGAL</a>
    </form>
  </div>

  <div id="cookie-banner" class="cookie-banner">
    <p>
      Utilizamos cookies propias para analizar el tráfico y mejorar tu experiencia.
      Si no las aceptas no podrás usar la aplicación.
    </p>
    <div class="cookie-buttons">
      <button id="acceptCookies" class="cookie-btn aceptar">Aceptar</button>
      <button id="rejectCookies" class="cookie-btn rechazar">Rechazar</button>
    </div>
  </div>

  <script>
    

    function togglePassword() {
      const passwordInput = document.getElementById("password");
      const toggle = document.querySelector(".toggle-password");
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggle.textContent = "🙈";
      } else {
        passwordInput.type = "password";
        toggle.textContent = "👁️";
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      const banner = document.getElementById("cookie-banner");
      const acceptBtn = document.getElementById("acceptCookies");
      const rejectBtn = document.getElementById("rejectCookies");

      const consent = localStorage.getItem("cookieConsent");

      if (!consent) banner.classList.remove("hidden");

      acceptBtn.addEventListener("click", () => {
        localStorage.setItem("cookieConsent", "accepted");
        banner.classList.add("hidden");
      });

      rejectBtn.addEventListener("click", () => {
        localStorage.setItem("cookieConsent", "rejected");
        banner.classList.add("hidden");
        const divLogin = document.getElementById("div-login")
        divLogin.setAttribute("hidden", "")
      });
    });

  </script>
</body>

</html>