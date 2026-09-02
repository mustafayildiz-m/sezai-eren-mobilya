<?php
/**
 * Seeds demo categories, projects and sample images.
 * Run: php scripts/seed.php   (or: docker compose exec app php scripts/seed.php)
 * Safe to re-run: skips if projects already exist unless --force is passed.
 */
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';

use App\Database;
use App\Image;
use App\Models\Category;
use App\Models\ImageModel;
use App\Models\Project;

Database::migrate();
$force = in_array('--force', $argv, true);
if (Project::count() > 0 && !$force) { echo "Zaten veri var, atlanıyor (--force ile sıfırla).\n"; exit; }
if ($force) {
    foreach (Project::all() as $p) Project::delete((int) $p['id']);
    Database::pdo()->exec('DELETE FROM categories');
}

$raw = BASE_PATH . '/public/assets/seed/raw';
if (!is_dir($raw) || !glob("$raw/*.jpg")) { echo "Örnek görseller yok. Önce scripts/download_images.sh çalıştırın.\n"; exit(1); }

$cats = [
    'Mutfak Dolabı' => 'Kullanım alışkanlıklarınıza göre planlanan, tavana kadar depolama sunan ölçüye özel mutfaklar.',
    'Vestiyer' => 'Giriş holünüzü düzenleyen, ayakkabılık ve askılığı tek gövdede birleştiren şık vestiyerler.',
    'Gardırop' => 'Sürgülü veya kapaklı, iç düzeni size göre kurgulanan yatak odası gardıropları.',
    'TV Ünitesi' => 'Salonun odak noktası olan, kablo gizleme ve aydınlatma çözümlü TV üniteleri.',
    'Banyo Dolabı' => 'Neme dayanıklı malzemeyle üretilen, lavabo altı ve boy dolabı çözümleri.',
    'Özel Tasarım' => 'Kitaplık, çalışma masası, portmanto… Aklınızdaki her mobilyayı ölçünüze göre üretiyoruz.',
];
$catIds = [];
$i = 1;
foreach ($cats as $n => $d) $catIds[$n] = Category::create($n, $d, $i++);

