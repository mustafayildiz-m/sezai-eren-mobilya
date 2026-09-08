<section class="border-b border-line pb-12 pt-32 md:pb-16 md:pt-40">
  <div class="container-x">
    <nav class="text-xs text-ink-dim" aria-label="Sayfa yolu">
      <a href="/" class="transition hover:text-gold">Ana Sayfa</a> <span class="px-1">/</span>
      <?php if ($active): ?><a href="/projeler" class="transition hover:text-gold">Projeler</a> <span class="px-1">/</span> <span class="text-ink"><?= e($active['name']) ?></span>
      <?php else: ?><span class="text-ink">Projeler</span><?php endif; ?>
    </nav>
    <span class="eyebrow mt-6">Portföy</span>
    <h1 class="h1 mt-4 text-balance"><?= $active ? e($title) : 'Tüm Projeler' ?></h1>
    <p class="lead mt-4 max-w-2xl"><?= $active && $active['description'] ? e($active['description']) : 'Ankara\'da tamamladığımız mutfak dolabı, gardırop, iç kapı ve özel tasarım mobilya projelerimizden seçkiler.' ?></p>
  </div>
</section>

<section class="py-10 md:py-14">
  <div class="container-x">
    <!-- Telefonda yatay kaydırmalı şerit -->
    <div class="scroll-x -mx-5 px-5 sm:mx-0 sm:px-0 sm:flex-wrap">
      <a href="/projeler" class="pill <?= !$active ? 'pill-active' : '' ?>">Tümü</a>
      <?php foreach ($categories as $c): if (!(int) $c['project_count']) continue; ?>
        <a href="/projeler?kategori=<?= e($c['slug']) ?>" class="pill <?= $active && $active['id'] === $c['id'] ? 'pill-active' : '' ?>">
          <?= e($c['name']) ?> <span class="ml-1.5 opacity-60"><?= (int) $c['project_count'] ?></span>
        </a>
      <?php endforeach; ?>
    </div>

    <?php if (!$projects): ?>
      <p class="mt-10 rounded-xl bg-surface p-10 text-center text-ink-dim">Bu kategoride henüz proje eklenmemiş.</p>
    <?php else: ?>
      <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($projects as $i => $p) echo \App\View::partial('_project_card', ['p' => $p, 'delay' => $i]); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($landing)): ?>
      <p class="mt-10 text-sm leading-relaxed text-ink-dim">
        Ankara'da <?= e(mb_strtolower($active['name'])) ?> üretim sürecimiz, malzeme seçenekleri ve fiyatlandırma hakkında ayrıntılı bilgi:
        <a href="<?= e($landing['path']) ?>" class="font-medium text-gold transition hover:underline">Ankara <?= e($landing['name']) ?></a>
      </p>
    <?php endif; ?>
  </div>
</section>

<?= \App\View::partial('_cta') ?>
