<?php
use App\Models\Setting;
use App\Models\Project;
use App\Models\Category;
$s = Setting::all();
// Sayılar veritabanından geliyor — uydurma rakam yok.
$projectCount = Project::count();
$serviceCount = count(array_filter(Category::all(), fn($c) => (int) $c['project_count'] > 0));
?>
<section class="border-b border-line pb-14 pt-32 md:pb-20 md:pt-40">
  <div class="container-x">
    <nav class="text-xs text-ink-dim" aria-label="Sayfa yolu"><a href="/" class="transition hover:text-gold">Ana Sayfa</a> <span class="px-1">/</span> <span class="text-ink">Hakkımızda</span></nav>
    <span class="eyebrow mt-6">Hakkımızda</span>
    <h1 class="h1 mt-4 max-w-[18ch] text-balance">Ahşaba saygı, ölçüye sadakat, müşteriye söz</h1>
  </div>
</section>

<section class="py-16 md:py-24">
  <div class="container-x grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
    <div class="img-zoom overflow-hidden rounded-xl reveal">
      <img src="/assets/about.webp" width="1242" height="1378" loading="lazy" decoding="async"
           alt="Sezai Eren, Siteler'deki atölyesinde ürettiği masalarla"
           class="aspect-[4/5] w-full object-cover">
    </div>
    <div class="reveal reveal-delay-1">
      <h2 class="h2">Sezai Eren kimdir?</h2>
      <p class="lead mt-6"><?= nl2br(e($s['about'])) ?></p>
      <p class="lead mt-4">Siteler'deki atölyemizde her parça tek tek kesilir, kenar bantları ısıyla yapıştırılır, mekanizmalar test edilir. Montaja giden her dolap bir kez daha ustanın elinden geçer. Çünkü bizim için iş, montaj bittiğinde değil; siz yıllar sonra hâlâ memnun olduğunuzda biter.</p>

      <div class="mt-10 grid grid-cols-3 gap-6 border-t border-line pt-8">
        <?php foreach ([
          [(int) ($s['years'] ?? 20), '+', 'Yıl Tecrübe'],
          [$projectCount, '', 'Yayındaki Proje'],
          [$serviceCount, '', 'Hizmet Kolu'],
        ] as [$n, $suffix, $l]): ?>
          <div>
            <div class="font-serif text-3xl font-light text-gold sm:text-4xl"><span data-count="<?= $n ?>" data-suffix="<?= $suffix ?>">0</span></div>
            <div class="mt-1 text-[11px] uppercase tracking-widest text-ink-dim"><?= $l ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="border-y border-line bg-surface py-16 md:py-24">
  <div class="container-x">
    <div class="max-w-2xl reveal">
      <span class="eyebrow">Süreç</span>
      <h2 class="h2 mt-4">Dört adımda yeni dolabınız</h2>
    </div>
    <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach ([
        ['Keşif', 'Evinize gelir, ölçü alır, ihtiyaçlarınızı dinleriz. Ankara içinde ücretsiz.'],
        ['Tasarım', '3 boyutlu çizim ve malzeme örnekleriyle sunum, kalem kalem yazılı teklif.'],
        ['Üretim', 'Atölyemizde kesim, kenar bantlama ve montaj tek elden yapılır.'],
        ['Montaj', 'Kendi ekibimizle temiz, hızlı ve garantili montaj. Taşeron yok.'],
      ] as $i => [$h, $t]): ?>
        <div class="card p-6 reveal reveal-delay-<?= $i % 4 ?>">
          <div class="font-serif text-4xl font-light text-gold/45">0<?= $i + 1 ?></div>
          <h3 class="h3 mt-4"><?= e($h) ?></h3>
          <p class="mt-2 text-sm leading-relaxed text-ink-dim"><?= e($t) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?= \App\View::partial('_cta') ?>
