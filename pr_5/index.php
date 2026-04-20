<?php
session_start();

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}
require_once __DIR__ . '/Controllers/BaseController.php';
require_once __DIR__ . '/Controllers/HomeController.php';
require_once __DIR__ . '/Controllers/LoginController.php';
require_once __DIR__ . '/Controllers/RegisterController.php';
require_once __DIR__ . '/Controllers/LogoutController.php';
require_once __DIR__ . '/Controllers/GuestbookController.php';

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

switch ($requestPath) {
    case '/':
        $controllerClassName = 'HomeController';
        break;
    case '/login':
        $controllerClassName = 'LoginController';
        break;
    case '/register':
        $controllerClassName = 'RegisterController';
        break;
    case '/logout':
        $controllerClassName = 'LogoutController';
        break;
    case '/guestbook':
        $controllerClassName = 'GuestbookController';
        break;
    default:
        http_response_code(404);
        echo '404 Not Found';
        exit;
}

$controllerClassName = 'guestbook\\Controllers\\' . $controllerClassName;
$controller = new $controllerClassName();
$controller->execute();
