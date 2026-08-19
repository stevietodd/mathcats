<?php

declare(strict_types=1);

final class Auth
{
    private static ?array $user = null;

    public static function boot(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $stmt = Database::pdo()->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute([(int) $_SESSION['user_id']]);
            $user = $stmt->fetch();
            if ($user) {
                self::$user = $user;
            } else {
                unset($_SESSION['user_id']);
            }
        }
    }

    public static function user(): ?array
    {
        return self::$user;
    }

    public static function check(): bool
    {
        return self::$user !== null;
    }

    public static function attempt(string $username, string $password): bool
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([trim($username)]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }
        $_SESSION['user_id'] = (int) $user['id'];
        self::$user = $user;
        return true;
    }

    public static function register(string $username, string $password, string $displayName = ''): array
    {
        $username = trim($username);
        $displayName = trim($displayName);
        $errors = [];

        if (!preg_match('/^[A-Za-z][A-Za-z0-9_]{2,19}$/', $username)) {
            $errors[] = 'Username must start with a letter and be 3–20 letters, numbers, or underscores.';
        }
        if (strlen($password) < 4) {
            $errors[] = 'Password must be at least 4 characters.';
        }
        if (strlen($displayName) > 30) {
            $errors[] = 'Display name is too long.';
        }

        if ($errors) {
            return $errors;
        }

        $stmt = Database::pdo()->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            return ['That username is already taken. Try another.'];
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $name = $displayName !== '' ? $displayName : $username;
        Database::pdo()->prepare(
            'INSERT INTO users (username, password_hash, display_name) VALUES (?, ?, ?)'
        )->execute([$username, $hash, $name]);

        $_SESSION['user_id'] = (int) Database::pdo()->lastInsertId();
        self::boot();
        return [];
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        self::$user = null;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            flash('error', 'Log in to play and collect cards.');
            redirect('/login');
        }
    }

    public static function displayName(?array $user = null): string
    {
        $user ??= self::$user;
        if (!$user) {
            return '';
        }
        $name = trim((string) ($user['display_name'] ?? ''));
        return $name !== '' ? $name : (string) $user['username'];
    }
}
