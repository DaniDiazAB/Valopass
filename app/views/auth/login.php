<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="/valopass/public/resources/favicon.ico">

  <title>Login - Valopass</title>
  <link rel="stylesheet" href="/valopass/public/assets/styles/login.css">
  

</head>

<body>
  <div id="div-login" class="login-container">
    <img class="logo" src="/valopass/public/resources/logo.png"></img>
    
    <form id="login" action="/valopass/server/login.php" method="POST">
      <div class="form-group">
        <label for="username">Nombre de usuario</label>
        <input type="text" id="username" name="username" placeholder="Tu usuario" value="" required>
      </div>

      <div class="form-group" id="login-form">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
        <span class="toggle-password" onclick="togglePassword()">👁️</span>
      </div>

      <div class="msg-err-login">
        <p class="mensaje-login"></p>
      <div>

      <div class="form-group recordar-sesion">
        <label>
          <input type="checkbox" name="recordar" id="recordar">
          Recordar sesión
        </label>
      </div>

      <button id="btn-login" type="submit" class="login-btn">Entrar</button>
      <a href="/valopass/recuperar" id="password-olvidada" class="form-btn">¿Has olvidado la contraseña?</a>
      <a href="/valopass/crear-usuario" id="crear-usuario" class="form-btn">¿No eres usuario?</a>
    </form>
  </div>

  <div id="cookie-banner" class="cookie-banner">
    <p>
      Solo se usan cookies propias y esenciales para el uso de la aplicación.
    </p>
    <div class="cookie-buttons">
      <a href="/valopass/aviso" class="aviso-legal">AVISO LEGAL</a>
    </div>
  </div>

</body>

</html>