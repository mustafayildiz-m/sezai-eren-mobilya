<?php
declare(strict_types=1);
namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;
use App\RateLimit;
use App\View;

final class AuthController
{
    public static function form(array $p = [], ?string $error = null): string
    {
        if (Auth::check()) redirect('/yonetim');
        return View::render('admin/login', ['error' => $error], null);
    }

    public static function login(array $p = []): string
    {
        Csrf::check();
        if (!RateLimit::hit('login:' . client_ip(), 5, 600)) {
            http_response_code(429);
            return View::render('admin/login', ['error' => 'Çok fazla deneme. 10 dakika sonra tekrar deneyin.'], null);
        }
        if (Auth::attempt(trim((string) ($_POST['username'] ?? '')), (string) ($_POST['password'] ?? ''))) redirect('/yonetim');
        http_response_code(401);
        return View::render('admin/login', ['error' => 'Kullanıcı adı veya şifre hatalı.'], null);
    }

    public static function logout(array $p = []): string
    {
        Csrf::check();
        Auth::logout();
        redirect('/yonetim/giris');
    }
}
