<?php
declare(strict_types=1);
namespace App;

/**
 * Ankara yerel SEO içerik kaynağı.
 *
 * Buradaki her hizmet ve ilçe kaydının metni özgündür; şablondan çoğaltma
 * yapılmaz. Yeni ilçe eklerken aynı kuralı koruyun — aynı paragrafın ilçe adı
 * değiştirilerek tekrarlanması Google tarafından "doorway page" sayılır ve
 * sitenin tamamına zarar verir.
 */
final class Ankara
{
    public const CITY = 'Ankara';
    public const REGION_CODE = 'TR-06';

    /** Hizmet (kategori) landing sayfaları. Anahtar = kategori slug'ı = URL parçası. */
    public static function services(): array
    {
        return [
            'mutfak-dolabi' => [
                'name' => 'Mutfak Dolabı',
                'h1' => 'Ankara Mutfak Dolabı — Ölçüye Özel Üretim',
                'title' => 'Ankara Mutfak Dolabı | Ölçüye Özel Mutfak Dolabı Fiyatları',
                'description' => 'Ankara\'da ölçüye özel mutfak dolabı: lake, membran ve laminat kapak seçenekleri, Blum mekanizma, ücretsiz keşif ve 3D tasarım. Sezai Eren Mobilya.',
                'lead' => 'Ankara\'da mutfak dolabını hazır modüllerle değil, mutfağınızın gerçek ölçüsüne göre sıfırdan üretiyoruz. Baca çıkıntısı, eğri duvar, alçak tavan ya da dar koridor mutfak — hepsinin ayrı bir çözümü var ve keşifte bunu birlikte konuşuyoruz.',
                'sections' => [
                    ['Kapak ve gövde seçenekleri', 'Mat lake, parlak lake, membran, yivli (fluted) ve klasik profilli kapak üretiyoruz. Gövdede 18 mm birinci sınıf suya dayanıklı yonga levha, tezgah altı modüllerde nem bariyeri kullanıyoruz. Lake kapaklarda MDF üzerine fırın boya uygulanır; renk seçimi için RAL kartelasından çalışıyoruz.'],
                    ['Mekanizma ve iç düzen', 'Menteşe ve çekmece raylarında Blum, Hettich sınıfı yavaş kapanan sistemler kullanıyoruz. Köşe dolapları için karusel ya da magic corner, tencere için tandem çekmece, çöp için gömme kova, baharatlık ve kaşıklık bölmeleri iç düzene keşifte karar veriyoruz.'],
                    ['Tezgah, sırt paneli ve aydınlatma', 'Akrilik, çimstone/kuvars ve granit tezgah seçenekleri sunuyoruz. Sırt panelinde tezgahla aynı malzeme, cam ya da seramik uygulanabiliyor. Üst dolap altına gömme LED bant, vitrinli kapaklara iç aydınlatma standart olarak teklif ediliyor.'],
                    ['Ankara\'da süreç ve teslim', 'Keşif randevusunu genellikle 1-2 gün içinde veriyoruz. Ölçü sonrası 3 boyutlu çizim ve fiyat teklifi hazırlıyoruz; onaydan sonra ortalama üretim + montaj süresi mutfağın büyüklüğüne göre 2-4 haftadır. Montaj kendi ekibimizle yapılır, taşeron kullanmıyoruz.'],
                ],
                'faq' => [
                    ['Ankara\'da mutfak dolabı fiyatları neye göre değişiyor?', 'Fiyatı belirleyen üç şey var: metretül (dolabın uzunluğu), kapak malzemesi ve mekanizma sınıfı. Lake kapak membrana göre, kuvars tezgah akriliğe göre daha yüksek maliyetlidir. Ölçüsüz telefonla verilen rakamlar yanıltıcı olduğu için keşif sonrası kalem kalem yazılı teklif veriyoruz.'],
                    ['Keşif ve 3D çizim ücretli mi?', 'Hayır. Ankara içinde keşif, ölçü alma, 3 boyutlu tasarım ve fiyat teklifi ücretsizdir. Teklifi beğenmezseniz herhangi bir yükümlülüğünüz olmaz.'],
                    ['Eski mutfağın sökümü size mi ait?', 'Evet, isterseniz eski mutfağın sökümünü ve molozun tahliyesini biz üstleniyoruz. Bunu teklifte ayrı bir kalem olarak gösteriyoruz.'],
                    ['Mutfak dolabı ne kadar sürede teslim edilir?', 'Onaylı çizimden sonra tipik bir daire mutfağı 2-4 hafta içinde üretilip monte edilir. Özel renk lake ve ithal mekanizma tercihlerinde süre bir hafta kadar uzayabilir.'],
                ],
            ],
            'gardirop' => [
                'name' => 'Gardırop',
                'h1' => 'Ankara Gardırop — Tavana Kadar Ölçüye Özel',
                'title' => 'Ankara Gardırop | Sürgülü ve Kapaklı Ölçüye Özel Gardırop',
                'description' => 'Ankara\'da ölçüye özel gardırop: sürgülü veya kapaklı, tavana kadar, aynalı ve LED iç aydınlatmalı. Ücretsiz keşif, yerinde montaj. Sezai Eren Mobilya.',
                'lead' => 'Hazır gardırop odanın ölçüsüne değil, kutunun ölçüsüne göre üretilir; yanında ve üstünde kaybedilen boşluk çoğu yatak odasında yarım dolaba denk gelir. Biz duvardan duvara, zeminden tavana çalışıyoruz.',
                'sections' => [
                    ['Sürgülü mü, kapaklı mı?', 'Yatak ile dolap arası 90 cm\'den darsa sürgülü kapak öneriyoruz; kapak açılışı için yer gerekmez. Alan yeterliyse kapaklı gardırop hem daha ekonomik hem de dolabın tamamını tek seferde görmenizi sağlar. Karma çözüm de mümkün: orta bölüm sürgülü, uçlar kapaklı.'],
                    ['İç düzen', 'Askılık yüksekliği, çekmece sayısı, pantolonluk, kravatlık, valiz ve mevsimlik eşya için tavan üstü ek dolap sırası — iç düzeni gardırobu kullanacak kişiye göre kurguluyoruz. Çekmecelerde frenli ray, kapaklarda yavaş kapanan menteşe standarttır.'],
                    ['Ayna, yiv ve kemer detayları', 'Boy aynası, kafes/baklava motifli ayna, dikey yivli (fluted) kapak ve kemerli üst kesim en çok tercih edilen detaylar. Ayna kapakların arkasına güvenlik filmi çekiyoruz; kırılma durumunda cam dağılmaz.'],
                    ['Dar ve eğri odalarda çözüm', 'Ankara\'nın eski apartmanlarında duvarlar çoğu zaman gönyesizdir. Gövdeyi duvara göre kesip, aradaki farkı kapakla değil dolgu profiliyle kapatıyoruz; böylece kapaklar yıllar sonra da düzgün kapanır.'],
                ],
                'faq' => [
                    ['Tavana kadar gardırop yaptırmak mantıklı mı?', 'Evet. Tavan ile dolap arasındaki 40-50 cm hem toz tutar hem de ölü alandır. Üst sıraya mevsimlik eşya ve valiz için ek dolap koyduğumuzda depolama yaklaşık üçte bir artıyor.'],
                    ['Sürgülü gardırop rayı zamanla bozulur mu?', 'Alt raylı ucuz sistemlerde bozulur. Biz alttan tekerlekli, frenli ve ayarlanabilir sistem kullanıyoruz; kapak sarkarsa vidayla yeniden hizalanabiliyor.'],
                    ['Mevcut yatak odası takımıma renk uydurabilir misiniz?', 'Çoğunlukla evet. Mevcut mobilyanızdan bir fotoğraf ve mümkünse küçük bir parça gördüğümüzde lake renginde ya da kaplama deseninde eşleştirme yapıyoruz.'],
                ],
            ],
            'vestiyer' => [
                'name' => 'Vestiyer',
                'h1' => 'Ankara Vestiyer — Giriş Holüne Ölçüye Özel',
                'title' => 'Ankara Vestiyer | Ayakkabılık ve Askılıklı Portmanto',
                'description' => 'Ankara\'da ölçüye özel vestiyer ve portmanto: ayakkabılık, askılık, boy aynası tek gövdede. Dar holler için özel çözümler. Sezai Eren Mobilya.',
                'lead' => 'Vestiyer, evin ilk görülen mobilyası ve genelde en dar alana sığması gereken parça. Ankara dairelerinde giriş holü çoğu zaman 100-140 cm; hazır ürün ya taşar ya da boşluk bırakır. Ölçüye özel üretimde bu alan santimi santimine değerlendirilir.',
                'sections' => [
                    ['Ayakkabılık kapasitesi', 'Eğimli ayakkabılık rafı ile aynı derinlikte iki katı çift depolanır. Bot ve çizme için yüksek bölme, sık kullanılan ayakkabı için alt boşluk bırakıyoruz — bu boşluk holü görsel olarak da ferahlatıyor.'],
                    ['Askılık, oturak ve ayna', 'Üstte kapalı askılık bölmesi, ortada çanta ve anahtar için açık niş, altta ayakkabı giymek için oturak — üçünü tek gövdede birleştiriyoruz. Boy aynası kapak yüzeyine ya da yan panele uygulanabilir.'],
                    ['Dar hollerde derinlik hesabı', '35 cm derinlik askı için yetmez, 60 cm ise dar holü tıkar. Yandan askılı (kancalı) çözümle 38-40 cm derinlikte palto asılabilen bir vestiyer üretiyoruz.'],
                ],
                'faq' => [
                    ['Vestiyer ile portmanto arasındaki fark ne?', 'Portmanto genelde açık askılıktır; vestiyer ise kapaklı gövde, ayakkabılık ve aynayı birleştiren kapalı bir üniteyi tarif eder. Biz ikisinin karması çözümleri de üretiyoruz.'],
                    ['Kombine vestiyer + ayakkabılık ne kadar sürede yapılır?', 'Tek parça bir vestiyer genellikle ölçü onayından sonra 10-15 gün içinde monte edilir.'],
                ],
            ],
            'tv-unitesi' => [
                'name' => 'TV Ünitesi',
                'h1' => 'Ankara TV Ünitesi — Salona Özel Tasarım',
                'title' => 'Ankara TV Ünitesi | Kitaplıklı ve Ölçüye Özel TV Duvarı',
                'description' => 'Ankara\'da ölçüye özel TV ünitesi ve TV duvarı: kablo gizleme, gömme LED, kitaplık entegrasyonu. Ücretsiz keşif. Sezai Eren Mobilya.',
                'lead' => 'TV ünitesi artık tek başına bir sehpa değil, salonun bütün duvarını kurgulayan bir eleman. Kablo, modem, oyun konsolu ve klima borusu gibi görünmesini istemediğiniz her şeyi tasarımın içine gizliyoruz.',
                'sections' => [
                    ['Kablo ve cihaz gizleme', 'TV arkasına gömme kablo kanalı, alt modüle havalandırmalı modem bölmesi ve priz grubu yerleştiriyoruz. Askı aparatını panele değil duvara sabitliyoruz; panel yalnızca görsel yüzey olarak kalıyor.'],
                    ['Kitaplık ve vitrin entegrasyonu', 'TV ünitesini kitaplık, vitrin ya da şömine nişiyle tek gövdede birleştirebiliyoruz. Açık raflarda arka panele ceviz ya da kumaş doku vererek derinlik kazandırıyoruz.'],
                    ['Aydınlatma', 'Panel arkasına gizli LED bant (bias lighting) hem göz yorgunluğunu azaltır hem de duvarı öne çıkarır. Raf altlarına ayrı devreden dimmerli aydınlatma ekleyebiliyoruz.'],
                ],
                'faq' => [
                    ['Askılı TV ünitesi duvarı taşır mı?', 'Tuğla ve betonarme duvarda kimyasal dübelle taşır; gaz beton veya alçıpan duvarda arkaya çelik profil takviyesi yapıyoruz. Duvar tipini keşifte kontrol ediyoruz.'],
                    ['Mevcut salon mobilyamla uyumlu olur mu?', 'Renk ve kaplama eşleştirmesi için mevcut mobilyanızın fotoğrafını keşifte inceliyoruz; benzer ton ve doku üzerinden çizim hazırlıyoruz.'],
                ],
            ],
            'banyo-dolabi' => [
                'name' => 'Banyo Dolabı',
                'h1' => 'Ankara Banyo Dolabı — Neme Dayanıklı Üretim',
                'title' => 'Ankara Banyo Dolabı | Lavabo Altı ve Boy Dolabı',
                'description' => 'Ankara\'da ölçüye özel banyo dolabı: neme dayanıklı gövde, lavabo altı, aynalı üst dolap ve boy dolabı. Sezai Eren Mobilya.',
                'lead' => 'Banyoda mobilyayı bitiren şey tasarım değil nemdir. Bu yüzden banyo dolaplarında gövde malzemesini ve kenar bantlamayı diğer odalardan farklı seçiyoruz.',
                'sections' => [
                    ['Neme dayanıklı malzeme', 'Gövdede suya dayanıklı (yeşil) MDF ya da kompakt lamine, kapakta lake veya PVC membran kullanıyoruz. Tüm kesim kenarlarını sıcak tutkalla bantlıyor, lavabo çevresine silikon fitil çekiyoruz.'],
                    ['Lavabo altı ve tesisat', 'Sifon ve boru geçişlerini gövdeye fabrikada değil, yerinde ölçüp kesiyoruz; böylece boşluk minimumda kalıyor. Çekmeceli lavabo altı isteyen için U kesimli çekmece üretiyoruz.'],
                    ['Ayna, dolap ve aydınlatma', 'Aynalı üst dolap, kenardan aydınlatmalı ayna ve makyaj için ek priz seçenekleri sunuyoruz. Küçük banyolarda kapı arkası boy dolabı ciddi depolama kazandırıyor.'],
                ],
                'faq' => [
                    ['Banyo dolabı kaç yıl dayanır?', 'Doğru malzeme ve düzgün havalandırma ile 10 yılın üzerinde. Şişmenin bir numaralı sebebi bantsız kesim kenarı ve sızdıran sifondur; ikisini de montajda kontrol ediyoruz.'],
                    ['Mevcut lavabomu kullanabilir miyim?', 'Evet, mevcut lavabonun ölçüsünü alıp dolabı ona göre üretiyoruz.'],
                ],
            ],
            'ic-kapi-duvar-paneli' => [
                'name' => 'İç Kapı & Duvar Paneli',
                'h1' => 'Ankara İç Kapı ve Ahşap Duvar Paneli',
                'title' => 'Ankara İç Kapı | Ahşap Duvar Paneli ve Lambri',
                'description' => 'Ankara\'da ölçüye özel iç kapı, ahşap duvar paneli ve lambri uygulaması. Lamelli bölücü, yivli panel, camlı kapı. Sezai Eren Mobilya.',
                'lead' => 'Kapı ve duvar paneli, bir evin kalitesinin en çabuk ele veren iki detayı. Hazır kapıda kasa ile duvar arasındaki fark silikonla kapatılır; biz kasayı duvara göre üretip pervazı yerinde kesiyoruz.',
                'sections' => [
                    ['Kapı tipleri ve kaplama', 'Düz (flush), yivli, camlı ve panelli iç kapı üretiyoruz. Yüzeyde lake, PVC membran veya doğal ahşap kaplama kullanılabiliyor. Kapı kanadını dolu petek dolgulu üretiyoruz — içi boş kapının aksine ses geçirmiyor ve zamanla eğrilmiyor.'],
                    ['Ahşap lamel ve bölücü', 'Dikey lamel (çıta) uygulaması, salonla holü duvar örmeden ayırmanın en temiz yolu. Lamellerin arasını ışık geçirecek kadar açık bırakıp arkasına gizli LED koyduğumuzda hol karanlık kalmıyor. Taş veya beton dokulu duvarla birleştiğinde etkisi belirgin biçimde artıyor.'],
                    ['Duvar paneli ve lambri', 'Yivli (fluted) panel, klasik çıtalı lambri ve tam boy ahşap kaplama üretiyoruz. TV duvarı, yatak başı ve merdiven boşluğu en sık uygulanan yerler. Panelin arkasına havalandırma boşluğu bırakıyoruz; nemli duvarda panel şişmesinin sebebi bu boşluğun bırakılmamasıdır.'],
                    ['Ölçü ve montaj', 'Kaba inşaat bitmeden kapı ölçüsü alınmaz — duvar sıvası ve zemin kaplaması tamamlandıktan sonra keşfe geliyoruz. Montajda kasa terazisini lazerle kontrol ediyoruz; kapının kendiliğinden açılıp kapanması bu ayarın atlanmasından kaynaklanır.'],
                ],
                'faq' => [
                    ['Mevcut kapı kasamı koruyup sadece kanadı değiştirebilir miyim?', 'Kasa sağlam ve terazideyse evet, sadece kanat ve pervaz yenilenebilir. Keşifte kasanın gönyesini kontrol ediyoruz; bozuksa yeni kanat da düzgün kapanmaz.'],
                    ['Ahşap lamel bölücü ne kadar yer kaplar?', 'Tipik bir lamel bölücü 4-6 cm derinliktedir; duvar örmenin aksine neredeyse hiç alan kaybettirmez ve ışığı kesmez.'],
                    ['Duvar paneli nemli duvarda şişer mi?', 'Panelin arkasına havalandırma boşluğu bırakılır ve duvara doğrudan yapıştırılmazsa şişmez. Dış duvarda ayrıca nem bariyeri uyguluyoruz.'],
                ],
            ],
            'ahsap-merdiven' => [
                'name' => 'Ahşap Merdiven',
                'h1' => 'Ankara Ahşap Merdiven — Masif Basamak Kaplama',
                'title' => 'Ankara Ahşap Merdiven | Masif Meşe Basamak Kaplama',
                'description' => 'Ankara\'da masif ahşap merdiven basamağı ve küpeşte uygulaması. Beton merdiven üzerine meşe kaplama, ölçüye özel kesim. Sezai Eren Mobilya.',
                'lead' => 'Dubleks dairelerin ve villaların beton merdiveni, masif ahşap basamak kaplamasıyla evin en dikkat çeken parçasına dönüşüyor. Her basamağın ölçüsü ayrı alınır — beton merdivende iki basamağın birebir aynı olması neredeyse hiç görülmez.',
                'sections' => [
                    ['Masif basamak kaplama', 'Beton merdiven üzerine 3-4 cm kalınlığında masif meşe basamak uyguluyoruz. Rıht (dikey yüzey) beyaz lake bırakıldığında ahşap öne çıkıyor; tamamı ahşap istendiğinde rıhtı da aynı malzemeden üretiyoruz.'],
                    ['Ölçü ve kesim', 'Her basamak için ayrı şablon alıyoruz. Duvara oturan kenarları gönyesiz duvara göre kesiyor, ön kenara yuvarlatma (bullnose) veya pah veriyoruz — çıplak ayakla kullanımda fark ediliyor.'],
                    ['Küpeşte ve korkuluk', 'Masif ahşap küpeşteyi ferforje veya cam korkulukla birleştirebiliyoruz. Küpeşte yüksekliğini ve tutuş kalınlığını evde yaşayanlara göre ayarlıyoruz.'],
                    ['Yüzey koruması', 'Basamaklarda parke cilası değil, aşınmaya dayanıklı su bazlı merdiven verniği kullanıyoruz. Kaymayı azaltmak için mat verniği tercih ediyoruz.'],
                ],
                'faq' => [
                    ['Beton merdivenin üzerine ahşap kaplanabilir mi?', 'Evet, en sık yaptığımız uygulama bu. Beton yüzeyi tesviye edip masif basamağı üzerine sabitliyoruz; merdiven yüksekliği basamak kalınlığı kadar (3-4 cm) artıyor, bunu ilk ve son basamakta dengeliyoruz.'],
                    ['Ahşap merdiven gıcırdar mı?', 'Doğru sabitlenirse gıcırdamaz. Gıcırtının sebebi basamağın betona tam oturmaması ya da yalnızca kenardan yapıştırılmasıdır; biz tesviye harcı ve noktasal sabitleme birlikte kullanıyoruz.'],
                ],
            ],
            'ozel-tasarim' => [
                'name' => 'Özel Tasarım',
                'h1' => 'Ankara Özel Tasarım Mobilya',
                'title' => 'Ankara Özel Tasarım Mobilya | Kitaplık, Çalışma Masası, Niş',
                'description' => 'Ankara\'da özel tasarım mobilya: kitaplık, çalışma masası, giyinme odası, niş ve depo çözümleri. Ölçüye özel üretim. Sezai Eren Mobilya.',
                'lead' => 'Kataloğa sığmayan işler bu başlık altında. Merdiven altı depo, çatı katı eğimli dolap, giyinme odası, ofis için toplantı masası ya da çocuk odası için ranza — çizimi birlikte yapıyoruz.',
                'sections' => [
                    ['Kitaplık ve çalışma alanı', 'Duvardan duvara kitaplık, gömme çalışma masası ve kablo düzeni bir arada tasarlanır. Raf açıklığını kitap ağırlığına göre hesaplıyoruz; 90 cm üzeri açıklıklarda raf ortadan sarkar, bunu takviye profille çözüyoruz.'],
                    ['Giyinme odası', 'Küçük bir oda ya da yatak odasının bir bölümü, açık modüllerle giyinme odasına dönüşebiliyor. Ada çekmece, aynalı panel ve LED askılık en sık istenen eklentiler.'],
                    ['Eğimli ve zor alanlar', 'Çatı katı eğimi, merdiven altı üçgen boşluk ve kolon çıkıntıları — bu alanlar hazır mobilyayla değerlendirilemez. Şablon alıp birebir kesim yapıyoruz.'],
                ],
                'faq' => [
                    ['Aklımdaki tasarımın çizimi yoksa ne olur?', 'Fotoğraf, ekran görüntüsü ya da elle çizilmiş bir kroki yeterli. Keşifte ölçüyü alıp 3 boyutlu çizimi biz hazırlıyoruz.'],
                    ['Ofis ve mağaza işleri yapıyor musunuz?', 'Evet. Ofis, mağaza ve kafe için tezgah, raf sistemi ve depolama üretiyoruz; referanslarımızı keşifte paylaşabiliriz.'],
                ],
            ],
        ];
    }

