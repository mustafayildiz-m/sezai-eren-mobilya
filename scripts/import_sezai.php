<?php
/**
 * Sezai usta'nın 2026-09 fotoğraf partisini içeri alır ve kalan örnek
 * (Unsplash) projeleri siler. Sitede stok görsel bırakmaz.
 *
 *   php scripts/import_sezai.php            → storage/kaynak-foto kullanılır
 *   php scripts/import_sezai.php /baska/dizin
 *
 * ÖNEMLİ: scripts/import_real.php ile daha önce aktarılan gerçek Mamak
 * projelerine DOKUNMAZ. Yalnızca aşağıdaki $FAKE listesindeki örnek projeleri
 * ve bu scriptin kendi ürettiği projeleri siler — bu sayede tekrar tekrar
 * çalıştırılabilir.
 */
declare(strict_types=1);
require dirname(__DIR__) . '/src/bootstrap.php';

use App\Database;
use App\Image;
use App\Models\Category;
use App\Models\ImageModel;
use App\Models\Project;
use App\Slug;

Database::migrate();

$src = rtrim($argv[1] ?? (BASE_PATH . '/storage/kaynak-foto'), '/');
if (!is_dir($src)) { fwrite(STDERR, "Kaynak klasör yok: $src\n"); exit(1); }
$uploads = BASE_PATH . '/public/uploads';

/*
 * Kategoriler. Sıra, sitedeki görünüm sırasıdır.
 * "Turizm & Ticari Projeler" bilerek en sonda ve Ankara landing sayfası yok:
 * bu işler Ankara dışında, kurumsal referans olarak sunuluyor.
 */
$CATS = [
    'Mutfak Dolabı' => 'Kullanım alışkanlıklarınıza göre planlanan, tavana kadar depolama sunan ölçüye özel mutfaklar.',
    'Gardırop' => 'Sürgülü veya kapaklı, iç düzeni size göre kurgulanan yatak odası gardıropları.',
    'Vestiyer' => 'Giriş holünüzü düzenleyen, ayakkabılık ve askılığı tek gövdede birleştiren vestiyerler.',
    'TV Ünitesi' => 'Salonun odak noktası olan, kablo gizleme ve aydınlatma çözümlü TV üniteleri.',
    'Banyo Dolabı' => 'Neme dayanıklı malzemeyle üretilen, lavabo altı ve boy dolabı çözümleri.',
    'İç Kapı & Duvar Paneli' => 'Ölçüye özel iç kapı, ahşap lamel bölücü, yivli panel ve lambri uygulamaları.',
    'Ahşap Merdiven' => 'Beton merdiven üzerine masif meşe basamak kaplama, küpeşte ve korkuluk.',
    'Özel Tasarım' => 'Kitaplık, çalışma masası, ranza, doğal kenar sehpa… Aklınızdaki mobilyayı ölçünüze göre üretiyoruz.',
    'Turizm & Ticari Projeler' => 'Otel, restoran ve işyerleri için ahşap cephe kaplama, pergola, deck, banko ve resepsiyon uygulamaları.',
];

/*
 * Projeler. 'photos' → storage/kaynak-foto içindeki dosya adları (ilk foto kapak olur).
 *
 * NOT: 'location' bilerek boş bırakıldı. Fotoğrafların hangi ilçede çekildiğini
 * bilmiyoruz ve uydurma konum hem müşteriye hem Google'a yanlış bilgi olur.
 * Sezai usta hatırladıkça yönetim panelinden ilçe girildiğinde ilgili proje
 * o ilçenin sayfasında referans olarak otomatik görünmeye başlar.
 */
