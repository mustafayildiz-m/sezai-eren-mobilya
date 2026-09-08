<?php
use App\Models\Setting;
$s = Setting::all();
$icons = ['🍳','🚪','🧥','📺','🛁','🚧','🪜','✨','🏨'];
?>

<!-- HERO -->
<section class="relative border-b border-line">
  <div class="grid md:min-h-[88vh] md:grid-cols-[1.05fr_.95fr]">

    <!-- Telefonda görsel üstte, masaüstünde sağda -->
    <div class="relative order-1 h-[46vh] min-h-[280px] overflow-hidden md:order-2 md:h-auto">
      <img src="/assets/hero-detail.webp"
           srcset="/assets/hero-detail-sm.webp 720w, /assets/hero-detail.webp 1200w"
           sizes="(min-width:768px) 45vw, 100vw"
           width="1200" height="1600" fetchpriority="high" decoding="async"
           alt="Doğal kenar ceviz tabakalı siyah TV ünitesi ve pirinç kulp detayı – Sezai Eren Mobilya"
           class="h-full w-full object-cover animate-kenburns">
      <div class="absolute inset-0 bg-gradient-to-t from-bg via-transparent to-transparent md:bg-gradient-to-r md:from-bg md:via-bg/25 md:to-transparent"></div>
      <!-- Telefonda logo/menü fotoğrafın üstünde okunabilsin diye -->
      <div class="absolute inset-x-0 top-0 h-36 bg-gradient-to-b from-bg via-bg/60 to-transparent md:hidden"></div>
    </div>

    <div class="order-2 flex flex-col justify-center px-5 py-14 sm:px-8 md:order-1 md:py-20 lg:px-16">
      <span class="eyebrow animate-fadeUp">Ankara · Ölçüye Özel</span>
      <h1 class="h1 mt-5 max-w-[15ch] text-balance animate-fadeUp [animation-delay:.1s]">
        Yirmi yıllık el,<br><span class="text-gold">tek bir ölçü.</span>
      </h1>
      <p class="lead mt-6 max-w-lg animate-fadeUp [animation-delay:.2s]">
        Mutfak dolabından gardıroba, ölçüsü alınmadan tek bir parça kesilmez.
        Ankara'nın her ilçesinde ücretsiz keşif, 3 boyutlu tasarım ve kendi ekibimizle montaj.
      </p>
      <div class="mt-8 flex flex-wrap gap-3 animate-fadeUp [animation-delay:.3s]">
        <a href="/teklif-al" class="btn-primary">Ücretsiz Keşif ve Teklif</a>
        <a href="/projeler" class="btn-ghost">Projeleri Gör</a>
      </div>
    </div>
  </div>
</section>

<!-- GÜVEN ŞERİDİ -->
<section class="border-b border-line bg-surface">
  <div class="container-x grid grid-cols-2 divide-line text-center md:grid-cols-4 md:divide-x">
    <?php foreach ([
      [($s['years'] ?? '20'), 'yıllık ustalık'],
      ['Ücretsiz', 'keşif ve 3D tasarım'],
      ['Kendi ekibimiz', 'taşeron yok'],
      ['Ankara', 'tüm ilçeler'],
    ] as $i => [$big, $small]): ?>
      <div class="px-3 py-7 <?= $i < 2 ? 'border-b border-line md:border-b-0' : '' ?> <?= $i % 2 === 1 ? 'border-l border-line md:border-l-0' : '' ?>">
        <div class="font-serif text-2xl font-light text-gold sm:text-3xl"><?= e($big) ?></div>
        <div class="mt-1 text-[11px] uppercase tracking-widest text-ink-dim sm:text-xs"><?= e($small) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- HİZMETLER -->
