<?php
declare(strict_types=1);
namespace App;

final class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        return $_SESSION['_csrf'];
    }

    public static function verify(?string $token): bool
    {
        return is_string($token) && !empty($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
    }

    /** Aborts the request with 419 when the token in POST (or X-CSRF-Token header) is invalid. */
    public static function check(): void
    {
        $t = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!self::verify($t)) {
            http_response_code(419);
            exit('Oturum süresi doldu, sayfayı yenileyip tekrar deneyin.');
        }
    }
}
