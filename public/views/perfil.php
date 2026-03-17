<?php
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

$username = $_GET['user'] ?? '';
$loginUsername = $_SESSION['usuario'] ?? '';
$existe_perfil = true;
$is_mismo_perfil = false;
$is_cuenta_main = false;
$usuario = null;
$error = "";

if (!empty($username)) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ?");
    $stmt->execute([$username]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $error = "Perfil no encontrado";
        $existe_perfil = false;
    } else {

        // conseguir el ID del perfil
        $stmt = $pdo->prepare('SELECT id_usuario AS id FROM usuarios WHERE nombre_usuario = :username');
        $stmt->execute(['username' => $username]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_usuario_perfil = $resultado['id'];

        // conseguir el ID del perfil del usuario
        $stmt = $pdo->prepare('SELECT id_usuario AS id FROM usuarios WHERE nombre_usuario = :username');
        $stmt->execute(['username' => $loginUsername]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_usuario_login = $resultado['id'];

        if ($id_usuario_perfil == $id_usuario_login) {
            $is_mismo_perfil = true;
        }

        // total de las cuentas del usuario
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) AS total FROM cuentas_usuarios WHERE id_usuario = ?'
        );
        $stmt->execute([$id_usuario_perfil]);
        $total_cuentas_usuario = $stmt->fetchColumn();

        // total de los amigos del usuario
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM usuarios_amigos WHERE id_usuario_uno_usuarios_amigos = ? OR id_usuario_dos_usuarios_amigos = ?'
        );
        $stmt->execute([$id_usuario_perfil, $id_usuario_perfil]);
        $total_amigos_usuario = $stmt->fetchColumn();

        // conseguir la fecha de registro del usuario
        $stmt = $pdo->prepare(
            'SELECT registro_usuario AS fecha FROM usuarios WHERE id_usuario = ?'
        );
        $stmt->execute([$id_usuario_perfil]);
        $fecha_registro = $stmt->fetchColumn();

        // conseguir la cuenta main del usuario
        $stmt = $pdo->prepare(
            'SELECT nombre_cuenta_main, tag_cuenta_main, elo_cuenta_main 
            FROM cuenta_main 
            WHERE id_usuario_cuenta_main = ?'
        );
        $stmt->execute([$id_usuario_perfil]);
        $resultado_main = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado_main) {
            $nombre = $resultado_main['nombre_cuenta_main'] ?? '';
            $tag = $resultado_main['tag_cuenta_main'] ?? '';
            $elo = $resultado_main['elo_cuenta_main'] ?? '';
            if ($nombre !== '') {
                $is_cuenta_main = true;
            }
        }

        // conseguir ids de amistades del usuario
        $stmt = $pdo->prepare(
            'SELECT * FROM usuarios_amigos where id_usuario_uno_usuarios_amigos = ? OR id_usuario_dos_usuarios_amigos = ?'
        );
        $stmt->execute([$id_usuario_perfil, $id_usuario_perfil]);
        $lista_amistades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // conseguir nombres de amistades del usuario
        $stmt = $pdo->prepare("SELECT u.nombre_usuario, cm.elo_cuenta_main
                                FROM usuarios_amigos ua
                                JOIN usuarios u 
                                    ON (u.id_usuario = ua.id_usuario_uno_usuarios_amigos 
                                        OR u.id_usuario = ua.id_usuario_dos_usuarios_amigos)
                                LEFT JOIN cuenta_main cm
                                    ON cm.id_usuario_cuenta_main = u.id_usuario
                                WHERE (ua.id_usuario_uno_usuarios_amigos = ? 
                                    OR ua.id_usuario_dos_usuarios_amigos = ?)
                                AND u.id_usuario != ?");

        $stmt->execute([$id_usuario_perfil, $id_usuario_perfil, $id_usuario_perfil]);
        $lista_amistades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    $error = "No se ha especificado ningún perfil.";
    $existe_perfil = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/valopass/public/resources/favicon.ico">

    <title>Valopass - Gestor de cuentas de Valorant</title>
    <link rel="stylesheet" type="text/css" href="/valopass/public/style.css">

    <script>
        window.usernameSesion = <?php echo json_encode($loginUsername); ?>;
        window.nombreCuenta = <?php echo json_encode($nombre ?? ""); ?>;
        window.tagCuenta = <?php echo json_encode($tag ?? ""); ?>;
        window.idPerfilUsuario = "<?php echo $id_usuario_perfil; ?>";
        window.idUsuarioLogin = "<?php echo $id_usuario_login; ?>";
        window.totalAmigos = "<?php echo $total_amigos_usuario; ?>";
        window.listaAmistades = <?php echo json_encode($lista_amistades); ?>;

    </script>
</head>

<body>
    <h1 id="title"><img class="logo" src="/valopass/public/resources/logo.png" alt="Valopass"></img></h1>
    <?php if ($existe_perfil): ?>
        <div id="div-perfil" class="div-perfil">
            <h1 id="username-perfil"><?php echo ($username); ?></h1>
            <div class="div-perfil">
                <div class="div-perfil-especializacion" id="div-estadisticas">
                    <h2 class="titulo-perfil">Estadísticas</h2>
                    <h4 class="div-detalle-perfil" id="elo-mayor">El usuario: <?php echo $username; ?></h4>
                    <h4 class="div-detalle-perfil" id="elo-menor">No tiene cuentas agregadas.</h4>
                    <h4 class="div-detalle-perfil" id="total-cuentas">Tiene un total de <?php echo $total_cuentas_usuario ?> cuenta(s)</h4>
                    <h4 class="div-detalle-perfil">Miembro desde: <?php echo $fecha_registro ?></h4>
                    <?php if ($is_cuenta_main): ?>

                        <h2>Cuenta Main</h2>
                        <div>
                            <p><?php echo $nombre; ?> #<?php echo $tag; ?></p>
                            <p>ELO: <?php echo $elo; ?></p>
                        </div>

                        <button id="tracker">Ver Tracker</button>
                        <?php if ($id_usuario_login == $id_usuario_perfil): ?>

                            <button id="actualizar-elo">Actualizar ELO cuenta</button>
                            <button id="editar-cuenta">Editar cuenta</button>
                            <button id="eliminar-cuenta">Eliminar cuenta</button>

                        <?php endif ?>
                    <?php else: ?>
                        <?php if ($id_usuario_login == $id_usuario_perfil): ?>
                            <div class="div-perfil">
                                <button id="agregar-main">Añadir Main</button>
                            </div>
                        <?php endif ?>

                    <?php endif ?>


                </div>

                <div class="div-perfil-especializacion" id="div-amigos">
                    <h2 class="titulo-perfil">Amigos</h2>
                    <h4 class="div-detalle-perfil" id="total-amigos">Tiene <?php echo $total_amigos_usuario ?> amigo(s)</h4>
                    <div class="div-amigos">
                        <div class="div-perfil-especializacion">

                            <h2 class="lista-amigos">Lista de amigos</h2>
                            <ul class="user-list">
                                <?php
                                $limite = 4;
                                $contador = 0;

                                foreach ($lista_amistades as $amistad) {

                                    if ($contador >= $limite) {
                                        break;
                                    }

                                    $nombre = htmlspecialchars($amistad['nombre_usuario']);
                                    $elo = htmlspecialchars($amistad['elo_cuenta_main'] ?? 'Sin ELO');

                                    echo "
                                    <li class='user-item'>
                                        <div class='user-info'>
                                            <p id='amistad-$nombre'><strong>Nombre de usuario:</strong><a href='/valopass/$nombre'> $nombre</a>
                                            <p><strong>ELO del usuario:</strong> $elo</p>
                                        </div>
                                    </li>
                                    ";

                                    $contador++;
                                }
                                ?>
                            </ul>
                            <div class="div-perfil-especializacion">
                                <h3 class="link-agregar-amigo" id="ver-todos-amigos">Ver todos los amigos</h3>
                            </div>
                        </div>
                        <?php if ($id_usuario_login != $id_usuario_perfil): ?>

                            <div class="div-perfil-especializacion">
                                <h3 id="agregar-amigo" id="agregar-amigo" class="link-agregar-amigo">Añadir amigo</h3>
                            </div>

                            <div class="div-perfil-especializacion">
                                <h3 id="bloquear-usuario" id="bloquear-usuario" class="link-eliminar-amigo">Bloquear usuario</h3>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>

        </div>
    <?php else: ?>
        <div class="div-perfil">
            <h1>"Perfil no encontrado"</h1>
        </div>

    <?php endif; ?>


    <div id="modal-confirmacion" class="modal"></div>

    <div id="modal-amigos" class="modal"></div>

</body>
<script src="/valopass/public/views/script.js">


</script>

</html>