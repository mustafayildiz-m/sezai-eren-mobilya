<?php /** @var array $p */ $delay = $delay ?? 0; ?>
<a href="/projeler/<?= e($p['slug']) ?>" class="card img-zoom group block reveal reveal-delay-<?= $delay % 4 ?>">
  <div class="relative aspect-[4/3] overflow-hidden rounded-2xl">
    <?php if ($p['cover']): ?>
      <img src="/uploads/<?= e($p['cover_thumb'] ?: $p['cover']) ?>" srcset="/uploads/<?= e($p['cover_thumb'] ?: $p['cover']) ?> 480w, /uploads/<?= e($p['cover']) ?> 1600w" sizes="(min-width:1024px) 33vw, (min-width:640px) 50vw, 100vw" alt="<?= e($p['title']) ?> – <?= e($p['category_name'] ?? '') ?><?= $p['location'] ? ', ' . e($p['location']) : '' ?>" width="480" height="360" loading="lazy" class="h-full w-full object-cover">
    <?php else: ?>
      <div class="grid h-full w-full place-items-center bg-beige text-walnut/30 font-serif text-2xl">Görsel yok</div>
    <?php endif; ?>
    <div class="absolute inset-0 bg-gradient-to-t from-ground/95 via-ground/40 to-ground/5 transition group-hover:from-ground"></div>
    <div class="absolute inset-x-0 bottom-0 p-5 text-cream">
      <span class="text-[11px] uppercase tracking-widest2 text-copper-light"><?= e($p['category_name'] ?? 'Proje') ?><?= $p['location'] ? ' · ' . e($p['location']) : '' ?></span>
      <h3 class="mt-1 font-serif text-2xl font-light leading-tight"><?= e($p['title']) ?></h3>
    </div>
  </div>
</a>
