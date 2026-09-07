<?php use App\Models\Setting; $s = Setting::all(); ?>
<!-- HERO -->
<section class="relative isolate min-h-[100svh] overflow-hidden bg-ground text-cream">
  <div class="absolute inset-0 -z-10">
    <img data-hero-img src="/assets/hero.webp" alt="Ankara özel tasarım mutfak dolabı" width="1600" height="1067" fetchpriority="high" class="h-full w-full object-cover animate-kenburns opacity-70" style="transform: translate(var(--mx,0), var(--my,0))">
    <div class="absolute inset-0 bg-gradient-to-b from-ground/60 via-ground/40 to-ground"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-ground/80 via-transparent to-transparent"></div>
  </div>
  <div class="container-x flex min-h-[100svh] flex-col justify-end pb-24 pt-40 md:justify-center md:pb-32">
    <span class="eyebrow animate-fadeUp">Ankara · Siteler · 20 Yıllık Ustalık</span>
    <h1 class="h-display mt-6 max-w-4xl text-5xl sm:text-6xl md:text-7xl lg:text-8xl animate-fadeUp [animation-delay:.15s] text-balance">
      Evinize <em class="font-normal text-copper-light">ölçüye özel</em><br>mutfak, vestiyer &amp; gardırop
    </h1>
    <p class="mt-7 max-w-xl text-lg text-cream/75 animate-fadeUp [animation-delay:.3s]">Sezai Eren ustalığıyla, birinci sınıf malzeme ve milimetrik işçilik. Tasarımdan montaja tek elden, söz verdiğimiz günde teslim.</p>
    <div class="mt-10 flex flex-wrap gap-4 animate-fadeUp [animation-delay:.45s]">
      <a href="/projeler" class="btn-primary">Projeleri İncele</a>
      <a href="/teklif-al" class="btn-outline-light">Ücretsiz Teklif Al</a>
    </div>
  </div>
  <div class="absolute bottom-8 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-2 text-[10px] uppercase tracking-widest2 text-cream/50 md:flex">
    <span>Kaydır</span><span class="h-10 w-px bg-gradient-to-b from-copper to-transparent"></span>
  </div>
</section>

<!-- MARQUEE -->
<div class="overflow-hidden border-y border-walnut/10 bg-beige/60 py-4">
  <div class="flex w-max animate-marquee gap-12 whitespace-nowrap font-serif text-lg text-walnut/60">
    <?php for ($i = 0; $i < 2; $i++) foreach (['Mutfak Dolabı', 'Vestiyer', 'Gardırop', 'TV Ünitesi', 'Banyo Dolabı', 'Kitaplık', 'Çalışma Odası', 'Özel Tasarım'] as $w): ?>
      <span class="flex items-center gap-12"><?= $w ?> <span class="h-1.5 w-1.5 rounded-full bg-copper"></span></span>
    <?php endforeach; ?>
  </div>
</div>

<!-- SERVICES -->
<section class="py-24 md:py-32">
  <div class="container-x">
    <div class="mb-14 max-w-2xl reveal">
      <span class="eyebrow">Neler Yapıyoruz</span>
      <h2 class="h-display mt-4 text-4xl md:text-5xl text-balance">Evinizde dolap olarak aklınıza ne geliyorsa</h2>
      <p class="mt-4 text-walnut/70">Her mekân farklıdır. Biz de hazır ölçü kullanmıyoruz: yerinde ölçü alıp, kullanım alışkanlıklarınıza göre tasarlıyoruz.</p>
    </div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($categories as $i => $c): $icons = ['🍳','🧥','🚪','📺','🛁','✨'];
            $lp = \App\Ankara::service((string) $c['slug']); ?>
        <a href="<?= $lp ? e($lp['path']) : '/projeler?kategori=' . e($c['slug']) ?>" class="card group p-7 reveal reveal-delay-<?= $i % 4 ?>">
          <div class="flex items-start justify-between">
            <span class="grid h-12 w-12 place-items-center rounded-full bg-copper/10 text-xl transition group-hover:bg-copper group-hover:scale-110"><?= $icons[$i % 6] ?></span>
            <span class="text-xs text-walnut/40"><?= (int) $c['project_count'] ?> proje</span>
          </div>
          <h3 class="mt-6 font-serif text-2xl"><?= e($c['name']) ?></h3>
          <p class="mt-2 text-sm leading-relaxed text-walnut/65"><?= e($c['description'] ?: 'Ölçüye özel tasarım, birinci sınıf malzeme ve kusursuz montaj.') ?></p>
          <span class="mt-5 inline-flex items-center gap-2 text-sm text-copper">İncele <span class="transition group-hover:translate-x-1">→</span></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FEATURED -->
