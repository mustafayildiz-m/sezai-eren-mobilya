<div class="flex flex-wrap items-center justify-between gap-4">
  <div><h1 class="font-serif text-4xl">Projeler</h1><p class="mt-1 text-sm text-walnut/60"><?= count($projects) ?> proje</p></div>
  <a href="/yonetim/projeler/yeni" class="btn-primary">＋ Yeni Proje</a>
</div>
<div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
  <?php foreach ($projects as $p): ?>
    <div class="card overflow-hidden">
      <a href="/yonetim/projeler/<?= $p['id'] ?>" class="block aspect-[4/3] bg-beige"><?php if ($p['cover_thumb']): ?><img src="/uploads/<?= e($p['cover_thumb']) ?>" alt="" class="h-full w-full object-cover"><?php else: ?><div class="grid h-full place-items-center text-walnut/30">Fotoğraf yok</div><?php endif; ?></a>
      <div class="p-4">
        <div class="flex items-start justify-between gap-2"><h3 class="font-medium leading-tight"><?= e($p['title']) ?></h3><?php if ($p['is_featured']): ?><span class="shrink-0 rounded-full bg-copper/15 px-2 py-0.5 text-[10px] uppercase tracking-wider text-copper">Öne çıkan</span><?php endif; ?></div>
        <div class="mt-1 text-xs text-walnut/50"><?= e($p['category_name'] ?? 'Kategorisiz') ?><?= $p['location'] ? ' · ' . e($p['location']) : '' ?></div>
        <div class="mt-4 flex gap-2 text-sm">
          <a href="/yonetim/projeler/<?= $p['id'] ?>" class="btn-outline !px-4 !py-2 flex-1">Düzenle</a>
          <form method="post" action="/yonetim/projeler/<?= $p['id'] ?>/sil" data-confirm="“<?= e($p['title']) ?>” projesi ve tüm fotoğrafları silinecek. Emin misiniz?"><?= csrf_field() ?><button class="rounded-full border border-red-200 px-4 py-2 text-red-600 hover:bg-red-50">Sil</button></form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php if (!$projects): ?><div class="card mt-8 p-12 text-center text-walnut/60">Henüz proje yok. İlk projenizi ekleyin.</div><?php endif; ?>
