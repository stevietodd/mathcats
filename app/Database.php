<?php

declare(strict_types=1);

final class Database
{
    private static ?PDO $pdo = null;

    public static function init(string $dbPath): void
    {
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $isNew = !is_file($dbPath);
        self::$pdo = new PDO('sqlite:' . $dbPath, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        self::$pdo->exec('PRAGMA foreign_keys = ON');

        if ($isNew) {
            self::applySchema();
        }
    }

    public static function pdo(): PDO
    {
        if (!self::$pdo) {
            throw new RuntimeException('Database not initialized.');
        }
        return self::$pdo;
    }

    public static function applySchema(): void
    {
        $schema = file_get_contents(ROOT_PATH . '/sql/schema.sql');
        if ($schema === false) {
            throw new RuntimeException('Could not read schema.sql');
        }
        self::pdo()->exec($schema);
    }
}
