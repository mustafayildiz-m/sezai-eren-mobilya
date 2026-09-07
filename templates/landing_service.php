<section class="bg-ground pb-16 pt-36 text-cream md:pt-44">
  <div class="container-x">
    <nav class="text-xs text-cream/50" aria-label="breadcrumb"><a href="/" class="hover:text-copper">Ana Sayfa</a> / <span class="text-cream/80">Ankara <?= e($sv['name']) ?></span></nav>
    <span class="eyebrow mt-6">Ankara</span>
    <h1 class="h-display mt-4 max-w-4xl text-5xl md:text-6xl text-balance"><?= e($sv['h1']) ?></h1>
    <p class="mt-6 max-w-2xl text-lg leading-relaxed text-cream/70"><?= e($sv['lead']) ?></p>
    <div class="mt-8 flex flex-wrap gap-3">
      <a href="/teklif-al" class="btn-primary">Ücretsiz Keşif ve Teklif</a>
      <?php if ($cat): ?><a href="/projeler?kategori=<?= e($cat['slug']) ?>" class="btn-outline-light"><?= e($sv['name']) ?> Projeleri</a><?php endif; ?>
    </div>
  </div>
</section>

<section class="py-20">
  <div class="container-x grid gap-12 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-10">
      <?php foreach ($sv['sections'] as [$h, $b]): ?>
        <div class="reveal">
          <h2 class="font-serif text-2xl md:text-3xl"><?= e($h) ?></h2>
          <p class="mt-3 leading-relaxed text-walnut/75"><?= e($b) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
    <aside class="space-y-6">
      <div class="card p-6">
        <h2 class="font-serif text-xl">Hizmet verdiğimiz ilçeler</h2>
        <p class="mt-2 text-sm text-walnut/60">Ankara genelinde çalışıyoruz; en yoğun olduğumuz ilçeler:</p>
        <ul class="mt-4 flex flex-wrap gap-2">
          <?php foreach ($districts as $d): ?>
            <li><a href="<?= e($d['path']) ?>" class="filter-pill"><?= e($d['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="card p-6">
        <h2 class="font-serif text-xl">Diğer hizmetlerimiz</h2>
        <ul class="mt-4 space-y-2 text-sm">
          <?php foreach ($others as $o): ?>
            <li><a href="<?= e($o['path']) ?>" class="text-walnut/80 hover:text-copper transition">Ankara <?= e($o['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </aside>
  </div>
</section>

<?php if ($projects): ?>
<section class="bg-beige/40 py-20">
  <div class="container-x">
    <span class="eyebrow">Portföy</span>
    <h2 class="h-display mt-4 text-4xl md:text-5xl">Ankara'da tamamladığımız <?= e(mb_strtolower($sv['name'])) ?> işleri</h2>
    <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($projects as $i => $p) echo \App\View::partial('_project_card', ['p' => $p, 'delay' => $i]); ?>
    </div>
    <?php if ($cat): ?><a href="/projeler?kategori=<?= e($cat['slug']) ?>" class="btn-outline mt-10 inline-block">Tümünü gör</a><?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ($sv['faq']) echo \App\View::partial('_faq', ['faq' => $sv['faq'], 'faqTitle' => 'Ankara ' . $sv['name'] . ' hakkında sık sorulanlar']); ?>
<?= \App\View::partial('_cta') ?>
