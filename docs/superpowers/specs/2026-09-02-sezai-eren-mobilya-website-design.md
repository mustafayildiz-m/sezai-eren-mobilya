# Sezai Eren Mobilya – Web Sitesi Tasarım Spec'i

Tarih: 2026-09-02

## Amaç
Ankara'da mutfak dolabı, vestiyer, gardırop ve özel mobilya üreten Sezai Eren'i tanıtan, SEO'su güçlü, şık ve modern bir vitrin sitesi. Sezai Eren kod bilmeden yönetim panelinden proje/resim yükleyebilir ve gelen teklif taleplerini görebilir.

## Teknoloji
- PHP 8.3 (framework yok, küçük bir dahili yönlendirici), Apache, mod_rewrite.
- SQLite (PDO). Veritabanı dosyası `storage/database.sqlite`.
- Resim işleme: GD (resize + WebP). Yüklemeler `public/uploads/`.
- Tailwind CSS: geliştirme aşamasında bir kez derlenir, `public/assets/app.css` olarak commit edilir. Canlıda build yok.
- Vanilla JS: scroll animasyonları (IntersectionObserver), lightbox, galeri filtresi, mobil menü. Harici JS kütüphanesi yok.
- Docker: tek servis (`php:8.3-apache`), volume'lar: `storage/`, `public/uploads/`. Port 8080.
- Paylaşımlı hostinge FTP ile atılabilir olmalı (Composer çalışma zamanında gerekmez; PHPUnit yalnızca dev bağımlılığı).

## Dizin Yapısı
```
sezai-eren-mobilya/
  docker-compose.yml, Dockerfile, .env.example
  public/            # Apache DocumentRoot
    index.php        # tek giriş noktası
    .htaccess        # temiz URL
    assets/          # app.css, app.js, statik görseller
    uploads/         # yüklenen resimler (volume)
  src/
    bootstrap.php    # autoload, config, db
    Router.php
    Database.php     # PDO + migrasyon
    Image.php        # resize/webp
    Auth.php         # panel oturumu
    Seo.php          # meta/schema üreticileri
    Controllers/     # Home, Projects, Pages, Quote, Admin*
    Models/          # Project, Category, Image, Quote, Setting
  templates/         # PHP şablonları (layout, sayfalar, admin)
  storage/           # database.sqlite, rate-limit dosyaları
  scripts/seed.php   # örnek veri + Unsplash görselleri
  tests/             # PHPUnit
  docs/superpowers/specs/
```

## Veri Modeli
- `categories`: id, name, slug, sort_order
- `projects`: id, category_id, title, slug, description, location (semt), cover_image_id, is_featured, sort_order, created_at
- `images`: id, project_id, filename, alt, sort_order
- `quotes`: id, name, phone, email?, job_type, message, photo?, created_at, is_read
- `settings`: key, value (phone, whatsapp, address, instagram, email, working_hours, map_embed)
- `users`: id, username, password_hash (tek kayıt)

## Sayfalar ve URL'ler
- `/` Ana sayfa: hero (Ken Burns efekti, kayan slogan), hizmet kartları (6 kategori), öne çıkan projeler, sayaçlar (yıl, proje, müşteri), yorumlar (statik 3 adet), teklif çağrısı.
- `/projeler` ve `/projeler?kategori=mutfak`: filtreli galeri, lightbox.
- `/projeler/{slug}`: proje detay, galeri, ilgili projeler, teklif çağrısı.
- `/hakkimizda`, `/iletisim` (WhatsApp, harita iframe, saatler).
- `/teklif-al` GET form, POST kayıt. Doğrulama: ad ve telefon zorunlu, honeypot alanı, IP başına 5 dk'da 3 gönderi sınırı. Fotoğraf isteğe bağlı (max 5 MB, jpg/png/webp). Başarıda teşekkür + WhatsApp ile devam et butonu.
- `/sitemap.xml`, `/robots.txt`.
- 404 sayfası.

## Yönetim Paneli (`/yonetim`)
- Giriş: `/yonetim/giris`, `password_verify`, session, CSRF token tüm POST'larda.
- Panel ana: özet sayılar, okunmamış teklifler.
- Projeler: liste, ekle/düzenle/sil, çoklu resim yükleme (drag-drop, `fetch` ile), kapak seçimi, resim sıralama/silme, öne çıkar.
- Kategoriler: ekle/düzenle/sil/sırala.
- Teklifler: liste, detay, okundu işaretle, sil.
- Ayarlar: iletişim bilgileri ve harita.
- İlk kullanıcı `.env`'deki `ADMIN_USER`/`ADMIN_PASSWORD` ile migrasyonda oluşturulur.

## Resim İşleme
Yükleme → doğrulama (MIME, boyut) → GD ile 1600px genişliğe küçült → WebP (kalite 82) → ayrıca 480px thumbnail. Dosya adı: `{uuid}.webp`, `{uuid}_thumb.webp`. Orijinal saklanmaz.

## Tasarım Dili
- Palet: ceviz `#2B1D14`, koyu zemin `#1A130E`, krem `#F5EFE6`, bakır vurgu `#B87333`, açık bej `#E8DCC8`.
- Fontlar: başlık Cormorant Garamond (serif), gövde Inter. Google Fonts.
- Animasyon: `.reveal` sınıfı ile scroll'da fade-up, kartlarda hover scale + gölge, hero'da yavaş zoom, sayaçlar görünürken sayar, sayfa yüklenirken kısa fade. `prefers-reduced-motion` saygı.
- Mobil öncelikli, responsive.

## SEO
- Her sayfa: title, description, canonical, Open Graph, Twitter card.
- JSON-LD: `LocalBusiness` (ana ve iletişim), `Product`/`ImageObject` (proje detay), `BreadcrumbList`.
- Anahtar kelime odağı: "Ankara mutfak dolabı", "Ankara vestiyer", "özel tasarım mobilya Ankara" + semtler.
- Tüm resimlerde alt, `loading="lazy"`, width/height.
- sitemap.xml dinamik, robots.txt panel yollarını engeller.

## Örnek İçerik
`scripts/seed.php`: 6 kategori, ~12 proje, Unsplash'tan indirilen ticari kullanıma serbest görseller (`assets/seed/` içine indirilip işlenir). İletişim bilgileri yer tutucu.

## Güvenlik
Prepared statements, CSRF, session cookie httponly/samesite, yükleme MIME kontrolü, `.htaccess` ile `storage/` erişimi kapalı (public dışında zaten), panel brute-force için giriş hız sınırı.

## Test
PHPUnit: Router eşleştirme, slug üretimi, Image resize/webp, Quote doğrulama ve rate limit, Auth password doğrulama, Seo JSON-LD çıktısı. Panel akışı elle test.

## Kapsam Dışı
Blog, çoklu dil, ödeme, çoklu kullanıcı, e-posta gönderimi (teklifler yalnızca panelde).