<section class="py-16 md:py-24">
  <div class="container-x">
    <div class="max-w-2xl reveal">
      <span class="eyebrow">Ne Yapıyoruz</span>
      <h2 class="h2 mt-4 text-balance">Evinizde dolap olarak aklınıza ne geliyorsa</h2>
      <p class="lead mt-4">Hazır ölçü kullanmıyoruz. Yerinde ölçü alıp kullanım alışkanlıklarınıza göre tasarlıyoruz.</p>
    </div>

    <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($categories as $i => $c):
        $lp = \App\Ankara::service((string) $c['slug']);
        $href = $lp ? $lp['path'] : '/projeler?kategori=' . $c['slug'];
        $cover = $c['cover'] ?? null; ?>
        <a href="<?= e($href) ?>" class="card img-zoom group relative block overflow-hidden reveal reveal-delay-<?= $i % 4 ?>">
          <div class="relative aspect-[16/10] overflow-hidden">
            <?php if (!empty($c['cover_thumb'])): ?>
              <img src="/uploads/<?= e($c['cover_thumb']) ?>" width="480" height="300" loading="lazy" decoding="async"
                   alt="<?= e($c['name']) ?> – Sezai Eren Mobilya" class="h-full w-full object-cover opacity-80 transition duration-500 group-hover:opacity-100">
            <?php else: ?>
              <div class="grid h-full w-full place-items-center bg-surface2 text-3xl" aria-hidden="true"><?= $icons[$i % count($icons)] ?></div>
            <?php endif; ?>
            <div class="scrim"></div>
            <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-3 p-4">
              <h3 class="h3 leading-tight"><?= e($c['name']) ?></h3>
              <span class="shrink-0 text-xs text-gold"><?= (int) $c['project_count'] ?> proje</span>
            </div>
          </div>
          <p class="px-4 pb-4 pt-3 text-sm leading-relaxed text-ink-dim"><?= e($c['description'] ?: 'Ölçüye özel tasarım, birinci sınıf malzeme ve kusursuz montaj.') ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- SEÇİLMİŞ İŞLER -->
<?php if ($featured): $big = array_shift($featured); ?>
<section class="border-y border-line bg-surface py-16 md:py-24">
  <div class="container-x">
    <div class="mb-10 flex flex-col justify-between gap-5 md:flex-row md:items-end reveal">
      <div class="max-w-xl">
        <span class="eyebrow">Seçilmiş İşler</span>
        <h2 class="h2 mt-4 text-balance">Ankara'nın dört bir yanında imzamızı taşıyan işler</h2>
      </div>
      <a href="/projeler" class="btn-ghost shrink-0">Tüm Projeler</a>
    </div>

    <div class="grid gap-4 lg:grid-cols-[1.35fr_1fr]">
      <a href="/projeler/<?= e($big['slug']) ?>" class="img-zoom group relative block overflow-hidden rounded-xl reveal">
        <!-- Sabit oran: fotoğraflar dikey olduğu için aspect-auto düzeni bozuyordu -->
        <div class="relative aspect-[4/3]">
          <img src="/uploads/<?= e($big['cover']) ?>"
               srcset="/uploads/<?= e($big['cover_thumb'] ?: $big['cover']) ?> 480w, /uploads/<?= e($big['cover']) ?> 1600w"
               sizes="(min-width:1024px) 55vw, 100vw"
               loading="lazy" decoding="async"
               alt="<?= e($big['title']) ?> – <?= e($big['category_name'] ?? '') ?>"
               class="absolute inset-0 h-full w-full object-cover">
          <div class="scrim"></div>
          <div class="absolute inset-x-0 bottom-0 p-5 sm:p-7">
            <span class="text-[11px] uppercase tracking-widest2 text-gold"><?= e($big['category_name'] ?? 'Proje') ?><?= $big['location'] ? ' · ' . e($big['location']) : '' ?></span>
            <h3 class="mt-2 font-serif text-2xl font-light leading-tight sm:text-3xl"><?= e($big['title']) ?></h3>
          </div>
        </div>
      </a>

      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1 lg:grid-rows-2">
        <?php foreach (array_slice($featured, 0, 2) as $i => $p): ?>
          <a href="/projeler/<?= e($p['slug']) ?>" class="img-zoom group relative block overflow-hidden rounded-xl lg:h-full reveal reveal-delay-<?= $i + 1 ?>">
            <div class="relative aspect-[4/3] lg:aspect-auto lg:h-full">
              <img src="/uploads/<?= e($p['cover_thumb'] ?: $p['cover']) ?>"
                   srcset="/uploads/<?= e($p['cover_thumb'] ?: $p['cover']) ?> 480w, /uploads/<?= e($p['cover']) ?> 1600w"
                   sizes="(min-width:1024px) 32vw, 50vw"
                   loading="lazy" decoding="async"
                   alt="<?= e($p['title']) ?> – <?= e($p['category_name'] ?? '') ?>"
                   class="absolute inset-0 h-full w-full object-cover">
              <div class="scrim"></div>
              <div class="absolute inset-x-0 bottom-0 p-4">
                <span class="text-[10px] uppercase tracking-widest2 text-gold"><?= e($p['category_name'] ?? 'Proje') ?></span>
                <h3 class="mt-1 font-serif text-lg font-light leading-tight sm:text-xl"><?= e($p['title']) ?></h3>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- SÜREÇ -->
