<!doctype html>
<html lang="tr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
<title>Giriş · Yönetim</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400&family=Inter:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/app.css"></head>
<body class="grid min-h-screen place-items-center bg-ground p-6 text-cream grain relative">
<form method="post" action="/yonetim/giris" class="relative w-full max-w-sm rounded-3xl border border-cream/10 bg-walnut/60 p-8 backdrop-blur">
  <?= csrf_field() ?>
  <div class="mb-8 text-center"><span class="mx-auto grid h-14 w-14 place-items-center rounded-full border border-copper/60 font-serif text-xl text-copper">SE</span><h1 class="mt-4 font-serif text-3xl font-light">Yönetim Paneli</h1></div>
  <?php if (!empty($error)): ?><div class="mb-4 rounded-xl border border-red-400/40 bg-red-500/10 px-4 py-3 text-sm text-red-200"><?= e($error) ?></div><?php endif; ?>
  <label class="mb-1.5 block text-xs uppercase tracking-widest text-cream/60">Kullanıcı adı</label>
  <input name="username" required autofocus autocomplete="username" class="mb-4 w-full rounded-xl border border-cream/15 bg-ground/60 px-4 py-3 text-cream focus:border-copper focus:outline-none">
  <label class="mb-1.5 block text-xs uppercase tracking-widest text-cream/60">Şifre</label>
  <input name="password" type="password" required autocomplete="current-password" class="mb-6 w-full rounded-xl border border-cream/15 bg-ground/60 px-4 py-3 text-cream focus:border-copper focus:outline-none">
  <button class="btn-primary w-full">Giriş Yap</button>
  <a href="/" class="mt-4 block text-center text-xs text-cream/40 hover:text-cream">← Siteye dön</a>
</form>
</body></html>
