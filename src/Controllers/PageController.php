<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Ankara;
use App\Models\Setting;
use App\Seo;
use App\View;

final class PageController
{
    public static function about(array $p = []): string
    {
        return View::render('about', [
            'title' => 'Hakkımızda',
            'description' => 'Sezai Eren Mobilya: Ankara Siteler\'de 20 yılı aşkın tecrübeyle ölçüye özel mutfak dolabı, vestiyer ve gardırop üretimi. Keşiften montaja tek elden.',
            'jsonLd' => [
                Seo::localBusiness(Setting::all()),
                Seo::breadcrumb([['Ana Sayfa', '/'], ['Hakkımızda', null]]),
                ['@type' => 'AboutPage', 'url' => url('/hakkimizda'), 'name' => 'Hakkımızda', 'about' => ['@id' => url('/') . '#business']],
            ],
        ]);
    }

    public static function contact(array $p = []): string
    {
        return View::render('contact', [
            'title' => 'İletişim',
            'description' => 'Sezai Eren Mobilya iletişim: telefon, WhatsApp, adres ve çalışma saatleri. Ankara\'da ücretsiz keşif için hemen ulaşın.',
            'districts' => array_map(fn($k) => Ankara::district($k), array_keys(Ankara::districts())),
            'jsonLd' => [
                Seo::localBusiness(Setting::all()),
                Seo::breadcrumb([['Ana Sayfa', '/'], ['İletişim', null]]),
                ['@type' => 'ContactPage', 'url' => url('/iletisim'), 'name' => 'İletişim', 'about' => ['@id' => url('/') . '#business']],
            ],
        ]);
    }
}