<section class="py-16 md:py-24">
  <div class="container-x">
    <div class="max-w-2xl reveal">
      <span class="eyebrow">Süreç</span>
      <h2 class="h2 mt-4">Dört adımda yeni dolabınız</h2>
    </div>
    <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach ([
        ['01', 'Ücretsiz keşif', 'Randevuyu genellikle 1-2 gün içinde veriyoruz. Yerinde ölçü alıyor, duvar ve tesisat durumunu birlikte konuşuyoruz.'],
        ['02', '3D tasarım ve teklif', 'Ölçüden sonra 3 boyutlu çizim ve kalem kalem yazılı fiyat teklifi. Beğenmezseniz hiçbir yükümlülüğünüz yok.'],
        ['03', 'Atölyede üretim', 'Kesim, kenar bantlama ve montaj tek elden, Siteler\'deki atölyemizde yapılır.'],
        ['04', 'Yerinde montaj', 'Kendi ekibimizle monte ediyoruz, taşeron kullanmıyoruz. Montaj sonrası ayar talepleri ücretsiz.'],
      ] as $i => [$n, $h, $t]): ?>
        <div class="border-t border-gold/40 pt-5 reveal reveal-delay-<?= $i % 4 ?>">
          <div class="font-serif text-3xl font-light text-gold"><?= $n ?></div>
          <h3 class="mt-3 text-base font-medium"><?= e($h) ?></h3>
          <p class="mt-2 text-sm leading-relaxed text-ink-dim"><?= e($t) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ATÖLYE -->
<section class="border-y border-line bg-surface py-16 md:py-24">
  <div class="container-x grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
    <div class="img-zoom overflow-hidden rounded-xl reveal">
      <img src="/assets/about.webp" width="1242" height="1378" loading="lazy" decoding="async"
           alt="Sezai Eren, Siteler'deki atölyesinde ürettiği masalarla"
           class="aspect-[4/5] w-full object-cover">
    </div>
    <div class="reveal reveal-delay-1">
      <span class="eyebrow">Atölye</span>
      <h2 class="h2 mt-4 text-balance">Güzel görünen değil, yıllarca kullanılan mobilya</h2>
      <p class="lead mt-5"><?= e($s['about'] ?? '') ?></p>
      <ul class="mt-7 space-y-3 text-sm">
        <?php foreach ([
          'Kesim, kenar bantlama, montaj ve kurulum tek elden — sorumluluk dağılmıyor',
          'Menteşe ve raylarda yavaş kapanan Blum/Hettich sınıfı mekanizma',
          'Suya dayanıklı gövde, sıcak tutkalla bantlanmış tüm kesim kenarları',
          'Teslimden itibaren işçilik ve mekanizma garantisi',
        ] as $li): ?>
          <li class="flex gap-3">
            <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-gold" aria-hidden="true"></span>
            <span class="text-ink-dim"><?= e($li) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
      <a href="/hakkimizda" class="btn-ghost mt-8">Hakkımızda</a>
    </div>
  </div>
</section>

<!-- HİZMET BÖLGESİ -->
<section class="py-16 md:py-24">
  <div class="container-x">
    <div class="max-w-2xl reveal">
      <span class="eyebrow">Hizmet Bölgemiz</span>
      <h2 class="h2 mt-4 text-balance">Ankara'nın her ilçesine keşfe geliyoruz</h2>
      <p class="lead mt-4">Atölyemiz Siteler'de. Ankara içinde keşif ücretsiz, mesafe için ek nakliye ücreti almıyoruz.</p>
    </div>

    <div class="mt-9 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($districts as $i => $d): ?>
        <a href="<?= e($d['path']) ?>" class="card group flex min-h-[56px] items-center justify-between px-5 py-4 reveal reveal-delay-<?= $i % 4 ?>">
          <span class="font-serif text-lg transition group-hover:text-gold"><?= e($d['name']) ?></span>
          <span class="text-gold transition group-hover:translate-x-1" aria-hidden="true">→</span>
        </a>
      <?php endforeach; ?>
    </div>

    <p class="mt-8 text-sm leading-relaxed text-ink-dim">
      <span class="text-ink">Hizmetler:</span>
      <?php foreach ($services as $i => $sv): ?><a href="<?= e($sv['path']) ?>" class="text-gold transition hover:underline">Ankara <?= e($sv['name']) ?></a><?= $i < count($services) - 1 ? ' · ' : '' ?><?php endforeach; ?>
    </p>
  </div>
</section>

<?= \App\View::partial('_faq', ['faq' => $faq, 'faqTitle' => 'Ankara\'da ölçüye özel mobilya hakkında']) ?>
<?= \App\View::partial('_cta') ?>
