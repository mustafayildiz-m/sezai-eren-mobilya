<?php $errors = $errors ?? []; $old = $old ?? []; ?>
<section class="bg-ground pb-16 pt-36 text-cream md:pt-44">
  <div class="container-x">
    <span class="eyebrow">Ücretsiz Teklif</span>
    <h1 class="h-display mt-4 text-5xl md:text-6xl">Birkaç bilgi verin, sizi arayalım</h1>
    <p class="mt-4 max-w-xl text-cream/70">Genellikle aynı gün içinde dönüş yapıyoruz. Yerinde ölçü ve keşif ücretsizdir.</p>
  </div>
</section>
<section class="py-20">
  <div class="container-x grid gap-12 lg:grid-cols-5">
    <form method="post" action="/teklif-al" enctype="multipart/form-data" class="card space-y-6 p-8 lg:col-span-3 reveal" novalidate>
      <?= csrf_field() ?>
      <div class="hidden" aria-hidden="true"><label>Web sitesi<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <?php if ($errors): ?>
        <div class="rounded-xl border border-red-300 bg-red-50 p-4 text-sm text-red-800"><ul class="list-disc pl-5"><?php foreach ($errors as $er) echo '<li>', e($er), '</li>'; ?></ul></div>
      <?php endif; ?>
      <div class="grid gap-6 sm:grid-cols-2">
        <div><label class="label" for="name">Ad Soyad *</label><input class="input" id="name" name="name" required value="<?= e($old['name'] ?? '') ?>" placeholder="Adınız Soyadınız"></div>
        <div><label class="label" for="phone">Telefon *</label><input class="input" id="phone" name="phone" type="tel" required value="<?= e($old['phone'] ?? '') ?>" placeholder="05XX XXX XX XX"></div>
      </div>
      <div class="grid gap-6 sm:grid-cols-2">
        <div><label class="label" for="email">E-posta</label><input class="input" id="email" name="email" type="email" value="<?= e($old['email'] ?? '') ?>" placeholder="ornek@mail.com"></div>
        <div><label class="label" for="job_type">İş Türü</label>
          <select class="input" id="job_type" name="job_type">
            <?php $sel = $old['job_type'] ?? ($_GET['is'] ?? ''); foreach (array_merge(array_column($categories, 'name'), ['Diğer']) as $n): ?>
              <option <?= $sel === $n ? 'selected' : '' ?>><?= e($n) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div><label class="label" for="message">Ölçüler, istekleriniz, notlarınız</label><textarea class="input" id="message" name="message" rows="5" placeholder="Örn: 3.20 m L mutfak, tavana kadar, beyaz mat kapak, lavabo pencere önünde..."><?= e($old['message'] ?? '') ?></textarea></div>
      <div><label class="label" for="photo">Mekânın fotoğrafı (isteğe bağlı)</label><input class="input file:mr-4 file:rounded-full file:border-0 file:bg-copper file:px-4 file:py-1.5 file:text-cream" id="photo" name="photo" type="file" accept="image/*"><p class="mt-1 text-xs text-walnut/50">JPG, PNG veya WebP, en fazla 10 MB.</p></div>
      <button class="btn-primary w-full sm:w-auto" type="submit">Teklif İste</button>
      <p class="text-xs text-walnut/50">Bilgileriniz yalnızca sizinle iletişim için kullanılır, üçüncü kişilerle paylaşılmaz.</p>
    </form>
    <aside class="space-y-6 lg:col-span-2 reveal reveal-delay-1">
      <div class="rounded-[2rem] bg-walnut p-8 text-cream grain relative overflow-hidden">
        <h2 class="font-serif text-3xl">Sonra ne olacak?</h2>
        <ol class="mt-6 space-y-4 text-sm text-cream/75">
          <li class="flex gap-3"><span class="font-serif text-2xl text-copper">1</span><span>Formunuz bize düşer, aynı gün sizi arayıp uygun bir keşif günü belirleriz.</span></li>
          <li class="flex gap-3"><span class="font-serif text-2xl text-copper">2</span><span>Evinize gelir, ölçü alır, malzeme örneklerini gösteririz.</span></li>
          <li class="flex gap-3"><span class="font-serif text-2xl text-copper">3</span><span>3D çizim ve net fiyatla teklif sunarız. Karar tamamen sizin.</span></li>
        </ol>
      </div>
      <a href="<?= $wa ?>" target="_blank" rel="noopener" class="card flex items-center gap-4 p-6">
        <span class="grid h-12 w-12 place-items-center rounded-full bg-[#25D366]/15 text-[#25D366] text-2xl">✆</span>
        <span><span class="block text-xs uppercase tracking-widest text-walnut/50">Acele mi?</span><span class="font-medium">WhatsApp'tan hemen yazın</span></span>
      </a>
    </aside>
  </div>
</section>
