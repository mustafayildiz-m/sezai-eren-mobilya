<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Ankara;
use App\Models\Category;
use App\Models\Project;
use App\Models\Setting;
use App\NotFound;
use App\Seo;
use App\View;

/**
 * Ankara yerel arama sayfaları:
 *   /ankara-{hizmet}          → hizmet landing (ör. /ankara-mutfak-dolabi)
 *   /ankara/{ilce}-mobilya    → ilçe landing  (ör. /ankara/cankaya-mobilya)
 */
final class LandingController
{
    public static function service(array $p): string
    {
        $sv = Ankara::service((string) $p['slug']);
        if (!$sv) throw new NotFound();

        $cat = Category::bySlug($sv['slug']);
        $projects = $cat ? Project::all((int) $cat['id']) : [];
        $s = Setting::all();

        $jsonLd = [Seo::localBusiness($s), Seo::service($sv), Seo::breadcrumb([
            ['Ana Sayfa', '/'], ['Ankara ' . $sv['name'], null],
        ])];
        if ($sv['faq']) $jsonLd[] = Seo::faq($sv['faq']);
        if ($projects) $jsonLd[] = Seo::itemList($projects, 'Ankara ' . $sv['name'] . ' projeleri');

        return View::render('landing_service', [
            'title' => $sv['title'],
            'titleRaw' => true,
            'description' => $sv['description'],
            'canonical' => url($sv['path']),
            'ogImage' => isset($projects[0]) && $projects[0]['cover'] ? url('/uploads/' . $projects[0]['cover']) : url('/assets/hero.webp'),
            'sv' => $sv,
            'cat' => $cat,
            'projects' => array_slice($projects, 0, 6),
            'others' => array_filter(array_map(fn($k) => Ankara::service($k), array_keys(Ankara::services())), fn($o) => $o['slug'] !== $sv['slug']),
            'districts' => array_map(fn($k) => Ankara::district($k), array_keys(Ankara::districts())),
            'jsonLd' => $jsonLd,
        ]);
    }

    public static function district(array $p): string
    {
        $d = Ankara::district((string) $p['slug']);
        if (!$d) throw new NotFound();

        $s = Setting::all();
        $services = array_map(fn($k) => Ankara::service($k), array_keys(Ankara::services()));

        // İlçede tamamlanmış işler varsa öne çıkar: gerçek yerel kanıt.
        $local = array_values(array_filter(Project::all(), fn($pr) => $pr['location'] && mb_stripos($pr['location'], $d['name']) !== false));

        $faq = [
            [$d['name'] . '\'da keşif ve ölçü ücretli mi?', $d['name'] . ' dahil Ankara\'nın tamamında keşif, ölçü alma, 3 boyutlu tasarım ve fiyat teklifi ücretsizdir. Ek nakliye ücreti almıyoruz.'],
            [$d['name'] . '\'da montaj ne kadar sürede yapılıyor?', 'Onaylı çizimden sonra tek oda işleri genellikle 10-15 gün, komple mutfak 2-4 hafta içinde üretilip monte edilir. Montajı kendi ekibimiz yapar.'],
            ['Montaj sonrası servis veriyor musunuz?', 'Evet. Kapak ayarı, menteşe ve ray gibi montaj sonrası talepleri ücretsiz karşılıyoruz; ürün garantimiz teslim tarihinden itibaren başlar.'],
        ];

        $jsonLd = [
            Seo::localBusiness($s),
            Seo::breadcrumb([['Ana Sayfa', '/'], [$d['name'] . ' Mobilya', null]]),
            Seo::faq($faq),
        ];
        if ($local) $jsonLd[] = Seo::itemList($local, $d['name'] . ' mobilya projeleri');

        return View::render('landing_district', [
            'title' => $d['title'],
            'titleRaw' => true,
            'description' => $d['description'],
            'canonical' => url($d['path']),
            'ogImage' => isset($local[0]) && $local[0]['cover'] ? url('/uploads/' . $local[0]['cover']) : url('/assets/hero.webp'),
            'd' => $d,
            'services' => $services,
            'local' => array_slice($local, 0, 6),
            'faq' => $faq,
            'others' => array_filter(array_map(fn($k) => Ankara::district($k), array_keys(Ankara::districts())), fn($o) => $o['slug'] !== $d['slug']),
            'jsonLd' => $jsonLd,
        ]);
    }
}
