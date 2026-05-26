<?php
require_once __DIR__ . '/../config/bbdd.php';

// Perfil por username
if (isset($_GET['user'])) {
    require_once __DIR__ . '/../app/controllers/ProfileController.php';
    $controller = new ProfileController($pdo);
    $controller->show();
    exit;
}

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$uri = preg_replace('#^valopass/?#', '', $uri);

$routes = [
    ''               => 'IndexController',
    'login'          => 'LoginController',
    'crear-usuario'  => 'CreateUserController',
];

if (array_key_exists($uri, $routes)) {
    $controllerName = $routes[$uri];

    require_once __DIR__ . "/../app/controllers/{$controllerName}.php";

    $controller = new $controllerName($pdo);
    $controller->index();
} else {
    http_response_code(404);
    require_once __DIR__ . '/../app/views/static/error.php';
}