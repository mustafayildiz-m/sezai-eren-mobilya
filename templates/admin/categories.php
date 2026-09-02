<h1 class="font-serif text-4xl">Kategoriler</h1>
<div class="mt-8 grid gap-8 lg:grid-cols-3">
  <form method="post" action="/yonetim/kategoriler" class="card space-y-4 p-6">
    <?= csrf_field() ?>
    <h2 class="font-serif text-2xl">Yeni Kategori</h2>
    <div><label class="label">Ad *</label><input class="input" name="name" required placeholder="Örn: Kitaplık"></div>
    <div><label class="label">Kısa açıklama</label><input class="input" name="description" placeholder="Ana sayfadaki kartta görünür"></div>
    <div><label class="label">Sıra</label><input class="input" type="number" name="sort_order" value="<?= count($categories) + 1 ?>"></div>
    <button class="btn-primary w-full">Ekle</button>
  </form>
  <div class="space-y-3 lg:col-span-2">
    <?php foreach ($categories as $c): ?>
      <form method="post" action="/yonetim/kategoriler/<?= $c['id'] ?>" class="card grid items-end gap-3 p-4 sm:grid-cols-[1fr_2fr_80px_auto]">
        <?= csrf_field() ?>
        <div><label class="label">Ad</label><input class="input" name="name" value="<?= e($c['name']) ?>" required></div>
        <div><label class="label">Açıklama</label><input class="input" name="description" value="<?= e($c['description']) ?>"></div>
        <div><label class="label">Sıra</label><input class="input" type="number" name="sort_order" value="<?= (int) $c['sort_order'] ?>"></div>
        <div class="flex gap-2"><button class="btn-outline !px-4 !py-2.5">Kaydet</button><button formaction="/yonetim/kategoriler/<?= $c['id'] ?>/sil" formmethod="post" data-confirm="“<?= e($c['name']) ?>” silinsin mi? Projeler silinmez, kategorisiz kalır." class="rounded-full border border-red-200 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">Sil</button></div>
        <div class="text-xs text-walnut/40 sm:col-span-4">/projeler?kategori=<?= e($c['slug']) ?> · <?= (int) $c['project_count'] ?> proje</div>
      </form>
    <?php endforeach; ?>
  </div>
</div>
