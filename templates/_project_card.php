<?php /** @var array $p */ $delay = $delay ?? 0; ?>
<a href="/projeler/<?= e($p['slug']) ?>" class="card img-zoom group block overflow-hidden reveal reveal-delay-<?= $delay % 4 ?>">
  <div class="relative aspect-[4/3] overflow-hidden">
    <?php if ($p['cover']): ?>
      <img src="/uploads/<?= e($p['cover_thumb'] ?: $p['cover']) ?>"
           srcset="/uploads/<?= e($p['cover_thumb'] ?: $p['cover']) ?> 480w, /uploads/<?= e($p['cover']) ?> 1600w"
           sizes="(min-width:1024px) 33vw, (min-width:640px) 50vw, 100vw"
           alt="<?= e($p['title']) ?> – <?= e($p['category_name'] ?? '') ?><?= $p['location'] ? ', ' . e($p['location']) : '' ?>"
           width="480" height="360" loading="lazy" decoding="async" class="h-full w-full object-cover">
    <?php else: ?>
      <div class="grid h-full w-full place-items-center bg-surface2 font-serif text-2xl text-ink-dim">Görsel yok</div>
    <?php endif; ?>
    <div class="scrim"></div>
    <div class="absolute inset-x-0 bottom-0 p-4 sm:p-5">
      <span class="text-[10px] uppercase tracking-widest2 text-gold"><?= e($p['category_name'] ?? 'Proje') ?><?= $p['location'] ? ' · ' . e($p['location']) : '' ?></span>
      <h3 class="mt-1.5 font-serif text-lg font-light leading-tight sm:text-xl"><?= e($p['title']) ?></h3>
    </div>
  </div>
</a>
