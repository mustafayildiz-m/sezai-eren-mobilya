<?php
/**
 * Sezai Usta'nın gerçek işlerini içeri alır, örnek (seed) projelerin çoğunu siler.
 * Çalıştırma: DB_PATH=... php scripts/import_real.php /gorsellerin/oldugu/klasor
 */
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';

use App\Database;
use App\Image;
use App\Models\Category;
use App\Models\ImageModel;
use App\Models\Project;
use App\Models\Setting;

Database::migrate();
$src = rtrim($argv[1] ?? '', '/');
if (!is_dir($src)) { fwrite(STDERR, "Görsel klasörü bulunamadı: $src\n"); exit(1); }

// Örnek projelerden bırakılacaklar (gerçek işi olmayan kategorileri temsil ediyorlar)
$keep = ['ceviz-kapakli-vestiyer', 'siyah-kitaplikli-tv-unitesi', 'salon-kitaplik-sehpa'];

$cats = [];
foreach (Category::all() as $c) $cats[$c['name']] = (int) $c['id'];

$LOC = 'Mamak – Kıbrısköy';
$projects = [
    [
        'cat' => 'Gardırop', 'featured' => 1,
        'title' => 'Krem Oval Desenli Aynalı Gardırop',
        'photos' => ['foto1.jpeg', 'foto4.jpeg'],
        'desc' => "Yatak odasının iki duvarını köşe dönerek saran, tavana kadar krem gardırop. Kapaklarda frezeyle açılmış oval göbek deseni, ortada boydan boya ayna ve daire motifli ayna kapak. Mat pirinç uzun kulplar, üst kısımda mevsimlik eşya için ek dolap sırası. Ölçü alınıp yerinde montajla teslim edildi.",
    ],
    [
        'cat' => 'Gardırop', 'featured' => 1,
        'title' => 'Kemerli Yivli Beyaz Gardırop',
        'photos' => ['foto2.jpeg'],
        'desc' => "Kemer formlu, dikey yiv (fluted) dokulu kapaklarla üretilen altı kapaklı beyaz gardırop. Ortadaki iki kapak boy aynası, üstte tavan boşluğunu değerlendiren ek dolap sırası. Uzun pirinç kulplar ve gizli bantlı aydınlatmayla bütünleşen sade bir yatak odası çözümü.",
    ],
    [
        'cat' => 'Gardırop', 'featured' => 1,
        'title' => 'Kemerli Yivli Gri Gardırop',
        'photos' => ['foto10.jpeg'],
        'desc' => "Açık gri (greige) mat lake, kemerli ve yivli kapaklar; ortada kemer kesimli boy aynası. Füme bronz yivli uzun kulplarla tamamlanan, tavana kadar çıkan altı kapaklı gardırop. Köşe birleşimleri yuvarlatılarak dar odada yumuşak bir hat elde edildi.",
    ],
    [
        'cat' => 'Gardırop', 'featured' => 0,
        'title' => 'Kafes Aynalı Klasik Gardırop',
        'photos' => ['foto12.jpeg', 'foto11.jpeg'],
        'desc' => "Klasik profilli açık gri kapaklar ve baklava motifli kafes ayna detayı. Üç ayna kapak odayı görsel olarak genişletiyor; üstte tavana kadar ek dolap sırası var. Antik bronz yivli kulplarla klasik çizgi tamamlandı.",
    ],
    [
        'cat' => 'Mutfak Dolabı', 'featured' => 1,
        'title' => 'Beyaz Klasik Mutfak',
        'photos' => ['foto3.jpeg', 'foto5.jpeg'],
        'desc' => "Klasik profilli beyaz lake kapaklar, açık mermer desenli tezgah ve aynı desende sırt paneli. Vitrinli cam kapaklar, davlumbaz için özel kabin, ankastre fırın ve buzdolabı için boy dolabı. Antik bronz kabuk kulplar. Mutfaktan salona geçen bölümde tezgah, bar formunda devam ettirildi.",
    ],
    [
        'cat' => 'Mutfak Dolabı', 'featured' => 1,
        'title' => 'Haki Yeşil Klasik Mutfak',
        'photos' => ['foto9.jpeg', 'foto8.jpeg', 'foto6.jpeg', 'foto7.jpeg'],
        'desc' => "Haki yeşil mat lake, klasik profilli kapaklarla üretilen tam boy mutfak. Tavana kadar iki sıra üst dolap, kemer kesimli davlumbaz kabini, geniş çekmeceli alt modüller. Aynı renk ve profille üretilen boy dolabı ve koridor konsolu sayesinde mutfak, evin geri kalanıyla aynı dili konuşuyor. Nikel kabuk kulplar ve düğme kulplar.",
    ],
];

// 1) Örnek projeleri temizle
$silinen = 0;
foreach (Project::all() as $p) {
    if (in_array($p['slug'], $keep, true)) continue;
    Project::delete((int) $p['id']);
    $silinen++;
}
echo "Silinen örnek proje: $silinen\n";

// 2) Kalan örnek projeleri geri plana al
foreach (Project::all() as $p) {
    Project::update((int) $p['id'], [
        'category_id' => $p['category_id'], 'title' => $p['title'],
        'description' => $p['description'], 'location' => $p['location'],
        'is_featured' => 0, 'sort_order' => 90,
    ]);
}

// 3) Gerçek işleri ekle
$uploads = BASE_PATH . '/public/uploads';
$i = 1;
foreach ($projects as $p) {
    $id = Project::create([
        'category_id' => $cats[$p['cat']] ?? null,
        'title' => $p['title'],
        'description' => $p['desc'],
        'location' => $LOC,
        'is_featured' => $p['featured'],
        'sort_order' => $i,
    ]);
    $cover = null;
    foreach ($p['photos'] as $k => $f) {
        $path = "$src/$f";
        if (!is_file($path)) { echo "  ! görsel yok: $f\n"; continue; }
        $r = Image::process($path, $uploads);
        $alt = $k === 0
            ? "{$p['title']} – {$p['cat']}, Mamak Ankara"
            : "{$p['title']} detay – Sezai Eren Mobilya";
        $imgId = ImageModel::add($id, $r['filename'], $r['thumb'], $alt);
        if ($cover === null) { $cover = $imgId; Project::setCover($id, $imgId); }
    }
    echo "+ {$p['title']} (" . count($p['photos']) . " foto)\n";
    $i++;
}

// 4) İletişim bilgileri
Setting::set('phone', '0543 490 15 23');
Setting::set('whatsapp', '905434901523');
echo "Telefon ve WhatsApp güncellendi.\n";
