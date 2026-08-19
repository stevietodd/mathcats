<?php

declare(strict_types=1);

if (PHP_SAPI === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $file = __DIR__ . $path;
    if ($path !== '/' && is_file($file)) {
        return false;
    }
}

require_once dirname(__DIR__) . '/app/bootstrap.php';
require_once ROOT_PATH . '/app/routes.php';

$router = new Router();
register_routes($router);
$router->dispatch(method(), request_path());