// image id => [category, title, location, description, featured]
$projects = [
    ['1565538810643-b5bdb714032a', 'Mutfak Dolabı', 'Antrasit & Mermer Ada Mutfak', 'Çankaya', "Antrasit mat kapaklar, Calacatta desenli tezgah ve pirinç kulplarla tasarlanan ada mutfak. Tavana kadar üst dolaplar, gizli tandem çekmeceler ve entegre aydınlatma.", 1],
    ['1556912172-45b7abe8b7e1', 'Mutfak Dolabı', 'Beyaz Country Mutfak', 'Yenimahalle', "Klasik profilli beyaz kapaklar, ceviz kaplama ada ve altıgen seramik sırt panel. Blum yavaş kapanan mekanizmalar.", 1],
    ['1484154218962-a197022b5858', 'Mutfak Dolabı', 'Modern Beyaz Mutfak', 'Etimesgut', "Kulpsuz beyaz parlak kapaklar, siyah cam sırt panel ve ankastre yerleşimli L mutfak.", 0],
    ['1556911220-bff31c812dba', 'Mutfak Dolabı', 'Minimal Gri Mutfak', 'Keçiören', "Açık gri mat kapaklar, beyaz mermer tezgah ve gömme ocak düzeniyle sade ve ferah bir mutfak.", 0],
    ['1588854337115-1c67d9247e4d', 'Mutfak Dolabı', 'Krem Klasik Mutfak', 'Mamak', "Krem profilli kapaklar, cam vitrinli üst dolaplar ve geniş tezgah alanı.", 0],
    ['1600489000022-c2086d79f9d4', 'Mutfak Dolabı', 'Yeşil U Mutfak', 'Gölbaşı', "Koyu yeşil kapaklar, beyaz tezgah ve açık raf detaylarıyla geniş U mutfak.", 1],
    ['1558997519-83ea9252edf8', 'Vestiyer', 'Ceviz Kapaklı Vestiyer', 'Çankaya', "Masif görünümlü ceviz kapaklar, iç kısımda ayakkabılık ve boy aynası. Giriş holüne ölçü ile birebir.", 1],
    ['1595428774223-ef52624120d2', 'Vestiyer', 'Açık Raflı Meşe Vestiyer', 'Çayyolu', "Meşe kaplama, açık raflı ve kapaklı bölmeleri birleştiren modern vestiyer.", 0],
    ['1595526114035-0d45ed16cfbf', 'Gardırop', 'Yatak Odası Gardırop Takımı', 'Batıkent', "Tavana kadar beyaz gardırop, iç düzen: çekmece, askı ve raf modülleri.", 1],
    ['1616594039964-ae9021a400a0', 'Gardırop', 'Antrasit Sürgülü Gardırop', 'Oran', "Antrasit sürgülü kapaklar, LED aydınlatmalı iç düzen ve başucu üniteleriyle bütünleşik tasarım.", 0],
    ['1600121848594-d8644e57abab', 'TV Ünitesi', 'Siyah Kitaplıklı TV Ünitesi', 'Ümitköy', "Siyah mat kitaplık modülleri arasına yerleştirilen TV ünitesi, kablo gizleme kanalı.", 1],
    ['1600607687939-ce8a6c25118c', 'TV Ünitesi', 'Ceviz Panelli Duvar Ünitesi', 'İncek', "Ceviz kaplama duvar paneli, asma alt dolap ve gizli aydınlatma.", 0],
    ['1620626011761-996317b8d101', 'Banyo Dolabı', 'Beyaz Lake Banyo Dolabı', 'Çankaya', "Neme dayanıklı beyaz lake kapaklar, çift lavabo ve boy dolabı.", 0],
    ['1600210492486-724fe5c67fb0', 'Özel Tasarım', 'Salon Kitaplık & Sehpa', 'Bilkent', "Duvar boyu açık kitaplık ve eşleşen orta sehpa, meşe kaplama.", 0],
    ['1600566753086-00f18fb6b3ea', 'Özel Tasarım', 'Merdiven Altı Depolama', 'Beysukent', "Merdiven altını değerlendiren çekmeceli özel depolama ünitesi.", 0],
    ['1615873968403-89e068629265', 'Özel Tasarım', 'Yeşil Duvar Konsol', 'Kızılay', "Duvara asılı deri kaplama detaylı konsol ve çerçeve düzeni.", 0],
    ['1631679706909-1844bbd07221', 'Özel Tasarım', 'Bej Oturma Grubu Nişi', 'Çayyolu', "Oturma grubunu çevreleyen niş rafları ve gizli depolama.", 0],
    ['1616486338812-3dadae4b4ace', 'Özel Tasarım', 'Beyaz Salon Ünitesi', 'Eryaman', "Beyaz ve doğal ahşap kombinli salon depolama ünitesi.", 0],
    ['1556228453-efd6c1ff04f6', 'Özel Tasarım', 'Pastel Salon Düzeni', 'Sincan', "Pastel tonlarda özel ölçü sehpa ve dekoratif duvar rafları.", 0],
];

$uploads = BASE_PATH . '/public/uploads';
$n = 0;
foreach ($projects as $k => [$img, $cat, $title, $loc, $desc, $feat]) {
    $file = "$raw/$img.jpg";
    if (!is_file($file)) { echo "yok: $img\n"; continue; }
    $id = Project::create(['category_id' => $catIds[$cat], 'title' => $title, 'location' => $loc, 'description' => $desc, 'is_featured' => $feat, 'sort_order' => $k + 1]);
    $r = Image::process($file, $uploads);
    $imgId = ImageModel::add($id, $r['filename'], $r['thumb'], "$title – $cat, $loc Ankara");
    Project::setCover($id, $imgId);
    // add 1-2 extra gallery shots from same category pool for richer detail pages
    $pool = array_filter($projects, fn($p) => $p[1] === $cat && $p[0] !== $img);
    foreach (array_slice(array_values($pool), 0, 2) as $extra) {
        if (!is_file("$raw/{$extra[0]}.jpg")) continue;
        $r2 = Image::process("$raw/{$extra[0]}.jpg", $uploads);
        ImageModel::add($id, $r2['filename'], $r2['thumb'], "$title detay");
    }
    $n++;
    echo "+ $title\n";
}
echo "$n proje eklendi.\n";