$PROJECTS = [
    // ---------- MUTFAK DOLABI ----------
    ['cat' => 'Mutfak Dolabı', 'featured' => 1,
     'title' => 'Beyaz Klasik Mutfak – Antrasit Bordürlü',
     'photos' => ['03.jpeg', '04.jpeg', '06.jpeg', '05.jpeg', '08.jpeg', '11.jpeg', '12.jpeg'],
     'desc' => "Beyaz klasik profilli kapaklar, tavan bordüründe ve tezgah altı süpürgelikte antrasit vurgu. Mermer desenli tezgah ve aynı desende sırt paneli. Ankastre fırın ile mikrodalga için gömme kabin, cam vitrinli üst dolaplar ve tavana kadar çıkan ek dolap sırası. L planlı mutfakta tezgah kesintisiz devam ediyor, köşe magic corner ile değerlendirildi."],

    ['cat' => 'Mutfak Dolabı', 'featured' => 1,
     'title' => 'Vizon Mat Modern Mutfak',
     'photos' => ['13.jpeg', '10.jpeg'],
     'desc' => "Vizon (greige) mat kapaklar ve kulpsuz gizli tutamak detayı. Beyaz mermer desenli tezgah ve sırt paneli, asma tavanda gizli LED bant. Ankastre ocak, davlumbaz, fırın ve bulaşık makinesi tezgah düzenine entegre edildi. Üst dolaplar tek sıra bırakılıp tavan boşluğu ferah tutuldu."],

    ['cat' => 'Mutfak Dolabı', 'featured' => 1,
     'title' => 'Açık Mutfak ve Ahşap Bar Uzantısı',
     'photos' => ['55.jpeg', '56.jpeg'],
     'desc' => "Beyaz kulpsuz üst dolaplar, mermer desenli sırt paneli ve tezgahtan salona doğru uzanan ahşap bar tablası. Açık plan yaşam alanında mutfağı duvarla ayırmadan tanımlıyor. Bar tablası aynı zamanda kahvaltı ve çalışma yüzeyi olarak kullanılıyor."],

    ['cat' => 'Mutfak Dolabı', 'featured' => 0,
     'title' => 'Beyaz Mutfak – Siyah Granit Tezgah',
     'photos' => ['14.jpeg'],
     'desc' => "Beyaz düz kapaklar ve siyah granit tezgah. Evye pencere altına alınarak gün ışığından yararlanıldı. Tavana kadar üst dolaplar ve buzdolabı için boy kabin bulunuyor."],

    ['cat' => 'Mutfak Dolabı', 'featured' => 0,
     'title' => 'Beyaz U Mutfak – Granit Tezgah',
     'photos' => ['48.jpeg'],
     'desc' => "Küçük bir mutfakta U düzeniyle maksimum tezgah alanı. Beyaz kapaklar, siyah granit tezgah ve mozaik sırt paneli. Üst dolaplar tavana kadar çıkarılarak depolama artırıldı."],

    ['cat' => 'Mutfak Dolabı', 'featured' => 0,
     'title' => 'Meşe Desenli Mutfak – Antrasit Tezgah',
     'photos' => ['62.jpeg'],
     'desc' => "Meşe desenli kapaklar ve antrasit tezgah. Köşe dönüşünde tezgah kesintisiz devam ediyor; üst dolaplar arasına ankastre cihaz için boşluk bırakıldı. Montaj aşamasında çekilmiş fotoğraf."],

    ['cat' => 'Mutfak Dolabı', 'featured' => 0,
     'title' => 'Beyaz Parlak Mutfak – Cam Sırt Paneli',
     'photos' => ['70.jpeg', '72.jpeg'],
     'desc' => "Beyaz parlak kulpsuz kapaklar ve baskılı cam sırt paneli. Kavisli uç modüllerle köşeler yumuşatıldı. Pencere önü tezgahı çalışma alanı olarak değerlendirildi."],

    ['cat' => 'Mutfak Dolabı', 'featured' => 0,
     'title' => 'Beyaz Parlak Köşe Mutfak',
     'photos' => ['71.jpeg'],
     'desc' => "Beyaz parlak kapaklar, çiçek desenli cam sırt paneli ve L planlı yerleşim. Buzdolabı için boy kabin ve tavana kadar üst dolap sırası bulunuyor."],

    ['cat' => 'Mutfak Dolabı', 'featured' => 0,
     'title' => 'Ahşap ve Beyaz Mutfak – Mozaik Panel',
     'photos' => ['75.jpeg'],
     'desc' => "Ahşap desenli alt modüller, beyaz üst dolaplar ve granit tezgah. Mozaik sırt paneli iki tonu birbirine bağlıyor. Üst dolaplar ahşap çerçeve içine alınarak duvarda tek bir kütle oluşturuldu."],

    ['cat' => 'Mutfak Dolabı', 'featured' => 0,
     'title' => 'Mutfak Kiler ve Boy Dolabı Detayı',
     'photos' => ['35.jpeg'],
     'desc' => "Beyaz boy dolabının iç düzeni: ayarlanabilir raflar ve buzdolabı yanına yerleştirilen dar kiler kabini. Kapak açıldığında tüm raflara tek bakışta ulaşılıyor."],

    // ---------- GARDIROP ----------
    ['cat' => 'Gardırop', 'featured' => 1,
     'title' => 'Kafes Aynalı Sürgülü Gardırop',
     'photos' => ['09.jpeg'],
     'desc' => "Beyaz sürgülü kapaklar üzerinde kafes bölmeli ayna uygulaması. Yatak ile dolap arası dar olduğu için sürgülü sistem tercih edildi; kapak açılışı için yer gerekmiyor. Aynalar odayı görsel olarak genişletiyor."],

    ['cat' => 'Gardırop', 'featured' => 1,
     'title' => 'Beyaz Klasik Altı Kapaklı Gardırop',
     'photos' => ['15.jpeg'],
     'desc' => "Klasik profilli beyaz kapaklar, ortada buzlu cam bölmeler ve altta iki geniş çekmece. Tavana kadar çıkan gövde ile mevsimlik eşya için üst sıra depolama sağlandı."],

    ['cat' => 'Gardırop', 'featured' => 0,
     'title' => 'Yatak Odası Gardırop ve Aynalı Konsol',
     'photos' => ['16.jpeg'],
     'desc' => "Duvardan duvara beyaz gardırop ve yanında aynalı konsol. Gardırop kapakları sade tutulup, ayna çerçevesindeki oyma detay odanın tek süsü olarak bırakıldı."],

    ['cat' => 'Gardırop', 'featured' => 0,
     'title' => 'Genç Odası Takımı – Beyaz ve Pudra',
     'photos' => ['63.jpeg', '64.jpeg'],
     'desc' => "Beyaz klasik profilli gardırop, kapitone pudra başlıklı karyola ve komodinlerden oluşan genç odası takımı. Gardırop kapaklarında camlı bölmeler, altta çekmece sırası bulunuyor."],

    ['cat' => 'Gardırop', 'featured' => 0,
     'title' => 'Beyaz Klasik Dört Kapaklı Gardırop',
     'photos' => ['65.jpeg'],
     'desc' => "Beyaz klasik gardırop; ortadaki iki kapak camlı, yanlar kapalı. Altta iki çekmece ve oymalı süpürgelik detayı."],

    ['cat' => 'Gardırop', 'featured' => 0,
     'title' => 'Mor Parlak Aynalı Gardırop',
     'photos' => ['74.jpeg'],
     'desc' => "Mor parlak kapaklar ve ortada oval boy aynası. Üstte ek dolap sırası, altta iki çekmece. Parlak yüzey küçük odada ışığı yansıtarak alanı büyütüyor."],

    // ---------- TV ÜNİTESİ ----------
    ['cat' => 'TV Ünitesi', 'featured' => 1,
     'title' => 'Siyah TV Ünitesi ve Doğal Kenar Ceviz Tabaka',
     'photos' => ['40.jpeg', '39.jpeg'],
     'desc' => "Mat siyah gövde üzerine doğal kenarı korunmuş masif ceviz tabaka. Pirinç uzun kulplar ve askılı (yerden yüksek) montaj. Aynı tasarım dili konsol olarak da üretilerek salon bütünlüğü sağlandı. Kablolar gövde içindeki kanaldan gizlendi."],

    ['cat' => 'TV Ünitesi', 'featured' => 0,
     'title' => 'Ceviz Duvar Ünitesi ve Yemek Masası',
     'photos' => ['68.jpeg'],
     'desc' => "Duvardan duvara ceviz TV ünitesi: ortada TV nişi, iki yanda camlı vitrin ve kapaklı depolama. Aynı malzemeden üretilen yemek masasıyla takım oluşturuluyor."],

    ['cat' => 'TV Ünitesi', 'featured' => 0,
     'title' => 'Ahşap Kaplama Yemek Odası ve Vitrin Nişi',
     'photos' => ['44.jpeg', '45.jpeg'],
     'desc' => "Duvarı boydan boya kaplayan dikey ahşap panel ve içine gömülen siyah raflı vitrin nişi. Tavandaki ahşap kirişler panelle aynı tonda tamamlandı. Masif yemek masası aynı projede üretildi."],

    // ---------- İÇ KAPI & DUVAR PANELİ ----------
    ['cat' => 'İç Kapı & Duvar Paneli', 'featured' => 1,
     'title' => 'Taş Duvar ve Ahşap Lamel Giriş Holü',
     'photos' => ['51.jpeg', '49.jpeg', '50.jpeg', '57.jpeg'],
     'desc' => "Salon ile giriş holünü duvar örmeden ayıran dikey ahşap lamel bölücü. Arkasındaki taş dokulu duvarla birlikte holü tanımlıyor, ışığı kesmiyor. Aynı ahşap ton kapı kasasında ve tavan kirişlerinde tekrarlanarak mekân bütünlüğü kuruldu."],

    ['cat' => 'İç Kapı & Duvar Paneli', 'featured' => 0,
     'title' => 'Buzlu Camlı Beyaz Çift Kanat Kapı',
     'photos' => ['07.jpeg'],
     'desc' => "Salon girişi için beyaz çift kanat kapı; üç sıra buzlu cam ile ışık geçişi sağlanırken görüş engelleniyor. Kasa ve pervaz kapıyla aynı malzemeden üretildi."],

    ['cat' => 'İç Kapı & Duvar Paneli', 'featured' => 0,
     'title' => 'Antrasit İç Kapı – Beyaz Kasa',
     'photos' => ['47.jpeg'],
     'desc' => "Mat antrasit kapı kanadı, beyaz kasa ve kanat ortasında ince metal bant detayı. Dolu dolgulu üretim sayesinde ses yalıtımı sağlanıyor."],

    ['cat' => 'İç Kapı & Duvar Paneli', 'featured' => 0,
     'title' => 'Antrasit İç Kapı – Gri Kasa',
     'photos' => ['53.jpeg'],
     'desc' => "Antrasit kapı kanadı ve gri kasa-pervaz; balık sırtı parke ve dokulu duvar kaplamasıyla uyumlu sade bir uygulama."],

    ['cat' => 'İç Kapı & Duvar Paneli', 'featured' => 0,
     'title' => 'Beyaz Dekoratif Oyma Kapı',
     'photos' => ['54.jpeg'],
     'desc' => "Beyaz lake kapı üzerine frezeyle açılmış oval geçmeli desen. Dekoratif kapı, koridorda kapalı bir yüzey yerine görsel bir eleman oluşturuyor."],

    ['cat' => 'İç Kapı & Duvar Paneli', 'featured' => 0,
     'title' => 'Siyah Yivli Duvar Paneli',
     'photos' => ['02.jpeg'],
     'desc' => "Salon duvarını boydan boya kaplayan siyah dikey yivli (fluted) panel. Uygulama aşamasında çekilmiş fotoğraf; panel arkasına havalandırma boşluğu bırakıldı."],

    ['cat' => 'İç Kapı & Duvar Paneli', 'featured' => 0,
     'title' => 'Ahşap Lamelli Dış Kapı',
     'photos' => ['52.jpeg'],
     'desc' => "Siyah metal kasa içine yerleştirilen dikey lamelli masif ahşap kapı. Dış mekân kullanımı için ahşap emprenye edilip su bazlı vernikle korundu."],

    ['cat' => 'İç Kapı & Duvar Paneli', 'featured' => 0,
     'title' => 'Beyaz İç Kapı Uygulaması',
     'photos' => ['61.jpeg'],
     'desc' => "Koridordaki üç odaya beyaz düz iç kapı ve gizli kasa uygulaması. Pervazlar duvar hizasına gömülerek çıkıntısız bir yüzey elde edildi."],

    // ---------- AHŞAP MERDİVEN ----------
    ['cat' => 'Ahşap Merdiven', 'featured' => 1,
     'title' => 'Masif Meşe Merdiven Basamağı',
     'photos' => ['43.jpeg', '42.jpeg'],
     'desc' => "Beton merdiven üzerine masif meşe basamak kaplama. Rıhtlar beyaz bırakılarak ahşap öne çıkarıldı, basamak ön kenarına yuvarlatma verildi. Her basamağın ölçüsü ayrı alındı; duvara oturan kenarlar gönyesiz duvara göre kesildi. Küpeşte masif ahşap, korkuluk metal."],

    // ---------- ÖZEL TASARIM ----------
    ['cat' => 'Özel Tasarım', 'featured' => 1,
     'title' => 'Doğal Kenar Ceviz Orta Sehpa',
     'photos' => ['41.jpeg'],
     'desc' => "Doğal kenarı korunmuş tek parça masif ceviz tabakadan orta sehpa. Altında dökme demir tekerlekler; ağacın çatlakları dolgu ile sabitlenip yüzey mat vernikle korundu."],

    ['cat' => 'Özel Tasarım', 'featured' => 0,
     'title' => 'Krem Klasik Makyaj Masası',
     'photos' => ['01.jpeg'],
     'desc' => "Krem klasik makyaj masası; üç parçalı katlanır ayna, altın rengi kulplar ve oymalı kavisli ayaklar. Yan çekmece sıralarıyla takı ve kozmetik için ayrı bölmeler sağlandı."],

    ['cat' => 'Özel Tasarım', 'featured' => 0,
     'title' => 'Siyah Çerçeveli Camlı Köşe Dolabı',
     'photos' => ['46.jpeg'],
     'desc' => "Odanın köşesini dönen, siyah metal çerçeveli camlı kapaklara sahip giyinme dolabı. Meşe desenli gövde, içeride açık raf ve askılık düzeni. Camlı kapak, kapalı dolabın aksine içeriği görünür kılıyor."],

    ['cat' => 'Özel Tasarım', 'featured' => 0,
     'title' => 'Ofis Makam Masası Takımı',
     'photos' => ['77.jpeg'],
     'desc' => "Makam masası, sehpa takımı ve arkada dosya dolabından oluşan ofis takımı. Ceviz desenli gövde üzerine siyah deri görünümlü panel uygulamaları. Atölyede tamamlanmış hâliyle."],

    ['cat' => 'Özel Tasarım', 'featured' => 0,
     'title' => 'Masif Çam Ranza',
     'photos' => ['73.jpeg'],
     'desc' => "Masif çam ranza; çıtalı korkuluk, sabit merdiven ve yuvarlatılmış köşeler. Boyasız bırakılıp yalnızca su bazlı vernikle korundu."],

    ['cat' => 'Özel Tasarım', 'featured' => 0,
     'title' => 'Ahşap Çerçeveli Niş ve Dolap',
     'photos' => ['76.jpeg'],
     'desc' => "Duvar nişini çerçeveleyen ahşap kasa ve içine yerleştirilen beyaz dolap modülleri. Yanda dekoratif oyma panel, üstte kavisli asma tavanla bütünleşen bir salon köşesi."],

    ['cat' => 'Özel Tasarım', 'featured' => 0,
     'title' => 'Dekoratif Ahşap Kuyu',
     'photos' => ['36.jpeg'],
     'desc' => "Bahçe için üretilen dekoratif ahşap kuyu; yakma (shou sugi ban) tekniğiyle yüzeyi karartılmış gövde, çatısında pul kesim shingle kaplama. Atölye bahçesinde tamamlanmış hâliyle."],

    // ---------- TURİZM & TİCARİ PROJELER ----------
    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 1,
     'title' => 'Otel Ahşap Cephe Kaplaması',
     'photos' => ['27.jpeg', '26.jpeg', '31.jpeg', '30.jpeg', '32.jpeg'],
     'desc' => "Otel bloklarının dış cephesinde masif ahşap kaplama uygulaması. Yatay ve dikey lamel geçişleri, pencere çevrelerinde ahşap sövelerle çerçeveleme, saçak altında açılı kesim. Ahşap dış mekân için emprenye edilip UV korumalı vernikle bitirildi; taş duvarla birleşim detayları yerinde çözüldü."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 1,
     'title' => 'Otel Pergola ve Deck Uygulaması',
     'photos' => ['28.jpeg', '29.jpeg', '33.jpeg'],
     'desc' => "Oda teraslarına ahşap pergola ve zemin deck uygulaması. Pergola lamelleri güneş açısına göre aralıklandırıldı; deck altında havalandırma boşluğu bırakılarak nem tahliyesi sağlandı. Gizli vidalama ile yüzeyde metal görünmüyor."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 1,
     'title' => 'Kütük Ahşap Kafe Barı',
     'photos' => ['58.jpeg', '59.jpeg'],
     'desc' => "Taş duvarlı tarihi mekân için kütük ahşap kafe barı. Tezgah tek parça masif kütükten, ön yüzey yakma tekniğiyle karartılmış yarım tomruklardan oluşuyor. Arkada aynı dilde raf sistemi ve içecek dolabı nişleri üretildi."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 0,
     'title' => 'Ahşap Kemerli Havuz Yapısı',
     'photos' => ['20.jpeg', '19.jpeg'],
     'desc' => "Otel havuzunu örten ahşap kemer taşıyıcı sistem ve havuz çevresi deck kaplaması. Kemerler lamine ahşaptan üretilip yerinde birleştirildi; nemli ortam için tüm yüzeyler özel koruma ile bitirildi."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 0,
     'title' => 'Ahşap Taşıyıcı Yapı İnşası',
     'photos' => ['17.jpeg', '18.jpeg'],
     'desc' => "Kemer formlu ahşap taşıyıcı iskelet ve ahşap cephe kaplaması ile inşa edilen yapı. Taşıyıcı elemanlar atölyede hazırlanıp şantiyede monte edildi; cephede dikey ahşap kaplama ve renkli doğrama kullanıldı."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 0,
     'title' => 'Otel Lobi Ahşap Duvar Panelleri',
     'photos' => ['22.jpeg', '23.jpeg'],
     'desc' => "Otel lobisinde iki farklı panel uygulaması: klasik çerçeveli yeşil lake panel duvarı ve dikey yivli ahşap panel üzerine yerleştirilen daire formlu dekoratif madalyon. Her iki uygulamada da panel arkası havalandırmalı sistemle monte edildi."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 0,
     'title' => 'Ceviz Resepsiyon ve Bölücü',
     'photos' => ['24.jpeg'],
     'desc' => "Ceviz kaplama resepsiyon bankosu ve arkasındaki geçmeli raf/bölücü sistem. Banko önü kavisli formda üretildi; bölücü hem depolama hem mekân ayırıcı olarak çalışıyor."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 0,
     'title' => 'Ahşap Kafes Cephe',
     'photos' => ['25.jpeg'],
     'desc' => "Yapının dış cephesinde geçmeli ahşap kafes uygulaması. Farklı genişlikteki çıtalar düzensiz aralıklarla yerleştirilerek gölge oyunu oluşturuldu; güneş kırıcı olarak da işlev görüyor."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 0,
     'title' => 'Villa Ahşap Tavan Kafesi',
     'photos' => ['37.jpeg', '38.jpeg'],
     'desc' => "Villa yaşam alanında tavanı boydan boya kaplayan ahşap kafes uygulaması. Gizli aydınlatma kafesin arkasına yerleştirilerek tavanda yumuşak bir ışık dağılımı sağlandı."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 0,
     'title' => 'Sahil Deck ve Kameriye',
     'photos' => ['21.jpeg'],
     'desc' => "Sahil kenarında ahşap deck platformu ve üzerine oturan altıgen kameriye. Deniz suyuna ve tuza dayanıklı ahşap seçildi; deck altı taşıyıcı sistem zeminden yükseltilerek havalandırma sağlandı. Uygulama aşamasında çekilmiş fotoğraf."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 0,
     'title' => 'Kavisli Resepsiyon Bankosu',
     'photos' => ['34.jpeg', '69.jpeg'],
     'desc' => "İşyeri girişi için kavisli formda resepsiyon bankosu. Beyaz gövde üzerine renkli tabaka ve iç tarafta açık raf düzeni. Kavis, atölyede şablona göre bükülerek üretildi."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 0,
     'title' => 'Anaokulu Renkli Masa Takımı',
     'photos' => ['66crop.jpg'],
     'desc' => "Anaokulu için üretilen, yan yana getirildiğinde tek bir yüzey oluşturan renkli çiçek formlu masa takımı. Kenarlar çocuk güvenliği için yuvarlatıldı, ayaklar yükseklik ayarlı. Atölyede tamamlanmış hâliyle."],

    ['cat' => 'Turizm & Ticari Projeler', 'featured' => 0,
     'title' => 'Ahşap Sundurma Yapımı',
     'photos' => ['60.jpeg'],
     'desc' => "Yapı girişine ahşap taşıyıcılı sundurma uygulaması. Kolonlar ve kirişler atölyede hazırlanıp yerinde monte edildi. Uygulama aşamasında çekilmiş fotoğraf."],
];

