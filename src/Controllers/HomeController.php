<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Ankara;
use App\Models\Category;
use App\Models\Project;
use App\Models\Setting;
use App\Seo;
use App\View;

final class HomeController
{
    /** Ana sayfada gösterilen, Ankara aramalarında en sık karşılaşılan sorular. */
    private const FAQ = [
        ['Ankara\'nın hangi ilçelerinde hizmet veriyorsunuz?', 'Ankara\'nın tamamına hizmet veriyoruz. En yoğun çalıştığımız ilçeler Çankaya, Keçiören, Yenimahalle, Mamak, Etimesgut, Altındağ, Sincan ve Gölbaşı. Mesafe için ek nakliye ücreti almıyoruz.'],
        ['Keşif, ölçü ve 3D tasarım ücretli mi?', 'Hayır. Ankara içinde keşif, ölçü alma, 3 boyutlu tasarım ve yazılı fiyat teklifi tamamen ücretsizdir. Teklifi beğenmezseniz hiçbir yükümlülüğünüz olmaz.'],
        ['Ölçüye özel mobilya hazır mobilyadan pahalı mı?', 'Metrekare başına maliyet genelde yakındır; fark, kaybedilen alanda ortaya çıkar. Hazır modüller odanın ölçüsüne oturmadığı için yanda ve üstte ölü alan bırakır. Ölçüye özel üretimde aynı bütçeyle daha fazla kullanılabilir depolama elde edilir.'],
        ['Üretim ve montaj ne kadar sürüyor?', 'Onaylı çizimden sonra tek parça işler (vestiyer, TV ünitesi) 10-15 gün, komple mutfak veya gardırop 2-4 hafta içinde üretilip monte edilir.'],
        ['Montajı kim yapıyor, garanti var mı?', 'Montajı kendi ekibimiz yapar, taşeron kullanmıyoruz. Teslim tarihinden itibaren işçilik ve mekanizma garantisi veriyoruz; kapak ayarı gibi montaj sonrası talepler ücretsizdir.'],
    ];

    /**
     * Öne çıkan projelerden kategori çeşitliliği olan bir seçki.
     * Aynı kategoriden arka arkaya üç iş göstermek portföyü dar gösteriyor.
     */
    private static function diverseFeatured(int $limit): array
    {
        $all = Project::featured(24);
        $picked = [];
        $seen = [];
        foreach ($all as $p) {
            $cat = $p['category_id'] ?? 0;
            if (isset($seen[$cat])) continue;
            $seen[$cat] = true;
            $picked[] = $p;
            if (count($picked) === $limit) return $picked;
        }
        // Yeterli kategori yoksa kalanlarla tamamla
        foreach ($all as $p) {
            if (count($picked) === $limit) break;
            if (!in_array($p, $picked, true)) $picked[] = $p;
        }
        return $picked;
    }

    public static function index(array $p = []): string
    {
        $s = Setting::all();
        return View::render('home', [
            // Fotoğrafı henüz olmayan kategoriyi ana sayfada "0 proje" diye
            // göstermek yerine gizliyoruz; hizmet sayfaları yerinde duruyor.
            'categories' => array_values(array_filter(Category::all(), fn($c) => (int) $c['project_count'] > 0)),
            'featured' => self::diverseFeatured(3),
            'services' => array_map(fn($k) => Ankara::service($k), array_keys(Ankara::services())),
            'districts' => array_map(fn($k) => Ankara::district($k), array_keys(Ankara::districts())),
            'faq' => self::FAQ,
            'description' => 'Ankara\'da ölçüye özel mutfak dolabı, vestiyer, gardırop ve özel tasarım mobilya. 20 yıllık ustalık, ücretsiz keşif, 3D tasarım. Sezai Eren Mobilya.',
            'jsonLd' => [Seo::localBusiness($s), Seo::website($s), Seo::faq(self::FAQ)],
        ]);
    }
}
