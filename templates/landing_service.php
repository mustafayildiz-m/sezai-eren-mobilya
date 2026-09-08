<section class="border-b border-line pb-14 pt-32 md:pb-20 md:pt-40">
  <div class="container-x">
    <nav class="text-xs text-ink-dim" aria-label="Sayfa yolu"><a href="/" class="transition hover:text-gold">Ana Sayfa</a> <span class="px-1">/</span> <span class="text-ink">Ankara <?= e($sv['name']) ?></span></nav>
    <span class="eyebrow mt-6">Ankara</span>
    <h1 class="h1 mt-4 max-w-[20ch] text-balance"><?= e($sv['h1']) ?></h1>
    <p class="lead mt-6 max-w-2xl text-base sm:text-lg"><?= e($sv['lead']) ?></p>
    <div class="mt-8 flex flex-wrap gap-3">
      <a href="/teklif-al" class="btn-primary">Ücretsiz Keşif ve Teklif</a>
      <?php if ($cat): ?><a href="/projeler?kategori=<?= e($cat['slug']) ?>" class="btn-ghost"><?= e($sv['name']) ?> Projeleri</a><?php endif; ?>
    </div>
  </div>
</section>

<section class="py-14 md:py-20">
  <div class="container-x grid gap-10 lg:grid-cols-3 lg:gap-14">
    <div class="space-y-9 lg:col-span-2">
      <?php foreach ($sv['sections'] as [$h, $b]): ?>
        <div class="reveal">
          <h2 class="h3"><?= e($h) ?></h2>
          <p class="mt-3 leading-relaxed text-ink-dim"><?= e($b) ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <aside class="space-y-4">
      <div class="panel p-6">
        <h2 class="h3">Hizmet verdiğimiz ilçeler</h2>
        <p class="mt-2 text-sm text-ink-dim">Ankara genelinde çalışıyoruz; en yoğun olduğumuz ilçeler:</p>
        <ul class="mt-4 flex flex-wrap gap-2">
          <?php foreach ($districts as $d): ?><li><a href="<?= e($d['path']) ?>" class="pill"><?= e($d['name']) ?></a></li><?php endforeach; ?>
        </ul>
      </div>
      <div class="panel p-6">
        <h2 class="h3">Diğer hizmetlerimiz</h2>
        <ul class="mt-4 space-y-2.5 text-sm">
          <?php foreach ($others as $o): ?><li><a href="<?= e($o['path']) ?>" class="text-ink-dim transition hover:text-gold">Ankara <?= e($o['name']) ?></a></li><?php endforeach; ?>
        </ul>
      </div>
    </aside>
  </div>
</section>

<?php if ($projects): ?>
<section class="border-y border-line bg-surface py-14 md:py-20">
  <div class="container-x">
    <span class="eyebrow">Portföy</span>
    <h2 class="h2 mt-4 text-balance">Ankara'da tamamladığımız <?= e(mb_strtolower($sv['name'])) ?> işleri</h2>
    <div class="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($projects as $i => $p) echo \App\View::partial('_project_card', ['p' => $p, 'delay' => $i]); ?>
    </div>
    <?php if ($cat): ?><a href="/projeler?kategori=<?= e($cat['slug']) ?>" class="btn-ghost mt-9">Tümünü gör</a><?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php if ($sv['faq']) echo \App\View::partial('_faq', ['faq' => $sv['faq'], 'faqTitle' => 'Ankara ' . $sv['name'] . ' hakkında sık sorulanlar']); ?>
<?= \App\View::partial('_cta') ?>
