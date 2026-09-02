<?php
declare(strict_types=1);
namespace App\Models;

use App\Database;

final class User
{
    public static function findByUsername(string $u): ?array
    {
        $s = Database::pdo()->prepare('SELECT * FROM users WHERE username=?'); $s->execute([$u]);
        return $s->fetch() ?: null;
    }

    public static function ensureAdmin(): void
    {
        $db = Database::pdo();
        if ((int) $db->query('SELECT COUNT(*) FROM users')->fetchColumn() > 0) return;
        $user = env('ADMIN_USER', 'admin');
        $pass = env('ADMIN_PASSWORD', 'admin123');
        $db->prepare('INSERT INTO users (username, password_hash) VALUES (?,?)')->execute([$user, password_hash($pass, PASSWORD_DEFAULT)]);
    }

    public static function updatePassword(int $id, string $newPassword): void
    {
        Database::pdo()->prepare('UPDATE users SET password_hash=? WHERE id=?')->execute([password_hash($newPassword, PASSWORD_DEFAULT), $id]);
    }
}
