<?php $isNew = empty($project); $p = $project ?? ['title' => '', 'category_id' => 0, 'location' => '', 'description' => '', 'is_featured' => 0, 'sort_order' => 0]; ?>
<a href="/yonetim/projeler" class="text-sm text-walnut/50 hover:text-copper">← Projeler</a>
<h1 class="mt-2 font-serif text-4xl"><?= $isNew ? 'Yeni Proje' : e($p['title']) ?></h1>
<div class="mt-8 grid gap-8 lg:grid-cols-5">
  <form method="post" action="<?= $isNew ? '/yonetim/projeler/yeni' : '/yonetim/projeler/' . $p['id'] ?>" class="card space-y-5 p-6 lg:col-span-2">
    <?= csrf_field() ?>
    <div><label class="label">Başlık *</label><input class="input" name="title" required value="<?= e($p['title']) ?>" placeholder="Örn: Modern Beyaz Mutfak"></div>
    <div><label class="label">Kategori</label><select class="input" name="category_id"><option value="0">— Seçin —</option><?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>" <?= (int) $p['category_id'] === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select></div>
    <div><label class="label">Konum (semt)</label><input class="input" name="location" value="<?= e($p['location']) ?>" placeholder="Çankaya"></div>
    <div><label class="label">Açıklama</label><textarea class="input" name="description" rows="6" placeholder="Malzeme, ölçü, özel çözümler..."><?= e($p['description']) ?></textarea><p class="mt-1 text-xs text-walnut/50">SEO için 2-3 cümle yazmanız yeterli.</p></div>
    <div class="grid grid-cols-2 gap-4">
      <label class="flex items-center gap-3 rounded-xl border border-walnut/10 px-4 py-3 text-sm"><input type="checkbox" name="is_featured" value="1" <?= $p['is_featured'] ? 'checked' : '' ?> class="h-4 w-4 accent-copper"> Ana sayfada göster</label>
      <div><input class="input" type="number" name="sort_order" value="<?= (int) $p['sort_order'] ?>" title="Sıra (küçük önce)" placeholder="Sıra"></div>
    </div>
    <button class="btn-primary w-full"><?= $isNew ? 'Oluştur ve Fotoğraf Ekle' : 'Kaydet' ?></button>
    <?php if (!$isNew): ?><a href="/projeler/<?= e($p['slug']) ?>" target="_blank" class="block text-center text-xs text-walnut/50 hover:text-copper">Sitede gör ↗</a><?php endif; ?>
  </form>
  <section class="lg:col-span-3">
    <?php if ($isNew): ?>
      <div class="card grid h-full min-h-[280px] place-items-center p-8 text-center text-walnut/50">Fotoğraf yüklemek için önce projeyi oluşturun.</div>
    <?php else: ?>
      <div class="card p-6">
        <h2 class="font-serif text-2xl">Fotoğraflar <span class="text-base text-walnut/40">(<?= count($images) ?>)</span></h2>
        <div data-uploader data-url="/yonetim/projeler/<?= $p['id'] ?>/resim" class="mt-4 grid cursor-pointer place-items-center rounded-2xl border-2 border-dashed border-walnut/20 p-10 text-center transition hover:border-copper hover:bg-copper/5">
          <input type="file" accept="image/*" multiple class="hidden">
          <div class="text-3xl">📷</div>
          <p class="mt-2 font-medium">Fotoğrafları buraya sürükleyin veya tıklayın</p>
          <p class="mt-1 text-xs text-walnut/50">Birden fazla seçebilirsiniz. Otomatik küçültülür ve WebP'ye çevrilir.</p>
          <div data-progress class="mt-4 hidden w-full max-w-xs overflow-hidden rounded-full bg-walnut/10"><div class="h-2 w-0 bg-copper transition-all"></div></div>
        </div>
        <p class="mt-4 text-xs text-walnut/50">Sürükleyip sıralayın. Yıldız kapak fotoğrafını belirler.</p>
        <ul data-sortable data-url="/yonetim/resim/sirala" class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3">
          <?php foreach ($images as $img): ?>
            <li draggable="true" data-id="<?= $img['id'] ?>" class="group relative aspect-[4/3] cursor-grab overflow-hidden rounded-xl ring-2 <?= (int) $p['cover_image_id'] === (int) $img['id'] ? 'ring-copper' : 'ring-transparent' ?>">
              <img src="/uploads/<?= e($img['thumb']) ?>" alt="" class="h-full w-full object-cover">
              <div class="absolute inset-x-0 bottom-0 flex justify-between bg-gradient-to-t from-black/70 p-2 opacity-0 transition group-hover:opacity-100">
                <form method="post" action="/yonetim/resim/<?= $img['id'] ?>/kapak"><?= csrf_field() ?><button title="Kapak yap" class="grid h-8 w-8 place-items-center rounded-full bg-white/20 text-cream hover:bg-copper">★</button></form>
                <form method="post" action="/yonetim/resim/<?= $img['id'] ?>/sil" data-confirm="Fotoğraf silinsin mi?"><?= csrf_field() ?><button title="Sil" class="grid h-8 w-8 place-items-center rounded-full bg-white/20 text-cream hover:bg-red-600">✕</button></form>
              </div>
              <?php if ((int) $p['cover_image_id'] === (int) $img['id']): ?><span class="absolute left-2 top-2 rounded-full bg-copper px-2 py-0.5 text-[10px] uppercase tracking-wider text-cream">Kapak</span><?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </section>
</div>
