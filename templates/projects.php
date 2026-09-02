<section class="bg-ground pb-16 pt-36 text-cream md:pt-44">
  <div class="container-x">
    <nav class="text-xs text-cream/50" aria-label="breadcrumb"><a href="/" class="hover:text-copper">Ana Sayfa</a> / <span class="text-cream/80">Projeler</span></nav>
    <span class="eyebrow mt-6">Portföy</span>
    <h1 class="h-display mt-4 text-5xl md:text-6xl"><?= $active ? e($active['name']) : 'Tüm Projeler' ?></h1>
    <p class="mt-4 max-w-2xl text-cream/70"><?= $active && $active['description'] ? e($active['description']) : 'Ankara\'da tamamladığımız mutfak dolabı, vestiyer, gardırop ve özel tasarım mobilya projelerimizden seçkiler.' ?></p>
  </div>
</section>
<section class="py-14">
  <div class="container-x">
    <div class="mb-10 flex flex-wrap gap-2">
      <a href="/projeler" class="filter-pill <?= !$active ? 'active' : '' ?>">Tümü</a>
      <?php foreach ($categories as $c): ?>
        <a href="/projeler?kategori=<?= e($c['slug']) ?>" class="filter-pill <?= $active && $active['id'] === $c['id'] ? 'active' : '' ?>"><?= e($c['name']) ?> <span class="opacity-50">(<?= (int) $c['project_count'] ?>)</span></a>
      <?php endforeach; ?>
    </div>
    <?php if (!$projects): ?>
      <p class="rounded-2xl bg-beige/60 p-10 text-center text-walnut/60">Bu kategoride henüz proje eklenmemiş.</p>
    <?php else: ?>
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($projects as $i => $p) echo \App\View::partial('_project_card', ['p' => $p, 'delay' => $i]); ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?= \App\View::partial('_cta') ?>
