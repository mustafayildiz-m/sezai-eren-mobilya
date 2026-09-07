<?php
declare(strict_types=1);
namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;
use App\Models\Setting;
use App\Models\User;
use App\View;

final class SettingAdminController
{
    private const KEYS = ['site_name', 'tagline', 'phone', 'whatsapp', 'email', 'address', 'instagram', 'working_hours', 'map_embed', 'about', 'years', 'projects_done', 'happy_clients',
        'gsc_verification', 'map_url', 'latitude', 'longitude'];

    public static function index(array $p = []): string
    {
        Auth::require();
        return View::render('admin/settings', ['title' => 'Ayarlar', 's' => Setting::all()], 'admin/layout');
    }

    public static function save(array $p = []): string
    {
        Auth::require(); Csrf::check();
        foreach (self::KEYS as $k) {
            if (array_key_exists($k, $_POST)) {
                $v = trim((string) $_POST[$k]);
                if ($k === 'whatsapp') $v = preg_replace('/\D/', '', $v);
                Setting::set($k, $v);
            }
        }
        $np = (string) ($_POST['new_password'] ?? '');
        if ($np !== '') {
            if (strlen($np) < 8) { flash('err', 'Şifre en az 8 karakter olmalı.'); redirect('/yonetim/ayarlar'); }
            User::updatePassword(Auth::id(), $np);
        }
        flash('ok', 'Ayarlar kaydedildi.');
        redirect('/yonetim/ayarlar');
    }
}