<?php if ($featured): ?>
<section class="bg-walnut py-24 text-cream md:py-32 grain relative">
  <div class="container-x relative">
    <div class="mb-14 flex flex-col justify-between gap-6 md:flex-row md:items-end reveal">
      <div class="max-w-xl">
        <span class="eyebrow">Seçilmiş Projeler</span>
        <h2 class="h-display mt-4 text-4xl md:text-5xl">Ankara'nın dört bir yanında<br>imzamızı taşıyan işler</h2>
      </div>
      <a href="/projeler" class="btn-outline-light shrink-0">Tüm Projeler</a>
    </div>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($featured as $i => $p) echo \App\View::partial('_project_card', ['p' => $p, 'delay' => $i]); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- WHY US / COUNTERS -->
<section class="py-24 md:py-32">
  <div class="container-x grid items-center gap-16 lg:grid-cols-2">
    <div class="relative reveal">
      <div class="img-zoom overflow-hidden rounded-[2rem]">
        <img src="/assets/about.webp" alt="Sezai Eren atölyede üretim başında" width="1242" height="1378" loading="lazy" class="aspect-[4/5] w-full object-cover">
      </div>
      <div class="absolute -bottom-8 -right-4 rounded-2xl bg-copper p-6 text-cream shadow-2xl md:-right-10">
        <div class="font-serif text-5xl leading-none"><span data-count="<?= (int) $s['years'] ?>" data-suffix="+">0</span></div>
        <div class="mt-1 text-xs uppercase tracking-widest2">Yıllık Tecrübe</div>
      </div>
    </div>
    <div>
      <span class="eyebrow reveal">Neden Sezai Eren?</span>
      <h2 class="h-display mt-4 text-4xl md:text-5xl reveal reveal-delay-1">Güzel görünen değil,<br>yıllarca kullanılan mobilya</h2>
      <ul class="mt-8 space-y-6 reveal reveal-delay-2">
        <?php foreach ([
          ['Yerinde ücretsiz ölçü ve keşif', 'Evinize gelir, ölçer, ihtiyaçlarınızı dinleriz. Teklif tamamen ücretsizdir.'],
          ['3D tasarım ile önce görün', 'Üretime geçmeden önce dolabınızı bilgisayarda birebir görürsünüz.'],
          ['Birinci sınıf malzeme', 'Blum / Hettich mekanizmalar, E1 standardı MDF-lam, suya dayanıklı yüzeyler.'],
          ['Söz verdiğimiz günde montaj', 'Kendi ekibimizle montaj yapar, arkamızda pislik bırakmayız.'],
        ] as [$h, $t]): ?>
          <li class="flex gap-4">
            <span class="mt-1 grid h-7 w-7 shrink-0 place-items-center rounded-full bg-copper/15 text-copper text-sm">✓</span>
            <div><h3 class="font-medium"><?= $h ?></h3><p class="mt-1 text-sm text-walnut/65"><?= $t ?></p></div>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="mt-12 grid grid-cols-3 gap-6 border-t border-walnut/10 pt-8 reveal reveal-delay-3">
        <div><div class="font-serif text-4xl text-copper"><span data-count="<?= (int) $s['projects_done'] ?>" data-suffix="+">0</span></div><div class="mt-1 text-xs uppercase tracking-widest text-walnut/50">Teslim Proje</div></div>
        <div><div class="font-serif text-4xl text-copper"><span data-count="<?= (int) $s['happy_clients'] ?>" data-suffix="+">0</span></div><div class="mt-1 text-xs uppercase tracking-widest text-walnut/50">Mutlu Müşteri</div></div>
        <div><div class="font-serif text-4xl text-copper"><span data-count="100" data-suffix="%">0</span></div><div class="mt-1 text-xs uppercase tracking-widest text-walnut/50">Ölçüye Özel</div></div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="bg-beige/50 py-24 md:py-32">
  <div class="container-x">
    <div class="mb-14 text-center reveal">
      <span class="eyebrow justify-center">Müşterilerimiz</span>
      <h2 class="h-display mt-4 text-4xl md:text-5xl">Bizi anlatanlar</h2>
    </div>
    <div class="grid gap-6 md:grid-cols-3">
      <?php foreach ([
        ['Mutfağımız tam istediğimiz gibi oldu. Ölçüde tek bir hata yok, çekmeceler kusursuz kayıyor. Sezai Bey işine gerçekten âşık.', 'Ayşe K.', 'Çankaya'],
        ['Vestiyer için üç firma gezdik, fiyatı da işçiliği de en dürüst burasıydı. Söz verilen tarihte monte edildi.', 'Murat D.', 'Yenimahalle'],
        ['Gardırobu 3D çizimde gördüğümüz için hiç sürpriz olmadı. Malzeme kalitesi hissediliyor. Tavsiye ederiz.', 'Elif & Cem S.', 'Keçiören'],
      ] as $i => [$q, $n, $d]): ?>
        <figure class="card p-8 reveal reveal-delay-<?= $i ?>">
          <div class="text-copper" aria-label="5 yıldız">★★★★★</div>
          <blockquote class="mt-4 font-serif text-xl leading-relaxed text-walnut/85">“<?= $q ?>”</blockquote>
          <figcaption class="mt-6 text-sm"><span class="font-medium"><?= $n ?></span> <span class="text-walnut/50">· <?= $d ?>, Ankara</span></figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ANKARA: HİZMET BÖLGESİ -->
