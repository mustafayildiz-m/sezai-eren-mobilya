<?php
declare(strict_types=1);
namespace App;

use App\Models\User;

final class Auth
{
    public static function attempt(string $username, string $password): bool
    {
        $user = User::findByUsername($username);
        if (!$user || !password_verify($password, $user['password_hash'])) return false;
        if (session_status() === PHP_SESSION_ACTIVE) session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $user['id'];
        $_SESSION['admin_name'] = $user['username'];
        return true;
    }

    public static function check(): bool
    {
        return !empty($_SESSION['admin_id']);
    }

    public static function id(): int
    {
        return (int) ($_SESSION['admin_id'] ?? 0);
    }

    public static function logout(): void
    {
        unset($_SESSION['admin_id'], $_SESSION['admin_name']);
        if (session_status() === PHP_SESSION_ACTIVE) session_regenerate_id(true);
    }

    public static function require(): void
    {
        if (!self::check()) redirect('/yonetim/giris');
    }
}
