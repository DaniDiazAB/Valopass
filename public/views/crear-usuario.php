<?php
session_start();
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
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="/valopass/public/resources/favicon.ico">

  <title>Crear usuario - Valopass</title>
  <link rel="stylesheet" href="/valopass/public/views/styles/crear-cuenta.css">

</head>

<body>
  <img class="logo" src="/valopass/public/resources/logo.png"></img>
  <div id="div-crear-cuenta" class="crear-cuenta-container">
    <h2>Crear cuenta</h2>
    <form id="crear-cuenta" action="/valopass/server/registrar-usuario.php" method="POST">

      <div class="form-group">
        <label for="username">Nombre de usuario</label>
        <input type="text" id="username" name="username" placeholder="Tu usuario" value="<?= htmlspecialchars($usuario) ?>" required>
        <p id="mensaje-repetido" class="mensaje-pass"><?= htmlspecialchars($usuario_repetido) ?></p>
      </div>

      <div class="form-group" id="correo-form">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" class="email" placeholder="valopass@danidiaz.site" value="<?= htmlspecialchars($email) ?>" required>
      </div>

      <div class="form-group" id="login-form">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu contraseña" value="<?= htmlspecialchars($password) ?>" required>
        <span class="toggle-password" onclick="togglePassword()">👁️</span>
      </div>
      <p id="mensaje-segura" class="mensaje-pass" hidden>La contraseña debe incluir, al menos, una mayúscula, una
        minúscula y un carácter especial</p>


      <div class="form-group" id="login-form-confirmar">
        <label for="password-confirmar">Confirmar contraseña</label>
        <input type="password" id="password-confirmar" name="password-confirmar" placeholder="Confirmar contraseña" value="<?= htmlspecialchars($passwordConfirmar) ?>"
          required>
      </div>

      <div class="msg-err-pass">
        <p id="mensaje-pass" class="mensaje-pass" hidden>Las contraseñas no coinciden</p>
        <div>


          <button id="btn-crear-cuenta" type="submit" class="login-btn">Crear cuenta</button>
          <a href="/valopass/" id="tengo-cuenta" class="crear-cuenta" disabled>Ya tengo cuenta</a>
    </form>
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





    const btnForm = document.getElementById("btn-crear-cuenta")

    const passwordUno = document.getElementById("password")
    const passwordDos = document.getElementById("password-confirmar")

    const mensajePass = document.getElementById("mensaje-pass")
    const mensajeSegura = document.getElementById("mensaje-segura")

    function validarPasswords() {
      /*
      const passwordSegura = esPasswordSegura(passwordUno.value)
      
      if (passwordSegura) {
        if (passwordUno.value != passwordDos.value) {
          btnForm.disabled = true;
          mensajePass.removeAttribute("hidden")
        } else {
          btnForm.disabled = false;
          mensajePass.setAttribute("hidden", "")

        }
        mensajeSegura.setAttribute("hidden", "")
      } else {
        mensajeSegura.removeAttribute("hidden")
      }
        */
    } 

    function esPasswordSegura(password) {
      const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{6,}$/;
      return regex.test(password);
    }

    passwordUno.addEventListener("input", validarPasswords);
    passwordDos.addEventListener("input", validarPasswords);




  </script>
</body>

</html>