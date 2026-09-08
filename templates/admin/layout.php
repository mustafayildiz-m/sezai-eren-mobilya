<?php use App\Models\Quote; $unread = Quote::unreadCount(); $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$menu = [['/yonetim', 'Özet', '▦'], ['/yonetim/projeler', 'Projeler', '▣'], ['/yonetim/kategoriler', 'Kategoriler', '☰'], ['/yonetim/teklifler', 'Teklifler', '✉'], ['/yonetim/ayarlar', 'Ayarlar', '⚙']]; ?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex, nofollow">
<title><?= e($title ?? 'Yönetim') ?> · Yönetim Paneli</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/app.css?v=<?= filemtime(BASE_PATH . '/public/assets/app.css') ?>">
<meta name="csrf-token" content="<?= e(\App\Csrf::token()) ?>">
</head>
<body class="admin min-h-screen bg-[#F3EEE6] text-walnut [animation:none]">
<div class="flex min-h-screen">
  <aside class="hidden w-64 shrink-0 flex-col bg-ground text-cream/80 md:flex">
    <a href="/yonetim" class="flex items-center gap-3 px-6 py-6"><span class="grid h-10 w-10 place-items-center rounded-full border border-copper/60 font-serif text-copper">SE</span><span><span class="block font-serif text-lg leading-none text-cream">Sezai Eren</span><span class="block text-[10px] uppercase tracking-widest text-copper-light">Yönetim</span></span></a>
    <nav class="flex-1 space-y-1 px-3">
      <?php foreach ($menu as [$href, $label, $ic]): $on = $href === '/yonetim' ? $path === $href : str_starts_with($path, $href); ?>
        <a href="<?= $href ?>" class="flex items-center justify-between rounded-xl px-4 py-2.5 text-sm transition <?= $on ? 'bg-copper text-cream' : 'hover:bg-white/5' ?>"><span class="flex items-center gap-3"><span class="opacity-70"><?= $ic ?></span><?= $label ?></span><?php if ($href === '/yonetim/teklifler' && $unread): ?><span class="rounded-full bg-cream px-2 py-0.5 text-[11px] font-semibold text-walnut"><?= $unread ?></span><?php endif; ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="space-y-2 p-4 text-xs">
      <a href="/" target="_blank" class="block rounded-xl border border-cream/10 px-4 py-2.5 text-center hover:bg-white/5">Siteyi Gör ↗</a>
      <form method="post" action="/yonetim/cikis"><?= csrf_field() ?><button class="w-full rounded-xl px-4 py-2.5 text-cream/50 hover:text-cream">Çıkış Yap</button></form>
    </div>
  </aside>
  <div class="flex-1 min-w-0">
    <header class="flex items-center justify-between border-b border-walnut/10 bg-white/60 px-6 py-4 backdrop-blur md:hidden">
      <a href="/yonetim" class="font-serif text-lg">SE Yönetim</a>
      <nav class="flex gap-3 text-sm"><?php foreach ($menu as [$href, $label]): ?><a href="<?= $href ?>" class="<?= str_starts_with($path, $href) && ($href !== '/yonetim' || $path === $href) ? 'text-copper font-medium' : '' ?>"><?= $label ?></a><?php endforeach; ?></nav>
    </header>
    <main class="p-6 md:p-10">
      <?php if ($m = flash('ok')): ?><div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?= e($m) ?></div><?php endif; ?>
      <?php if ($m = flash('err')): ?><div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= e($m) ?></div><?php endif; ?>
      <?= $content ?>
    </main>
  </div>
</div>
<script src="/assets/admin.js?v=<?= filemtime(BASE_PATH . '/public/assets/admin.js') ?>" defer></script>
</body>
</html>
