<?php

declare(strict_types=1);

function config(string $key, mixed $default = null): mixed
{
    static $cfg;
    if ($cfg === null) {
        $cfg = require ROOT_PATH . '/config/config.php';
        $local = ROOT_PATH . '/config/config.local.php';
        if (is_file($local)) {
            $cfg = array_merge($cfg, require $local);
        }
    }
    return $cfg[$key] ?? $default;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): never
{
    $base = rtrim((string) config('base_url', ''), '/');
    header('Location: ' . $base . $path);
    exit;
}

function url(string $path = '/'): string
{
    $base = rtrim((string) config('base_url', ''), '/');
    if ($path === '') {
        $path = '/';
    }
    if ($path[0] !== '/') {
        $path = '/' . $path;
    }
    return $base . $path;
}

function flash(string $type, string $message): void
{
    $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
}

function consume_flashes(): array
{
    $messages = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return $messages;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['_csrf'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(400);
        echo 'Invalid CSRF token.';
        exit;
    }
}

function render(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $flashes = consume_flashes();
    $currentUser = Auth::user();
    $appName = (string) config('app_name', 'ArithmeCats');
    $templateFile = ROOT_PATH . '/templates/' . $template . '.php';
    if (!is_file($templateFile)) {
        http_response_code(500);
        echo 'Template not found.';
        exit;
    }
    require ROOT_PATH . '/templates/layout.php';
}

function request_path(): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($uri, PHP_URL_PATH) ?: '/';
    $base = rtrim((string) config('base_url', ''), '/');
    if ($base !== '' && str_starts_with($path, $base)) {
        $path = substr($path, strlen($base)) ?: '/';
    }
    return $path === '' ? '/' : $path;
}

function method(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

function rarity_label(string $rarity): string
{
    return match ($rarity) {
        'common' => 'Common',
        'uncommon' => 'Uncommon',
        'rare' => 'Rare',
        'epic' => 'Epic',
        'legendary' => 'Legendary',
        default => ucfirst($rarity),
    };
}

function rarity_order(): array
{
    return ['legendary', 'epic', 'rare', 'uncommon', 'common'];
}

function operation_label(string $op): string
{
    return match ($op) {
        '+' => 'Addition',
        '-' => 'Subtraction',
        '×' => 'Multiplication',
        '÷' => 'Division',
        default => $op,
    };
}
