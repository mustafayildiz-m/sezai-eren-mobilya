<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Csrf;
use App\Image;
use App\Models\Category;
use App\Models\Quote;
use App\Models\Setting;
use App\QuoteValidator;
use App\RateLimit;
use App\Seo;
use App\View;

final class QuoteController
{
    private static function wa(string $text = ''): string
    {
        return 'https://wa.me/' . preg_replace('/\D/', '', Setting::get('whatsapp')) . ($text ? '?text=' . rawurlencode($text) : '');
    }

    public static function form(array $p = [], array $errors = [], array $old = []): string
    {
        return View::render('quote', [
            'title' => 'Ücretsiz Teklif Al',
            'description' => 'Ankara\'da mutfak dolabı, vestiyer, gardırop için ücretsiz keşif ve fiyat teklifi. Formu doldurun, aynı gün sizi arayalım.',
            'categories' => Category::all(),
            'errors' => $errors,
            'old' => $old,
            'wa' => self::wa('Merhaba, teklif almak istiyorum.'),
            'jsonLd' => [Seo::localBusiness(Setting::all()), Seo::breadcrumb([['Ana Sayfa', '/'], ['Teklif Al', null]])],
        ]);
    }

    public static function submit(array $p = []): string
    {
        Csrf::check();
        if (!RateLimit::hit('quote:' . client_ip(), 3, 300)) {
            http_response_code(429);
            return self::form([], ['rate' => 'Çok sık gönderim yaptınız. Lütfen birkaç dakika sonra tekrar deneyin.'], $_POST);
        }
        $r = QuoteValidator::validate($_POST, $_FILES);
        if ($r['errors']) {
            http_response_code(422);
            return self::form([], $r['errors'], $_POST);
        }
        $data = $r['data'];
        if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            try {
                $img = Image::process($_FILES['photo']['tmp_name'], BASE_PATH . '/public/uploads/teklif');
                $data['photo'] = 'teklif/' . $img['filename'];
            } catch (\Throwable) { /* photo is optional; ignore failures */ }
        }
        Quote::create($data);
        $_SESSION['quote_done'] = $data;
        redirect('/teklif-al/tesekkurler');
    }

    public static function done(array $p = []): string
    {
        $d = $_SESSION['quote_done'] ?? null;
        if (!$d) redirect('/teklif-al');
        $text = "Merhaba, az önce web sitenizden teklif formu doldurdum.\nAd: {$d['name']}\nİş: {$d['job_type']}";
        return View::render('quote_done', ['title' => 'Teşekkürler', 'wa' => self::wa($text)]);
    }
}
