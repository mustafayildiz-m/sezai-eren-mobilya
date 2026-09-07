<section class="bg-ground pb-16 pt-36 text-cream md:pt-44">
  <div class="container-x">
    <nav class="text-xs text-cream/50" aria-label="breadcrumb"><a href="/" class="hover:text-copper">Ana Sayfa</a> / <span class="text-cream/80"><?= e($d['name']) ?> Mobilya</span></nav>
    <span class="eyebrow mt-6"><?= e($d['name']) ?> · Ankara</span>
    <h1 class="h-display mt-4 max-w-4xl text-5xl md:text-6xl text-balance"><?= e($d['name']) ?>'da Ölçüye Özel Mobilya</h1>
    <p class="mt-6 max-w-2xl text-lg leading-relaxed text-cream/70"><?= e($d['lead']) ?></p>
    <div class="mt-8 flex flex-wrap gap-3">
      <a href="/teklif-al" class="btn-primary"><?= e($d['name']) ?> İçin Ücretsiz Keşif</a>
      <a href="/iletisim" class="btn-outline-light">Bize Ulaşın</a>
    </div>
  </div>
</section>

<section class="py-20">
  <div class="container-x grid gap-12 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
      <h2 class="font-serif text-2xl md:text-3xl"><?= e($d['name']) ?>'da nasıl çalışıyoruz?</h2>
      <?php foreach ($d['body'] as $para): ?>
        <p class="leading-relaxed text-walnut/75 reveal"><?= e($para) ?></p>
      <?php endforeach; ?>
    </div>
    <aside class="card h-fit p-6">
      <h2 class="font-serif text-xl">Keşfe gittiğimiz mahalleler</h2>
      <p class="mt-2 text-sm text-walnut/60"><?= e($d['name']) ?> genelinde çalışıyoruz. En sık iş yaptığımız mahalleler:</p>
      <ul class="mt-4 flex flex-wrap gap-2 text-xs">
        <?php foreach ($d['neighborhoods'] as $n): ?>
          <li class="rounded-full border border-walnut/15 px-3 py-1 text-walnut/70"><?= e($n) ?></li>
        <?php endforeach; ?>
      </ul>
    </aside>
  </div>
</section>

<section class="bg-beige/40 py-20">
  <div class="container-x">
    <span class="eyebrow">Hizmetler</span>
    <h2 class="h-display mt-4 text-4xl md:text-5xl"><?= e($d['name']) ?>'da hangi işleri yapıyoruz?</h2>
    <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($services as $sv): ?>
        <a href="<?= e($sv['path']) ?>" class="card group block p-6 transition hover:-translate-y-1">
          <h3 class="font-serif text-xl group-hover:text-copper transition"><?= e($sv['name']) ?></h3>
          <p class="mt-2 text-sm leading-relaxed text-walnut/65"><?= e(mb_substr($sv['lead'], 0, 120)) ?>…</p>
          <span class="mt-4 inline-block text-xs uppercase tracking-widest2 text-copper">Detay →</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($local): ?>
<section class="py-20">
  <div class="container-x">
    <span class="eyebrow">Referans</span>
    <h2 class="h-display mt-4 text-4xl md:text-5xl"><?= e($d['name']) ?>'da tamamladığımız işler</h2>
    <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($local as $i => $p) echo \App\View::partial('_project_card', ['p' => $p, 'delay' => $i]); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?= \App\View::partial('_faq', ['faq' => $faq, 'faqTitle' => $d['name'] . ' mobilya hakkında sık sorulanlar']) ?>

<section class="border-t border-walnut/10 py-12">
  <div class="container-x">
    <h2 class="text-sm font-medium uppercase tracking-widest2 text-walnut/50">Ankara'da hizmet verdiğimiz diğer ilçeler</h2>
    <ul class="mt-5 flex flex-wrap gap-2">
      <?php foreach ($others as $o): ?>
        <li><a href="<?= e($o['path']) ?>" class="filter-pill"><?= e($o['name']) ?> Mobilya</a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?= \App\View::partial('_cta') ?>
