<?php
require __DIR__ . '/../vendor/autoload.php'; // autoload de Composer
require_once "db.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'] ?? '';

$password = generarPassword();
$passwordHash = password_hash($password, PASSWORD_BCRYPT);


$subject = "Se ha solicitado un cambio en tu contraseña";
$mensaje = "Su nueva contraseña es: " . $password;
$altMensaje = "Su nueva contraseña es: " . $password;
$toEmail = $email;

$sql = "SELECT id_usuario FROM usuarios WHERE correo_usuario = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([":email" => $email]);
$id_cuenta = $stmt->fetchColumn();

$sql = "UPDATE usuarios SET password_usuario = :passwordHash WHERE id_usuario = :id_usuario";
$stmt = $pdo->prepare($sql);
$stmt->execute([
   ":passwordHash" => $passwordHash,
   ":id_usuario" => $id_cuenta
]);

function generarPassword() {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=';
    return substr(str_shuffle($caracteres), 0, 12);
}

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = ''; // Poner tu smpt
    $mail->SMTPAuth   = true;
    $mail->Username   = ''; // Poner tu correo electronico
    $mail->Password   = 'iO3S4DitSg@_'; // Poner password del correo
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Port       = 587; 

    $mail->setFrom('', 'Tu nueva contraseña'); // Poner desde donde se envia
    $mail->addAddress($toEmail);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $mensaje;
    $mail->AltBody = $altMensaje;
    echo $password;
    $mail->send();

    header("Location: /valopass/");
} catch (Exception $e) {
    echo "Fallo: {$mail->ErrorInfo}";
}
