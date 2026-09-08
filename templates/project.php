<section class="border-b border-line pb-12 pt-32 md:pb-16 md:pt-40">
  <div class="container-x">
    <nav class="text-xs text-ink-dim" aria-label="Sayfa yolu">
      <a href="/" class="transition hover:text-gold">Ana Sayfa</a> <span class="px-1">/</span>
      <a href="/projeler" class="transition hover:text-gold">Projeler</a>
      <?php if ($project['category_name']): ?> <span class="px-1">/</span> <a href="/projeler?kategori=<?= e($project['category_slug']) ?>" class="transition hover:text-gold"><?= e($project['category_name']) ?></a><?php endif; ?>
    </nav>
    <span class="eyebrow mt-6"><?= e($project['category_name'] ?? 'Proje') ?><?= $project['location'] ? ' · ' . e($project['location']) . ', Ankara' : '' ?></span>
    <h1 class="h1 mt-4 max-w-[20ch] text-balance"><?= e($project['title']) ?></h1>
  </div>
</section>

<section class="py-10 md:py-14">
  <div class="container-x grid gap-8 lg:grid-cols-3 lg:gap-12">
    <div class="lg:col-span-2">
      <?php if ($images): ?>
        <div class="grid gap-3 sm:grid-cols-2 sm:gap-4">
          <?php foreach ($images as $i => $img): ?>
            <a href="/uploads/<?= e($img['filename']) ?>"
               data-lightbox="/uploads/<?= e($img['filename']) ?>"
               data-alt="<?= e($img['alt'] ?: $project['title']) ?>"
               class="img-zoom block overflow-hidden rounded-xl reveal reveal-delay-<?= $i % 4 ?> <?= $i === 0 ? 'sm:col-span-2' : '' ?>">
              <img src="/uploads/<?= e($i === 0 ? $img['filename'] : $img['thumb']) ?>"
                   srcset="/uploads/<?= e($img['thumb']) ?> 480w, /uploads/<?= e($img['filename']) ?> 1600w"
                   sizes="<?= $i === 0 ? '(min-width:1024px) 62vw, 100vw' : '(min-width:1024px) 31vw, 50vw' ?>"
                   alt="<?= e($img['alt'] ?: $project['title'] . ' ' . ($i + 1)) ?>"
                   width="<?= $i === 0 ? 1600 : 480 ?>" height="<?= $i === 0 ? 1000 : 360 ?>"
                   loading="<?= $i === 0 ? 'eager' : 'lazy' ?>" decoding="async"
                   class="w-full object-cover <?= $i === 0 ? 'aspect-[16/10]' : 'aspect-[4/3]' ?>">
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <aside class="lg:sticky lg:top-28 lg:self-start">
      <div class="panel p-6 sm:p-8 reveal">
        <h2 class="h3">Proje Hakkında</h2>
        <p class="mt-4 text-sm leading-relaxed text-ink-dim"><?= nl2br(e($project['description'] ?: 'Bu proje müşterimizin mekânına ve kullanım alışkanlıklarına göre sıfırdan tasarlandı, atölyemizde üretildi ve ekibimizce monte edildi.')) ?></p>
        <dl class="mt-6 space-y-3 border-t border-line pt-6 text-sm">
          <?php if ($project['category_name']): ?><div class="flex justify-between gap-4"><dt class="text-ink-dim">Kategori</dt><dd class="text-right"><?= e($project['category_name']) ?></dd></div><?php endif; ?>
          <?php if ($project['location']): ?><div class="flex justify-between gap-4"><dt class="text-ink-dim">Konum</dt><dd class="text-right"><?= e($project['location']) ?>, Ankara</dd></div><?php endif; ?>
          <div class="flex justify-between gap-4"><dt class="text-ink-dim">Fotoğraf</dt><dd><?= count($images) ?></dd></div>
        </dl>
        <a href="/teklif-al?is=<?= rawurlencode($project['category_name'] ?? '') ?>" class="btn-primary mt-7 w-full">Benzerini İsterim</a>
      </div>
    </aside>
  </div>
</section>

<?php if ($related): ?>
<section class="border-t border-line bg-surface py-16 md:py-20">
  <div class="container-x">
    <h2 class="h2 mb-9 reveal">Benzer Projeler</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($related as $i => $p) echo \App\View::partial('_project_card', ['p' => $p, 'delay' => $i]); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?= \App\View::partial('_cta') ?>
