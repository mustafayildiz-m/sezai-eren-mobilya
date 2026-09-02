# Sezai Eren Mobilya – Web Sitesi

Ankara'da mutfak dolabı, vestiyer, gardırop ve özel tasarım mobilya üreten Sezai Eren için
SEO odaklı vitrin sitesi + resim yükleme ve teklif takibi yapılabilen yönetim paneli.

**Teknoloji:** PHP 8.3, SQLite, GD (WebP), Tailwind CSS (önceden derlenmiş), vanilla JS. Çalışma zamanında Composer/Node gerekmez.

## Yerelde çalıştırma

```bash
cp .env.example .env            # ADMIN_PASSWORD'ü değiştirin
docker compose up -d --build
./scripts/download_images.sh    # örnek görseller (Unsplash, ticari kullanıma serbest)
docker compose exec -u www-data app php scripts/seed.php
```

- Site: http://localhost:8080
- Yönetim: http://localhost:8080/yonetim (kullanıcı/şifre `.env` içinde)

## Testler

```bash
docker compose run --rm test
```

## Canlıya alma

**Seçenek A – Paylaşımlı hosting (FTP):** Tüm klasörü yükleyin, alan adının kök dizinini `public/` klasörüne yönlendirin
(çoğu panelde "document root" ayarı). `.env` dosyasını oluşturun, `storage/` ve `public/uploads/` yazılabilir olsun (755/775).
PHP 8.1+ ve `gd`, `pdo_sqlite` eklentileri gerekir. `APP_URL`'i gerçek alan adı yapın, `APP_DEBUG=0`.

**Seçenek B – VPS / Docker:** Aynı `docker compose up -d --build`. Önüne Caddy/Nginx koyup HTTPS verin.

## Yedekleme

Tek bir SQLite dosyası ve yüklenen resimler:

```bash
cp storage/database.sqlite yedek/
cp -r public/uploads yedek/
```

## CSS'i yeniden derleme (yalnızca tasarım değiştirirseniz)

```bash
npx tailwindcss -i src/input.css -o public/assets/app.css --minify
```

## Yapı

- `public/` web kökü (index.php, .htaccess, assets, uploads)
- `src/` Router, Database, Image, Auth, Csrf, RateLimit, Seo, View, Models, Controllers
- `templates/` PHP şablonları (`admin/` panel)
- `scripts/` seed ve görsel indirme
- `tests/` PHPUnit
