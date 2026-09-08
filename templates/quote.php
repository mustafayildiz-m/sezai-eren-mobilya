<?php $errors = $errors ?? []; $old = $old ?? []; ?>
<section class="border-b border-line pb-14 pt-32 md:pb-20 md:pt-40">
  <div class="container-x">
    <nav class="text-xs text-ink-dim" aria-label="Sayfa yolu"><a href="/" class="transition hover:text-gold">Ana Sayfa</a> <span class="px-1">/</span> <span class="text-ink">Teklif Al</span></nav>
    <span class="eyebrow mt-6">Ücretsiz Teklif</span>
    <h1 class="h1 mt-4 max-w-[18ch] text-balance">Birkaç bilgi verin, sizi arayalım</h1>
    <p class="lead mt-4 max-w-xl">Genellikle aynı gün içinde dönüş yapıyoruz. Yerinde ölçü ve keşif ücretsizdir.</p>
  </div>
</section>

<section class="py-14 md:py-20">
  <div class="container-x grid gap-8 lg:grid-cols-5 lg:gap-12">
    <form method="post" action="/teklif-al" enctype="multipart/form-data" class="panel space-y-6 p-6 sm:p-8 lg:col-span-3 reveal" novalidate>
      <?= csrf_field() ?>
      <div class="hidden" aria-hidden="true"><label>Web sitesi<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

      <?php if ($errors): ?>
        <div role="alert" class="rounded-lg border border-red-500/40 bg-red-500/10 p-4 text-sm text-red-200">
          <ul class="list-disc pl-5"><?php foreach ($errors as $er) echo '<li>', e($er), '</li>'; ?></ul>
        </div>
      <?php endif; ?>

      <div class="grid gap-5 sm:grid-cols-2">
        <div><label class="label" for="name">Ad Soyad *</label><input class="input" id="name" name="name" required autocomplete="name" value="<?= e($old['name'] ?? '') ?>" placeholder="Adınız Soyadınız"></div>
        <div><label class="label" for="phone">Telefon *</label><input class="input" id="phone" name="phone" type="tel" required autocomplete="tel" inputmode="tel" value="<?= e($old['phone'] ?? '') ?>" placeholder="05XX XXX XX XX"></div>
      </div>

      <div class="grid gap-5 sm:grid-cols-2">
        <div><label class="label" for="email">E-posta</label><input class="input" id="email" name="email" type="email" autocomplete="email" inputmode="email" value="<?= e($old['email'] ?? '') ?>" placeholder="ornek@mail.com"></div>
        <div><label class="label" for="job_type">İş Türü</label>
          <select class="input" id="job_type" name="job_type">
            <?php $sel = $old['job_type'] ?? ($_GET['is'] ?? ''); foreach (array_merge(array_column($categories, 'name'), ['Diğer']) as $n): ?>
              <option <?= $sel === $n ? 'selected' : '' ?>><?= e($n) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div>
        <label class="label" for="message">Ölçüler, istekleriniz, notlarınız</label>
        <textarea class="input" id="message" name="message" rows="5" placeholder="Örn: 3.20 m L mutfak, tavana kadar, beyaz mat kapak, lavabo pencere önünde..."><?= e($old['message'] ?? '') ?></textarea>
      </div>

      <div>
        <label class="label" for="photo">Mekânın fotoğrafı (isteğe bağlı)</label>
        <input class="input file:mr-4 file:rounded-full file:border-0 file:bg-gold file:px-4 file:py-1.5 file:font-medium file:text-bg" id="photo" name="photo" type="file" accept="image/*">
        <p class="mt-1.5 text-xs text-ink-dim">JPG, PNG veya WebP, en fazla 10 MB.</p>
      </div>

      <button class="btn-primary w-full sm:w-auto" type="submit">Teklif İste</button>
      <p class="text-xs text-ink-dim">Bilgileriniz yalnızca sizinle iletişim için kullanılır, üçüncü kişilerle paylaşılmaz.</p>
    </form>

    <aside class="space-y-4 lg:col-span-2 reveal reveal-delay-1">
      <div class="relative overflow-hidden rounded-xl bg-surface2 p-6 ring-1 ring-line sm:p-8 grain">
        <h2 class="h3">Sonra ne olacak?</h2>
        <ol class="mt-6 space-y-4 text-sm text-ink-dim">
          <li class="flex gap-3"><span class="font-serif text-xl leading-none text-gold">1</span><span>Formunuz bize düşer, aynı gün sizi arayıp uygun bir keşif günü belirleriz.</span></li>
          <li class="flex gap-3"><span class="font-serif text-xl leading-none text-gold">2</span><span>Evinize gelir, ölçü alır, malzeme örneklerini gösteririz.</span></li>
          <li class="flex gap-3"><span class="font-serif text-xl leading-none text-gold">3</span><span>3 boyutlu çizim ve net fiyatla teklif sunarız. Karar tamamen sizin.</span></li>
        </ol>
      </div>
      <a href="<?= e($wa) ?>" target="_blank" rel="noopener" class="card flex min-h-[76px] items-center gap-4 p-5">
        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-[#25D366]/15 text-2xl text-[#25D366]" aria-hidden="true">✆</span>
        <span>
          <span class="block text-[11px] uppercase tracking-widest text-ink-dim">Acele mi?</span>
          <span class="font-medium">WhatsApp'tan hemen yazın</span>
        </span>
      </a>
    </aside>
  </div>
</section>
