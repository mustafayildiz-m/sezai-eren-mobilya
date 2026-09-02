<?php
use App\Models\Setting;
use App\Models\Category;
use App\Seo;
$s = Setting::all();
$title = isset($title) ? $title . ' | ' . $s['site_name'] : $s['site_name'] . ' | Ankara Mutfak Dolabı, Vestiyer ve Özel Mobilya';
$description = $description ?? $s['tagline'];
$canonical = $canonical ?? url(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$ogImage = $ogImage ?? url('/assets/seed/hero.webp');
$jsonLd = $jsonLd ?? [Seo::localBusiness($s)];
$navCats = Category::all();
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$nav = [['/', 'Ana Sayfa'], ['/projeler', 'Projeler'], ['/hakkimizda', 'Hakkımızda'], ['/iletisim', 'İletişim']];
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
<meta name="robots" content="index, follow, max-image-preview:large">
<meta property="og:type" content="website">
<meta property="og:locale" content="tr_TR">
<meta property="og:site_name" content="<?= e($s['site_name']) ?>">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($description) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($title) ?>">
<meta name="twitter:description" content="<?= e($description) ?>">
<meta name="twitter:image" content="<?= e($ogImage) ?>">
<meta name="theme-color" content="#1A130E">
<meta name="geo.region" content="TR-06"><meta name="geo.placename" content="Ankara">
<link rel="icon" href="data:image/svg+xml,<?= rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#1A130E"/><text x="32" y="43" font-family="Georgia,serif" font-size="34" text-anchor="middle" fill="#B87333">SE</text></svg>') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/app.css?v=<?= filemtime(BASE_PATH . '/public/assets/app.css') ?>">
<?php foreach ($jsonLd as $ld) echo Seo::jsonLd($ld), "\n"; ?>
</head>
<body class="min-h-screen flex flex-col">

<header data-header class="fixed inset-x-0 top-0 z-50 transition-all duration-500 <?= $path === '/' ? 'text-cream' : 'text-cream header-scrolled' ?>">
  <div class="container-x nav-shrink flex items-center justify-between py-5 transition-all duration-500">
    <a href="/" class="group flex items-center gap-3" aria-label="<?= e($s['site_name']) ?>">
      <span class="grid h-11 w-11 place-items-center rounded-full border border-copper/60 font-serif text-lg text-copper transition group-hover:bg-copper group-hover:text-cream">SE</span>
      <span class="hidden sm:block">
        <span class="block font-serif text-xl leading-none tracking-wide">Sezai Eren</span>
        <span class="block text-[10px] uppercase tracking-widest2 text-copper-light">Mobilya · Ankara</span>
      </span>
    </a>
    <nav class="hidden items-center gap-9 md:flex">
      <?php foreach ($nav as [$href, $label]): ?>
        <a href="<?= $href ?>" class="nav-link <?= $path === $href || ($href !== '/' && str_starts_with($path, $href)) ? 'text-copper-light after:w-full' : 'text-cream/85 hover:text-cream' ?>"><?= $label ?></a>
      <?php endforeach; ?>
      <a href="/teklif-al" class="btn-primary !py-2.5 !px-6">Teklif Al</a>
    </nav>
    <button data-menu-toggle aria-expanded="false" aria-label="Menü" class="md:hidden grid h-11 w-11 place-items-center rounded-full border border-cream/30">
      <span class="block h-px w-5 bg-current"></span><span class="mt-1.5 block h-px w-5 bg-current"></span>
    </button>
  </div>
  <div data-menu class="fixed inset-0 -z-10 flex flex-col items-center justify-center gap-7 bg-ground/98 text-cream opacity-0 pointer-events-none transition-opacity duration-500 [&.open]:opacity-100 [&.open]:pointer-events-auto md:hidden">
    <?php foreach ($nav as [$href, $label]): ?>
      <a href="<?= $href ?>" class="font-serif text-4xl font-light"><?= $label ?></a>
    <?php endforeach; ?>
    <a href="/teklif-al" class="btn-primary mt-4">Ücretsiz Teklif Al</a>
  </div>
</header>

<main class="flex-1">
<?= $content ?>
</main>

<footer class="relative bg-ground text-cream/80 grain overflow-hidden">
  <div class="container-x grid gap-12 py-20 md:grid-cols-4">
    <div class="md:col-span-2">
      <span class="font-serif text-3xl text-cream">Sezai Eren <span class="text-copper">Mobilya</span></span>
      <p class="mt-4 max-w-md text-sm leading-relaxed text-cream/60"><?= e($s['tagline']) ?>. Ücretsiz keşif, ölçüye özel üretim ve zamanında teslim.</p>
      <div class="mt-6 flex gap-3">
        <a href="<?= e($s['instagram']) ?>" target="_blank" rel="noopener" class="grid h-10 w-10 place-items-center rounded-full border border-cream/15 hover:border-copper hover:text-copper transition" aria-label="Instagram">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
        </a>
        <a href="<?= $wa ?>" target="_blank" rel="noopener" class="grid h-10 w-10 place-items-center rounded-full border border-cream/15 hover:border-copper hover:text-copper transition" aria-label="WhatsApp">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.3.1-.2 0-.3 0-.5l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.1.6a2.6 2.6 0 0 0 1.7-1.2c.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3z"/></svg>
        </a>
      </div>
    </div>
    <div>
      <h4 class="eyebrow mb-5">Hizmetler</h4>
      <ul class="space-y-2.5 text-sm">
        <?php foreach ($navCats as $c): ?><li><a href="/projeler?kategori=<?= e($c['slug']) ?>" class="hover:text-copper transition"><?= e($c['name']) ?></a></li><?php endforeach; ?>
      </ul>
    </div>
    <div>
      <h4 class="eyebrow mb-5">İletişim</h4>
      <ul class="space-y-2.5 text-sm">
        <li><a href="tel:<?= preg_replace('/\s/', '', $s['phone']) ?>" class="hover:text-copper transition"><?= e($s['phone']) ?></a></li>
        <li><a href="mailto:<?= e($s['email']) ?>" class="hover:text-copper transition"><?= e($s['email']) ?></a></li>
        <li class="text-cream/60"><?= e($s['address']) ?></li>
        <li class="text-cream/60"><?= e($s['working_hours']) ?></li>
      </ul>
    </div>
  </div>
  <div class="border-t border-cream/10">
    <div class="container-x flex flex-col items-center justify-between gap-3 py-6 text-xs text-cream/40 sm:flex-row">
      <span>© <?= date('Y') ?> <?= e($s['site_name']) ?>. Tüm hakları saklıdır.</span>
      <span>Ankara · Mutfak Dolabı · Vestiyer · Gardırop · Özel Tasarım Mobilya</span>
    </div>
  </div>
</footer>

<a href="<?= $wa ?>" target="_blank" rel="noopener" aria-label="WhatsApp ile yazın" class="fixed bottom-6 right-6 z-40 grid h-14 w-14 place-items-center rounded-full bg-[#25D366] text-white shadow-[0_15px_40px_-10px_rgba(37,211,102,.7)] transition hover:scale-110">
  <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.2-.4.7-1.3.1-.2 0-.3 0-.5l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.1.6a2.6 2.6 0 0 0 1.7-1.2c.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3z"/></svg>
</a>

<script src="/assets/app.js?v=<?= filemtime(BASE_PATH . '/public/assets/app.js') ?>" defer></script>
</body>
</html>