    /** Gerçekten hizmet verilen ilçeler. Her kaydın metni ilçeye özgüdür. */
    public static function districts(): array
    {
        return [
            'cankaya' => [
                'name' => 'Çankaya',
                'title' => 'Çankaya Mobilya | Mutfak Dolabı, Gardırop ve Vestiyer',
                'description' => 'Çankaya\'da ölçüye özel mutfak dolabı, gardırop, vestiyer ve TV ünitesi. Ücretsiz keşif, 3D tasarım, yerinde montaj. Sezai Eren Mobilya.',
                'lead' => 'Çankaya\'da Ayrancı ve Kavaklıdere\'nin yüksek tavanlı eski dairelerinden Çayyolu ve Oran\'ın yeni sitelerine kadar çok farklı yapı stoğuna iş yaptık. İkisinin de kendine göre bir zorluğu var: eskilerde duvar gönyesiz, yenilerde ise kolon ve tesisat şaftı dolabın tam ortasına denk geliyor.',
                'neighborhoods' => ['Ayrancı', 'Kavaklıdere', 'Çayyolu', 'Ümitköy', 'Oran', 'Birlik', 'Yıldız', 'Dikmen', 'Bahçelievler'],
                'body' => [
                    'Ayrancı ve Kavaklıdere\'deki 3 metreye yaklaşan tavanlarda mutfak üst dolabını tek sıra bırakmak alanı ziyan ediyor; ikinci sırayı mevsimlik eşya için ek kabin olarak kurguluyoruz. Yüksek tavan aynı zamanda gardıropta tavana kadar çıkma imkânı veriyor.',
                    'Çayyolu, Ümitköy ve Oran\'daki sitelerde en sık karşılaştığımız istek açık mutfağın salonla ilişkisini düzenlemek: tezgahı bar formunda uzatmak, davlumbazı gizlemek ve mutfak kapaklarının salon mobilyasıyla aynı tonda olmasını sağlamak.',
                    'Siteler\'deki atölyemizden Çankaya\'ya ulaşım kısa olduğu için keşif randevusunu genellikle aynı hafta içinde veriyoruz. Site yönetimlerinin asansör ve montaj saati kurallarına uyuyor, gerekli izin yazışmasını biz takip ediyoruz.',
                ],
            ],
            'kecioren' => [
                'name' => 'Keçiören',
                'title' => 'Keçiören Mobilya | Mutfak Dolabı ve Gardırop',
                'description' => 'Keçiören\'de ölçüye özel mutfak dolabı, gardırop ve vestiyer. Ücretsiz keşif ve ölçü, yerinde montaj. Sezai Eren Mobilya.',
                'lead' => 'Keçiören, Ankara\'da en çok iş yaptığımız ilçelerden biri. Etlik, Kalaba ve Sanatoryum çevresindeki daireler genellikle orta metrekarede ve mutfaklar kapalı-dar planlı; burada kazanç, santimetre hesabıyla planlanan depolamadan çıkıyor.',
                'neighborhoods' => ['Etlik', 'Kalaba', 'Sanatoryum', 'Aşağı Eğlence', 'Bağlum', 'Ovacık', 'Güçlükaya'],
                'body' => [
                    'Dar ve uzun (koridor tipi) Keçiören mutfaklarında iki sıra dolap arasında en az 110 cm boşluk bırakmak gerekiyor; bunun altına inildiğinde iki kişi aynı anda mutfakta çalışamıyor. Derinliği 60 yerine 50 cm tutulan tezgah altı modüller genellikle bu sorunu çözüyor.',
                    'Etlik ve Kalaba\'daki daha eski binalarda kolon ve baca çıkıntıları neredeyse kuraldır. Bu çıkıntıları kapatmak yerine niş olarak değerlendirip baharatlık ya da açık raf haline getiriyoruz.',
                    'Bağlum ve Ovacık gibi ilçenin kuzey mahallelerine de keşfe gidiyoruz; ek nakliye ücreti almıyoruz.',
                ],
            ],
            'yenimahalle' => [
                'name' => 'Yenimahalle',
                'title' => 'Yenimahalle Mobilya | Ölçüye Özel Mutfak ve Gardırop',
                'description' => 'Yenimahalle ve Batıkent\'te ölçüye özel mutfak dolabı, gardırop, TV ünitesi. Ücretsiz keşif. Sezai Eren Mobilya.',
                'lead' => 'Batıkent, Demetevler ve Ostim çevresini kapsayan Yenimahalle, hem toplu konut hem de yeni site yapısının bir arada olduğu bir ilçe. Toplu konutlarda daire planları tekrar ettiği için burada üretilmiş çözümlerimiz oturmuş durumda.',
                'neighborhoods' => ['Batıkent', 'Demetevler', 'İvedik', 'Ostim', 'Şentepe', 'Ragıp Tüzün', 'Çamlıca'],
                'body' => [
                    'Batıkent\'teki kooperatif bloklarında mutfak ölçüleri blok bazında büyük ölçüde aynı. Daha önce aynı blokta çalıştıysak çizim aşamasını hızlandırabiliyor, keşifte size önceki projeden görsel gösterebiliyoruz.',
                    'Demetevler ve Şentepe\'de sık istenen iş, mutfağı komple yenilemek yerine sadece kapak ve tezgah değişimi. Gövde sağlamsa bunu da yapıyoruz; maliyeti komple yenilemenin belirgin biçimde altında kalıyor.',
                    'Ostim ve İvedik\'teki ofis ve işyerleri için tezgah, arşiv dolabı ve mutfak nişi üretimi yapıyoruz; işyeri montajlarını mesai dışına ayarlayabiliyoruz.',
                ],
            ],
            'mamak' => [
                'name' => 'Mamak',
                'title' => 'Mamak Mobilya | Mutfak Dolabı, Gardırop, Vestiyer',
                'description' => 'Mamak\'ta ölçüye özel mutfak dolabı, gardırop ve vestiyer üretimi. Ücretsiz keşif ve ölçü, yerinde montaj. Sezai Eren Mobilya.',
                'lead' => 'Mamak, atölyemizin bulunduğu Siteler\'e en yakın ilçelerden biri; portföyümüzdeki işlerin önemli bir kısmı burada. Kıbrısköy, Akdere ve Abidinpaşa çevresinde tamamladığımız gardırop ve mutfak projelerini projeler sayfasında görebilirsiniz.',
                'neighborhoods' => ['Kıbrısköy', 'Akdere', 'Abidinpaşa', 'Şahintepe', 'Türközü', 'Kutludüğün', 'Boğaziçi'],
                'body' => [
                    'Yakınlık pratikte şu anlama geliyor: ölçü sonrası bir detayı yerinde teyit etmek gerektiğinde ekstra gün kaybı olmuyor, montaj sonrası ayar talebine aynı gün dönebiliyoruz.',
                    'Mamak\'ta müstakil ve az katlı yapı oranı yüksek. Bu yapılarda tavan yüksekliği kat kat değişebiliyor; her odanın tavanını ayrı ölçüp dolabı ona göre üretiyoruz, tek ölçüyle bütün eve üretim yapmıyoruz.',
                    'İlçedeki yenileme işlerinde eski mobilyanın sökümü ve tahliyesini de üstleniyoruz.',
                ],
            ],
            'etimesgut' => [
                'name' => 'Etimesgut',
                'title' => 'Etimesgut Mobilya | Ölçüye Özel Mutfak Dolabı ve Gardırop',
                'description' => 'Etimesgut ve Eryaman\'da ölçüye özel mutfak dolabı, gardırop ve TV ünitesi. Ücretsiz keşif. Sezai Eren Mobilya.',
                'lead' => 'Eryaman ve Elvankent\'in ağırlıklı olarak site yapısında olduğu Etimesgut\'ta işlerin çoğu yeni daire teslimlerinde başlıyor: müteahhit mutfağı sökülüp yerine ölçüye özel üretim yapılıyor.',
                'neighborhoods' => ['Eryaman', 'Elvankent', 'Bağlıca', 'Ahi Mesut', 'Göksu', 'Süvari'],
                'body' => [
                    'Yeni teslim dairelerde standart mutfak genelde 3-4 metretül ve depolama yetersiz kalıyor. Aynı alanda tavana kadar çıkarak ve köşeyi magic corner ile değerlendirerek kullanılabilir hacmi belirgin biçimde artırıyoruz.',
                    'Eryaman\'daki sitelerde montaj için önceden yönetim izni ve asansör rezervasyonu gerekiyor; bu yazışmayı biz yapıyor, montaj gününü size teyit ettikten sonra planlıyoruz.',
                    'Bağlıca ve Göksu tarafındaki dubleks dairelerde merdiven altı depo ve çatı katı eğimli dolap talebi sık geliyor; bu alanlar için şablon alarak birebir kesim yapıyoruz.',
                ],
            ],
            'altindag' => [
                'name' => 'Altındağ',
                'title' => 'Altındağ Mobilya | Siteler Atölyemizden Ölçüye Özel Üretim',
                'description' => 'Altındağ ve Siteler\'de ölçüye özel mutfak dolabı, gardırop, vestiyer. Atölyemiz Siteler\'de. Sezai Eren Mobilya.',
                'lead' => 'Atölyemiz Siteler\'de, yani Altındağ sınırları içinde. İlçedeki işlerde keşif, üretim ve montaj arasındaki mesafe en kısa; isterseniz üretim aşamasında atölyeye gelip malzemeyi ve işçiliği yerinde görebilirsiniz.',
                'neighborhoods' => ['Siteler', 'Ulus', 'Aydınlıkevler', 'Hasköy', 'Solfasol', 'Karapürçek'],
                'body' => [
                    'Siteler\'de mobilyacı çok, ama iş çoğu zaman parça parça farklı atölyelere dağıtılıyor. Biz kesim, kenar bantlama, montaj ve yerinde kurulumu tek elden yapıyoruz; sorumluluk dağılmıyor.',
                    'Ulus ve Aydınlıkevler\'deki eski yapılarda kapı ve merdiven boşlukları dar. Büyük gövdeleri parçalı üretip yerinde birleştirerek bu sorunu çözüyoruz — hazır mobilyada mümkün olmayan bir avantaj.',
                    'Malzemeyi kendiniz görmek isterseniz keşif öncesi atölyeye davet ediyoruz: kapak numunelerini, kenar bandını ve mekanizmaları elle inceleyebilirsiniz.',
                ],
            ],
            'sincan' => [
                'name' => 'Sincan',
                'title' => 'Sincan Mobilya | Mutfak Dolabı ve Gardırop',
                'description' => 'Sincan\'da ölçüye özel mutfak dolabı, gardırop ve vestiyer. Ücretsiz keşif, ek nakliye ücreti yok. Sezai Eren Mobilya.',
                'lead' => 'Sincan Ankara\'nın batı ucunda ve pek çok atölye buraya keşfe gitmek istemez. Biz gidiyoruz ve mesafe için ek ücret talep etmiyoruz; tek fark, keşif randevusunu belirli günlerde toplu planlamamız.',
                'neighborhoods' => ['Fatih', 'Yenikent', 'Törekent', 'Plevne', 'Andiçen', 'Osmanlı'],
                'body' => [
                    'İlçede toplu konut ve TOKİ yapısı yaygın; daire planları tekrar ettiği için daha önce aynı tipte ürettiğimiz çözümü size örnek olarak gösterebiliyoruz.',
                    'Uzak mesafede en kritik konu montaj sonrası servis. Kapak ayarı ya da menteşe değişimi gerektiğinde Sincan taleplerini haftalık bir servis gününde topluyor, bekletmeden çözüyoruz.',
                    'Yenikent ve Törekent tarafındaki müstakil evlerde tüm ev mobilyasını tek seferde planlamak nakliye ve montaj açısından avantajlı oluyor; teklifte bunu toplu iş olarak fiyatlandırıyoruz.',
                ],
            ],
            'golbasi' => [
                'name' => 'Gölbaşı',
                'title' => 'Gölbaşı Mobilya | Villa ve Daire İçin Ölçüye Özel Mobilya',
                'description' => 'Gölbaşı\'nda ölçüye özel mutfak dolabı, gardırop, giyinme odası ve özel tasarım mobilya. Ücretsiz keşif. Sezai Eren Mobilya.',
                'lead' => 'Gölbaşı\'nda villa ve bahçeli müstakil yapı oranı yüksek olduğu için buradaki işler genellikle tek oda değil, evin tamamını kapsıyor: mutfak, giyinme odası, kiler ve çalışma odası birlikte planlanıyor.',
                'neighborhoods' => ['İncek', 'Karagedik', 'Hacılar', 'Şafak', 'Bahçelievler', 'Taşpınar'],
                'body' => [
                    'İncek çevresindeki villalarda en sık kurduğumuz düzen mutfak + ayrı kiler kombinasyonu. Kileri mutfakla aynı kapak malzemesinden ama daha ekonomik iç düzenle üretmek toplam maliyeti ciddi biçimde düşürüyor.',
                    'Giyinme odası taleplerinde odanın havalandırmasını da hesaba katıyoruz; nem alan bir odada açık modül yerine kapaklı çözüm öneriyoruz.',
                    'Bahçe katı ve teras bağlantılı alanlarda nem daha yüksek olduğundan bu bölümlerde banyo dolabı sınıfı neme dayanıklı gövde kullanıyoruz.',
                ],
            ],
        ];
    }

    public static function service(string $slug): ?array
    {
        $s = self::services()[$slug] ?? null;
        return $s ? $s + ['slug' => $slug, 'path' => '/ankara-' . $slug] : null;
    }

    public static function district(string $slug): ?array
    {
        $d = self::districts()[$slug] ?? null;
        return $d ? $d + ['slug' => $slug, 'path' => '/ankara/' . $slug . '-mobilya'] : null;
    }

    /** Sitemap ve iç linkler için tüm landing yolları. */
    public static function paths(): array
    {
        $out = [];
        foreach (array_keys(self::services()) as $s) $out[] = '/ankara-' . $s;
        foreach (array_keys(self::districts()) as $d) $out[] = '/ankara/' . $d . '-mobilya';
        return $out;
    }

    /** LocalBusiness areaServed listesi. */
    public static function areaServedNames(): array
    {
        return array_map(fn($d) => $d['name'], array_values(self::districts()));
    }
}