// ---------------------------------------------------------------------------

// 1) Geriye kalan örnek (Unsplash) projeleri sil — site yalnızca gerçek işi göstersin.
//    Bu üç slug, import_real.php'nin bilerek bıraktığı örnek projelerdir.
$FAKE = ['ceviz-kapakli-vestiyer', 'siyah-kitaplikli-tv-unitesi', 'salon-kitaplik-sehpa'];
foreach ($FAKE as $slug) {
    if ($p = Project::bySlug($slug)) {
        Project::delete((int) $p['id']);
        echo "- örnek proje silindi: {$p['title']}\n";
    }
}

// 2) Bu scriptin daha önce oluşturduğu projeleri sil (tekrar çalıştırılabilirlik).
foreach ($PROJECTS as $p) {
    if ($ex = Project::bySlug(Slug::make($p['title']))) {
        Project::delete((int) $ex['id']);
    }
}

// 3) Kategorileri kur (varsa adı/açıklaması güncellenir, yoksa oluşturulur).
$catIds = [];
$i = 1;
foreach ($CATS as $name => $desc) {
    $slug = Slug::make($name);
    $existing = Category::bySlug($slug);
    if ($existing) {
        Category::update((int) $existing['id'], $name, $desc, $i);
        $catIds[$name] = (int) $existing['id'];
    } else {
        $catIds[$name] = Category::create($name, $desc, $i);
    }
    $i++;
}
// Listede olmayan ve hiç projesi kalmamış kategorileri temizle.
// Projesi olan bir kategoriye dokunmuyoruz — eski gerçek işler orada olabilir.
foreach (Category::all() as $c) {
    if (!in_array((int) $c['id'], $catIds, true) && (int) $c['project_count'] === 0) {
        echo "- boş kategori silindi: {$c['name']}\n";
        Category::delete((int) $c['id']);
    }
}
echo count($catIds) . " kategori hazır.\n";

