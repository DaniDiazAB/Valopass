<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/secret.php';
require_once __DIR__ . '/../controllers/ProfileController.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'mensaje' => 'No autorizado']);
    exit;
}

$profileController = new ProfileController($pdo);
$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'cambiar-password':
        echo $profileController->cambiarPassword();
        break;

    case 'cambiar-username':
        echo $profileController->cambiarUsername();
        break;

    case 'cambiar-email':
        echo $profileController->cambiarEmail();
        break;

    case 'lista-amigos-completa':
        echo $profileController->getListaAmigosCompleta();
        break;

    default:
        echo json_encode(['success' => false, 'mensaje' => 'Acción no reconocida']);
        break;
}
?>
