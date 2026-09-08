<?php
use App\Models\Setting;
use App\Models\Category;
use App\Ankara;
use App\Seo;
$s = Setting::all();
// $titleRaw: landing sayfaları kendi <title>'ını tam olarak belirler
// (anahtar kelime başta olsun diye); diğer sayfalarda site adı sona eklenir.
$title = isset($title)
    ? (!empty($titleRaw) ? $title : $title . ' | ' . $s['site_name'])
    : $s['site_name'] . ' | Ankara Mutfak Dolabı, Vestiyer ve Özel Mobilya';
$description = $description ?? $s['tagline'];
$canonical = $canonical ?? url(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$ogImage = $ogImage ?? url('/assets/hero.webp');
$jsonLd = $jsonLd ?? [Seo::localBusiness($s)];
$navCats = Category::all();
$navServices = array_map(fn($k) => Ankara::service($k), array_keys(Ankara::services()));
$navDistricts = array_map(fn($k) => Ankara::district($k), array_keys(Ankara::districts()));
$robots = $robots ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$nav = [['/', 'Ana Sayfa'], ['/projeler', 'Projeler'], ['/hakkimizda', 'Hakkımızda'], ['/iletisim', 'İletişim']];
$tel = preg_replace('/\s/', '', $s['phone']);
$wa = 'https://wa.me/' . preg_replace('/\D/', '', $s['whatsapp']) . '?text=' . rawurlencode('Merhaba, mobilya projem için bilgi almak istiyorum.');
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($description) ?>">
<link rel="canonical" href="<?= e($canonical) ?>">
<meta name="robots" content="<?= e($robots) ?>">
<link rel="alternate" hreflang="tr-tr" href="<?= e($canonical) ?>">
<link rel="alternate" hreflang="x-default" href="<?= e($canonical) ?>">
<?php if (!empty($s['gsc_verification'])): ?><meta name="google-site-verification" content="<?= e($s['gsc_verification']) ?>"><?php endif; ?>
<meta property="og:type" content="website">
<meta property="og:locale" content="tr_TR">
<meta property="og:site_name" content="<?= e($s['site_name']) ?>">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($description) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta property="og:image:alt" content="<?= e($title) ?>">
<meta property="og:image:width" content="1600">
<meta property="og:image:height" content="900">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($title) ?>">
<meta name="twitter:description" content="<?= e($description) ?>">
<meta name="twitter:image" content="<?= e($ogImage) ?>">
<meta name="theme-color" content="#141210">
<meta name="geo.region" content="<?= Ankara::REGION_CODE ?>"><meta name="geo.placename" content="Ankara">
<meta name="author" content="<?= e($s['site_name']) ?>">
<link rel="icon" href="data:image/svg+xml,<?= rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#141210"/><text x="32" y="43" font-family="Georgia,serif" font-size="34" text-anchor="middle" fill="#C9A227">SE</text></svg>') ?>">
<?php if ($path === '/'): ?><link rel="preload" as="image" href="/assets/hero-detail.webp" imagesrcset="/assets/hero-detail-sm.webp 720w, /assets/hero-detail.webp 1200w" imagesizes="(min-width:768px) 45vw, 100vw" fetchpriority="high"><?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/app.css?v=<?= filemtime(BASE_PATH . '/public/assets/app.css') ?>">
<?php foreach ($jsonLd as $ld) echo Seo::jsonLd($ld), "\n"; ?>
</head>
<body class="flex min-h-screen flex-col bg-bg text-ink">

<a href="#icerik" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-full focus:bg-gold focus:px-5 focus:py-3 focus:text-bg">İçeriğe atla</a>

<header data-header class="fixed inset-x-0 top-0 z-50 transition-all duration-500 <?= $path === '/' ? '' : 'header-scrolled' ?>">
  <div class="container-x nav-shrink flex items-center justify-between py-4 transition-all duration-500 md:py-5">
    <a href="/" class="group flex items-center gap-3" aria-label="<?= e($s['site_name']) ?> ana sayfa">
      <span class="grid h-10 w-10 place-items-center rounded-full border border-gold/50 font-serif text-base text-gold transition group-hover:bg-gold group-hover:text-bg">SE</span>
      <span>
        <span class="block font-serif text-lg leading-none tracking-wide sm:text-xl">Sezai Eren</span>
        <span class="mt-0.5 block text-[9px] uppercase tracking-widest2 text-gold sm:text-[10px]">Mobilya · Ankara</span>
      </span>
    </a>

    <nav class="hidden items-center gap-8 md:flex" aria-label="Ana menü">
      <?php foreach ($nav as [$href, $label]): ?>
        <a href="<?= $href ?>" class="nav-link <?= $path === $href || ($href !== '/' && str_starts_with($path, $href)) ? 'nav-link-active' : '' ?>"><?= $label ?></a>
      <?php endforeach; ?>
      <a href="/teklif-al" class="btn-primary !min-h-[42px] !px-6 text-[13px]">Teklif Al</a>
    </nav>

    <button data-menu-toggle aria-expanded="false" aria-controls="mobil-menu" aria-label="Menüyü aç"
            class="grid h-11 w-11 place-items-center rounded-full border border-ink/25 md:hidden">
      <span class="block h-px w-5 bg-current"></span><span class="mt-1.5 block h-px w-5 bg-current"></span>
    </button>
  </div>

  <div id="mobil-menu" data-menu class="fixed inset-0 -z-10 flex flex-col items-center justify-center gap-6 bg-bg opacity-0 pointer-events-none transition-opacity duration-400 [&.open]:opacity-100 [&.open]:pointer-events-auto md:hidden">
    <?php foreach ($nav as [$href, $label]): ?>
      <a href="<?= $href ?>" class="font-serif text-3xl font-light"><?= $label ?></a>
    <?php endforeach; ?>
    <a href="/teklif-al" class="btn-primary mt-3">Ücretsiz Teklif Al</a>
    <a href="tel:<?= e($tel) ?>" class="text-sm text-ink-dim"><?= e($s['phone']) ?></a>
  </div>
</header>

<main id="icerik" class="flex-1 pb-actionbar md:pb-0">
<?= $content ?>
</main>

<footer class="relative overflow-hidden border-t border-line bg-surface grain">
  <div class="container-x grid gap-10 py-14 md:grid-cols-4 md:py-16">
    <div class="md:col-span-2">
      <span class="font-serif text-2xl sm:text-3xl">Sezai Eren <span class="text-gold">Mobilya</span></span>
      <p class="lead mt-4 max-w-md text-sm"><?= e($s['tagline']) ?>. Ücretsiz keşif, ölçüye özel üretim ve zamanında teslim.</p>
      <div class="mt-6 flex gap-3">
        <a href="<?= e($s['instagram']) ?>" target="_blank" rel="noopener" class="grid h-11 w-11 place-items-center rounded-full border border-line transition hover:border-gold hover:text-gold" aria-label="Instagram">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
        </a>
        <a href="<?= $wa ?>" target="_blank" rel="noopener" class="grid h-11 w-11 place-items-center rounded-full border border-line transition hover:border-gold hover:text-gold" aria-label="WhatsApp">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.3.1-.2 0-.3 0-.5l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.1.6a2.6 2.6 0 0 0 1.7-1.2c.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3z"/></svg>
        </a>
      </div>
    </div>
    <div>
      <h2 class="eyebrow mb-5">Hizmetler</h2>
      <ul class="space-y-2.5 text-sm">
        <?php foreach ($navCats as $c): ?><li><a href="/projeler?kategori=<?= e($c['slug']) ?>" class="text-ink-dim transition hover:text-gold"><?= e($c['name']) ?></a></li><?php endforeach; ?>
      </ul>
    </div>
    <div>
      <h2 class="eyebrow mb-5">İletişim</h2>
      <ul class="space-y-2.5 text-sm">
        <li><a href="tel:<?= e($tel) ?>" class="transition hover:text-gold"><?= e($s['phone']) ?></a></li>
        <li><a href="mailto:<?= e($s['email']) ?>" class="text-ink-dim transition hover:text-gold"><?= e($s['email']) ?></a></li>
        <li class="text-ink-dim"><?= e($s['address']) ?></li>
        <li class="text-ink-dim"><?= e($s['working_hours']) ?></li>
      </ul>
    </div>
  </div>

  <div class="border-t border-line">
    <div class="container-x grid gap-8 py-10 md:grid-cols-2">
      <div>
        <h2 class="eyebrow mb-4">Ankara'da Hizmetlerimiz</h2>
        <ul class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-ink-dim">
          <?php foreach ($navServices as $sv): ?><li><a href="<?= e($sv['path']) ?>" class="transition hover:text-gold">Ankara <?= e($sv['name']) ?></a></li><?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h2 class="eyebrow mb-4">Hizmet Verdiğimiz İlçeler</h2>
        <ul class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-ink-dim">
          <?php foreach ($navDistricts as $dd): ?><li><a href="<?= e($dd['path']) ?>" class="transition hover:text-gold"><?= e($dd['name']) ?> Mobilya</a></li><?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>

  <div class="border-t border-line">
    <div class="container-x flex flex-col items-center justify-between gap-3 py-6 text-xs text-ink-dim sm:flex-row">
      <span>© <?= date('Y') ?> <?= e($s['site_name']) ?>. Tüm hakları saklıdır.</span>
      <span>Ankara · Mutfak Dolabı · Vestiyer · Gardırop · Özel Tasarım Mobilya</span>
    </div>
  </div>
</footer>

<nav class="actionbar" aria-label="Hızlı iletişim">
  <a href="tel:<?= e($tel) ?>">
    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.4 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
    Ara
  </a>
  <a href="<?= $wa ?>" target="_blank" rel="noopener">
    <svg width="19" height="19" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.3.1-.2 0-.3 0-.5l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.1.6a2.6 2.6 0 0 0 1.7-1.2c.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3z"/></svg>
    WhatsApp
  </a>
  <a href="/teklif-al" class="!text-gold font-semibold">
    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 15h6M9 11h3"/></svg>
    Teklif Al
  </a>
</nav>

<script src="/assets/app.js?v=<?= filemtime(BASE_PATH . '/public/assets/app.js') ?>" defer></script>
</body>
</html>
