<section class="bg-ground pb-16 pt-36 text-cream md:pt-44">
  <div class="container-x">
    <nav class="text-xs text-cream/50" aria-label="breadcrumb"><a href="/" class="hover:text-copper">Ana Sayfa</a> / <a href="/projeler" class="hover:text-copper">Projeler</a><?php if ($project['category_name']): ?> / <a href="/projeler?kategori=<?= e($project['category_slug']) ?>" class="hover:text-copper"><?= e($project['category_name']) ?></a><?php endif; ?></nav>
    <span class="eyebrow mt-6"><?= e($project['category_name'] ?? 'Proje') ?><?= $project['location'] ? ' · ' . e($project['location']) . ', Ankara' : '' ?></span>
    <h1 class="h-display mt-4 text-5xl md:text-6xl text-balance"><?= e($project['title']) ?></h1>
  </div>
</section>
<section class="py-14">
  <div class="container-x grid gap-12 lg:grid-cols-3">
    <div class="lg:col-span-2">
      <?php if ($images): ?>
        <div class="grid gap-4 sm:grid-cols-2">
          <?php foreach ($images as $i => $img): ?>
            <a href="/uploads/<?= e($img['filename']) ?>" data-lightbox="/uploads/<?= e($img['filename']) ?>" data-alt="<?= e($img['alt'] ?: $project['title']) ?>" class="img-zoom block overflow-hidden rounded-2xl reveal reveal-delay-<?= $i % 4 ?> <?= $i === 0 ? 'sm:col-span-2' : '' ?>">
              <img src="/uploads/<?= e($i === 0 ? $img['filename'] : $img['thumb']) ?>" alt="<?= e($img['alt'] ?: $project['title'] . ' ' . ($i + 1)) ?>" width="<?= $i === 0 ? 1600 : 480 ?>" height="<?= $i === 0 ? 1067 : 320 ?>" loading="<?= $i === 0 ? 'eager' : 'lazy' ?>" class="w-full object-cover <?= $i === 0 ? 'aspect-[16/10]' : 'aspect-[4/3]' ?>">
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <aside class="lg:sticky lg:top-28 lg:self-start">
      <div class="card p-8 reveal">
        <h2 class="font-serif text-2xl">Proje Hakkında</h2>
        <div class="prose mt-4 text-sm leading-relaxed text-walnut/75"><?= nl2br(e($project['description'] ?: 'Bu proje müşterimizin mekânına ve kullanım alışkanlıklarına göre sıfırdan tasarlandı, atölyemizde üretildi ve ekibimizce monte edildi.')) ?></div>
        <dl class="mt-6 space-y-3 border-t border-walnut/10 pt-6 text-sm">
          <?php if ($project['category_name']): ?><div class="flex justify-between"><dt class="text-walnut/50">Kategori</dt><dd><?= e($project['category_name']) ?></dd></div><?php endif; ?>
          <?php if ($project['location']): ?><div class="flex justify-between"><dt class="text-walnut/50">Konum</dt><dd><?= e($project['location']) ?>, Ankara</dd></div><?php endif; ?>
          <div class="flex justify-between"><dt class="text-walnut/50">Fotoğraf</dt><dd><?= count($images) ?></dd></div>
        </dl>
        <a href="/teklif-al?is=<?= rawurlencode($project['category_name'] ?? '') ?>" class="btn-primary mt-8 w-full">Benzerini İsterim</a>
      </div>
    </aside>
  </div>
</section>
<?php if ($related): ?>
<section class="bg-beige/50 py-20">
  <div class="container-x">
    <h2 class="h-display mb-10 text-3xl md:text-4xl reveal">Benzer Projeler</h2>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($related as $i => $p) echo \App\View::partial('_project_card', ['p' => $p, 'delay' => $i]); ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?= \App\View::partial('_cta') ?>
