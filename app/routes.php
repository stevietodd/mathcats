<?php

declare(strict_types=1);

require_once ROOT_PATH . '/app/controllers/HomeController.php';
require_once ROOT_PATH . '/app/controllers/AuthController.php';
require_once ROOT_PATH . '/app/controllers/PlayController.php';
require_once ROOT_PATH . '/app/controllers/BinderController.php';

function register_routes(Router $router): void
{
    $router->get('/', [HomeController::class, 'home']);

    $router->get('/login', [AuthController::class, 'loginForm']);
    $router->post('/login', [AuthController::class, 'login']);
    $router->get('/signup', [AuthController::class, 'signupForm']);
    $router->post('/signup', [AuthController::class, 'signup']);
    $router->post('/logout', [AuthController::class, 'logout']);

    $router->get('/play/setup', [PlayController::class, 'setup']);
    $router->post('/play/start', [PlayController::class, 'start']);
    $router->get('/play', [PlayController::class, 'play']);
    $router->post('/play/answer', [PlayController::class, 'answer']);
    $router->get('/rounds/{id}', [PlayController::class, 'results']);
    $router->post('/rounds/{id}/open', [PlayController::class, 'open']);

    $router->get('/binder', [BinderController::class, 'index']);
    $router->get('/binder/{slug}', [BinderController::class, 'show']);
}
