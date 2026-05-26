<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="/valopass/public/resources/favicon.ico">

  <title>Crear usuario - Valopass</title>
  <link rel="stylesheet" href="/valopass/public/views/styles/crear-usuario.css">

</head>

<body>
  <div id="div-crear-cuenta" class="crear-cuenta-container">
    <img class="logo" src="/valopass/public/resources/logo.png"></img>
    <form id="crear-cuenta" action="/valopass/server/set-new-user.php" method="POST">

      <div class="form-group">
        <label for="username">Nombre de usuario</label>
        <input type="text" id="username" name="username" placeholder="Tu usuario" value="<?= /*htmlspecialchars($usuario) */ '' ?>" required>
        <p id="mensaje-repetido" class="mensaje-pass"><?= /*htmlspecialchars($usuario_repetido) */ '' ?></p>
      </div>

      <div class="form-group" id="correo-form">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" class="email" placeholder="valopass@danidiaz.site" value="<?= /*htmlspecialchars($email) */ '' ?>" required>
      </div>

      <div class="form-group" id="login-form">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Tu contraseña" value="<?= /*htmlspecialchars($password) */ '' ?>" required>
        <span class="toggle-password" onclick="togglePassword()">👁️</span>
      </div>
      <p id="mensaje-segura" class="mensaje-pass" hidden>La contraseña debe incluir, al menos, una mayúscula, una
        minúscula y un carácter especial</p>


      <div class="form-group" id="login-form-confirmar">
        <label for="password-confirmar">Confirmar contraseña</label>
        <input type="password" id="password-confirmar" name="password-confirmar" placeholder="Confirmar contraseña" value="<?= /*htmlspecialchars($passwordConfirmar) */ '' ?>"
          required>
      </div>

      <div class="msg-err-pass">
        <p id="mensaje-pass" class="mensaje-pass" hidden>Las contraseñas no coinciden</p>
        <div>


          <button id="btn-crear-cuenta" type="submit" class="login-btn">Crear cuenta</button>
          <a href="/valopass/" id="tengo-cuenta" class="crear-cuenta" disabled>Ya tengo cuenta</a>
    </form>
  </div>

    <script src="/valopass/public/assets/js/create-user.js"></script>

</body>

</html>