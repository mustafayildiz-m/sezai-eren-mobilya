<h1 class="font-serif text-4xl">Hoş geldiniz, <?= e($_SESSION['admin_name'] ?? '') ?></h1>
<p class="mt-2 text-sm text-walnut/60">Sitenizin güncel durumu.</p>
<div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
  <?php foreach ([['Proje', $stats['projects'], '/yonetim/projeler'], ['Fotoğraf', $stats['images'], '/yonetim/projeler'], ['Kategori', $stats['categories'], '/yonetim/kategoriler'], ['Okunmamış Teklif', $stats['unread'], '/yonetim/teklifler']] as [$l, $n, $h]): ?>
    <a href="<?= $h ?>" class="card p-6"><div class="text-xs uppercase tracking-widest text-walnut/50"><?= $l ?></div><div class="mt-2 font-serif text-5xl text-copper"><?= (int) $n ?></div></a>
  <?php endforeach; ?>
</div>
<div class="mt-10 grid gap-6 lg:grid-cols-2">
  <section class="card p-6">
    <div class="flex items-center justify-between"><h2 class="font-serif text-2xl">Son Teklifler</h2><a href="/yonetim/teklifler" class="text-sm text-copper">Tümü →</a></div>
    <ul class="mt-4 divide-y divide-walnut/10 text-sm">
      <?php foreach (array_slice($quotes, 0, 6) as $q): ?>
        <li class="flex items-center justify-between py-3"><span><?= $q['is_read'] ? '' : '<span class="mr-2 inline-block h-2 w-2 rounded-full bg-copper"></span>' ?><strong><?= e($q['name']) ?></strong> <span class="text-walnut/50">· <?= e($q['job_type']) ?></span></span><span class="text-xs text-walnut/40"><?= date('d.m.Y', strtotime($q['created_at'])) ?></span></li>
      <?php endforeach; if (!$quotes): ?><li class="py-3 text-walnut/50">Henüz teklif yok.</li><?php endif; ?>
    </ul>
  </section>
  <section class="card p-6">
    <h2 class="font-serif text-2xl">Hızlı İşlemler</h2>
    <div class="mt-4 grid gap-3">
      <a href="/yonetim/projeler/yeni" class="btn-primary justify-start">＋ Yeni Proje Ekle</a>
      <a href="/yonetim/ayarlar" class="btn-outline justify-start">İletişim Bilgilerini Düzenle</a>
      <a href="/" target="_blank" class="btn-outline justify-start">Siteyi Görüntüle ↗</a>
    </div>
  </section>
</div>