<section class="py-24">
  <div class="container-x">
    <div class="mb-12 max-w-2xl reveal">
      <span class="eyebrow">Hizmet Bölgemiz</span>
      <h2 class="h-display mt-4 text-4xl md:text-5xl text-balance">Ankara'nın her ilçesine keşfe geliyoruz</h2>
      <p class="mt-4 text-walnut/70">Atölyemiz Siteler'de. Ankara genelinde ücretsiz keşif yapıyor, mesafe için ek nakliye ücreti almıyoruz. En yoğun çalıştığımız ilçeler:</p>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($districts as $i => $d): ?>
        <a href="<?= e($d['path']) ?>" class="card group flex items-center justify-between p-5 reveal reveal-delay-<?= $i % 4 ?>">
          <span class="font-serif text-xl group-hover:text-copper transition"><?= e($d['name']) ?></span>
          <span class="text-copper transition group-hover:translate-x-1">→</span>
        </a>
      <?php endforeach; ?>
    </div>
    <p class="mt-8 text-sm text-walnut/60">
      Hizmetlerimiz:
      <?php foreach ($services as $i => $sv): ?><a href="<?= e($sv['path']) ?>" class="text-copper hover:underline">Ankara <?= e($sv['name']) ?></a><?= $i < count($services) - 1 ? ' · ' : '' ?><?php endforeach; ?>
    </p>
  </div>
</section>

<?= \App\View::partial('_faq', ['faq' => $faq, 'faqTitle' => 'Ankara\'da ölçüye özel mobilya hakkında']) ?>

<?= \App\View::partial('_cta') ?>
