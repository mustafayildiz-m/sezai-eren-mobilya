<?php
declare(strict_types=1);
namespace App\Models;

use App\Database;

final class Setting
{
    private static ?array $cache = null;

    public static function all(): array
    {
        if (self::$cache === null) {
            self::$cache = Database::pdo()->query('SELECT key, value FROM settings')->fetchAll(\PDO::FETCH_KEY_PAIR);
        }
        return self::$cache;
    }

    public static function get(string $key, string $default = ''): string
    {
        return self::all()[$key] ?? $default;
    }

    public static function set(string $key, string $value): void
    {
        Database::pdo()->prepare('INSERT INTO settings (key, value) VALUES (?,?) ON CONFLICT(key) DO UPDATE SET value=excluded.value')->execute([$key, $value]);
        self::$cache = null;
    }
}