// 4) Projeleri ve görselleri kur. sort_order 100'den başlıyor ki daha önce
//    aktarılmış gerçek projeler listenin başında kalsın.
$sort = 100;
$photoCount = 0;
foreach ($PROJECTS as $p) {
    $id = Project::create([
        'category_id' => $catIds[$p['cat']],
        'title' => $p['title'],
        'description' => $p['desc'],
        'location' => $p['location'] ?? '',
        'is_featured' => $p['featured'],
        'sort_order' => $sort++,
    ]);

    $cover = null;
    foreach ($p['photos'] as $k => $f) {
        $path = "$src/$f";
        if (!is_file($path)) { echo "  ! görsel yok: $f\n"; continue; }
        $r = Image::process($path, $uploads);
        // Alt metin: ne olduğunu + kategoriyi söyler. Google Görseller'in okuduğu alan.
        $alt = $k === 0
            ? $p['title'] . ' – ' . $p['cat'] . ', Sezai Eren Mobilya'
            : $p['title'] . ' detay ' . ($k + 1) . ' – Sezai Eren Mobilya';
        $imgId = ImageModel::add($id, $r['filename'], $r['thumb'], $alt);
        if ($cover === null) { $cover = $imgId; Project::setCover($id, $imgId); }
        $photoCount++;
    }
    echo "+ {$p['title']} (" . count($p['photos']) . " foto)\n";
}

echo "\nToplam " . count($PROJECTS) . " proje, $photoCount görsel içeri alındı.\n";
echo "Konum (ilçe) alanları bilerek boş bırakıldı — yönetim panelinden girildiğinde\n";
echo "ilgili proje o ilçenin sayfasında referans olarak görünmeye başlar.\n";
