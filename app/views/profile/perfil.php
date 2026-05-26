<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/valopass/public/resources/favicon.ico">

    <title>Valopass - Gestor de cuentas de Valorant</title>
    <link rel="stylesheet" type="text/css" href="/valopass/public/assets/styles/style.css">
</head>

<body>
    <h1 id="title">
        <img class="logo" src="/valopass/public/resources/logo.png" alt="Valopass">
    </h1>

    <?php if ($existe_perfil ?? false): ?>
        <div id="div-perfil" class="div-perfil">
            <h1 id="username-perfil"><?php echo htmlspecialchars($this->profileUsername); ?></h1>
            
            <div class="div-perfil">
                <!-- Sección de Estadísticas -->
                <div class="div-perfil-especializacion" id="div-estadisticas">
                    <h2 class="titulo-perfil">Estadísticas</h2>
                    <h4 class="div-detalle-perfil" id="elo-mayor">El usuario: <?php echo htmlspecialchars($this->profileUsername); ?></h4>
                    <h4 class="div-detalle-perfil" id="elo-menor">No tiene cuentas agregadas.</h4>
                    <h4 class="div-detalle-perfil" id="total-cuentas">Tiene un total de <?php echo $total_cuentas_usuario  ?? 0; ?> cuenta(s)</h4>
                    <h4 class="div-detalle-perfil">Miembro desde: <?php echo htmlspecialchars($fecha_registro ?? ''); ?></h4>

                    <?php if ($is_cuenta_main ?? false): ?>
                        <h2>Cuenta Main</h2>
                        <div>
                            <p><?php echo htmlspecialchars($nombre ?? ''); ?> #<?php echo htmlspecialchars($tag ?? ''); ?></p>
                            <p>ELO: <?php echo htmlspecialchars($elo ?? ''); ?></p>
                        </div>

                        <button id="tracker" class="btn-perfil">Ver Tracker</button>
                        
                        <?php if ($is_mismo_perfil ?? false): ?>
                            <button id="actualizar-elo" class="btn-perfil">Actualizar ELO cuenta</button>
                            <button id="editar-cuenta" class="btn-perfil">Editar cuenta</button>
                            <button id="eliminar-cuenta" class="btn-perfil">Eliminar cuenta</button>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($is_mismo_perfil ?? false): ?>
                            <div class="div-perfil">
                                <button id="agregar-main" class="btn-perfil">Añadir Main</button>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Sección de Amigos -->
                <div class="div-perfil-especializacion" id="div-amigos">
                    <h2 class="titulo-perfil">Amigos</h2>
                    <h4 class="div-detalle-perfil" id="total-amigos">Tiene <?php echo $total_amigos_usuario  ?? 0; ?> amigo(s)</h4>
                    
                    <div class="div-amigos">
                        <div class="div-perfil-especializacion">
                            <h2 class="lista-amigos">Lista de amigos</h2>
                            <ul class="user-list">
                                <?php
                                foreach ($lista_amistades ?? [] as $amistad) {
                                    $nombre_amigo = htmlspecialchars($amistad['nombre_usuario']);
                                    $elo_amigo = htmlspecialchars($amistad['elo_cuenta_main'] ?? 'Sin ELO');

                                    echo "
                                    <li class='user-item'>
                                        <div class='user-info'>
                                            <p id='amistad-{$nombre_amigo}'>
                                                <strong>Nombre de usuario:</strong>
                                                <a href='/valopass/{$nombre_amigo}'> {$nombre_amigo}</a>
                                            </p>
                                            <p><strong>ELO del usuario:</strong> {$elo_amigo}</p>
                                        </div>
                                    </li>
                                    ";
                                }
                                ?>
                            </ul>
                            <div class="div-perfil-especializacion">
                                <h3 class="link-agregar-amigo" id="ver-todos-amigos">Ver todos los amigos</h3>
                            </div>
                        </div>

                        <?php if (!($is_mismo_perfil ?? false)): ?>
                            <div class="div-perfil-especializacion">
                                <h3 id="agregar-amigo" class="link-agregar-amigo">Añadir amigo</h3>
                            </div>

                            <div class="div-perfil-especializacion">
                                <h3 id="bloquear-usuario" class="link-eliminar-amigo">Bloquear usuario</h3>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($is_mismo_perfil ?? false): ?>
        <div id="div-usuario" class="div-perfil">
            <h1 id="datos-usuario">Mi cuenta</h1>

            <div class="div-perfil-especializacion">
                <h2 class="titulo-perfil">Cambiar contraseña</h2>

                <form id="form-cambiar-password" class="form-perfil" onsubmit="return false;">
                    <div class="form-group">
                        <label for="password_actual">Contraseña actual</label>
                        <input type="password" id="password_actual" name="password_actual" required>
                    </div>

                    <div class="form-group">
                        <label for="password_nueva">Nueva contraseña</label>
                        <input type="password" id="password_nueva" name="password_nueva" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmar">Confirmar nueva contraseña</label>
                        <input type="password" id="password_confirmar" name="password_confirmar" required>
                    </div>

                    <button id="btn-cambiar-password" type="submit" class="btn-perfil">Cambiar contraseña</button>
                </form>
            </div>

            <div class="div-perfil-especializacion">
                <h2 class="titulo-perfil">Cambiar nombre de usuario</h2>

                <form id="form-cambiar-username" class="form-perfil" onsubmit="return false;">
                    <div class="form-group">
                        <label for="username_nuevo">Nuevo nombre de usuario</label>
                        <input type="text" id="username_nuevo" name="nuevo_usuario" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmar_usuario">Confirma tu contraseña</label>
                        <input type="password" id="password_confirmar_usuario" name="password_confirmar" required>
                    </div>

                    <button id="btn-cambiar-username" type="submit" class="btn-perfil">Cambiar nombre de usuario</button>
                </form>
            </div>

            <div class="div-perfil-especializacion">
                <h2 class="titulo-perfil">Cambiar correo</h2>

                <form id="form-cambiar-email" class="form-perfil" onsubmit="return false;">
                    <div class="form-group">
                        <label for="email_nuevo">Nuevo email</label>
                        <input type="email" id="email_nuevo" name="nuevo_email" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmar_email">Confirma tu contraseña</label>
                        <input type="password" id="password_confirmar_email" name="password_confirmar" required>
                    </div>

                    <button id="btn-cambiar-email" type="submit" class="btn-perfil">Cambiar email</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="div-perfil">
            <h1>Perfil no encontrado</h1>
            <p><?php echo htmlspecialchars($error ?? 'Perfil no encontrado'); ?></p>
        </div>
    <?php endif; ?>

    <div id="modal-confirmacion" class="modal"></div>
    <div id="modal-amigos" class="modal"></div>

    <script>
        window.usernameSesion = <?php echo json_encode($this->loginUsername); ?>;
        window.nombreCuenta = <?php echo json_encode($nombre ?? ""); ?>;
        window.tagCuenta = <?php echo json_encode($tag ?? ""); ?>;
        window.idPerfilUsuario = "<?php echo $id_usuario_perfil ?? 0; ?>";
        window.idUsuarioLogin = "<?php echo $id_usuario_login  ?? 0; ?>";
        window.totalAmigos = "<?php echo $total_amigos_usuario ?? 0; ?>";
        window.listaAmistades = <?php echo json_encode($lista_amistades ?? []); ?>;
    </script>
    <script src="/valopass/public/assets/js/profile.js"></script>
</body>

</html>
