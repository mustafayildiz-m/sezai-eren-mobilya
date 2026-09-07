<?php use App\Models\Setting; $s = Setting::all(); ?>
<section class="bg-ground pb-16 pt-36 text-cream md:pt-44">
  <div class="container-x">
    <span class="eyebrow">Hakkımızda</span>
    <h1 class="h-display mt-4 max-w-3xl text-5xl md:text-6xl text-balance">Ahşaba saygı, ölçüye sadakat, müşteriye söz</h1>
  </div>
</section>
<section class="py-20 md:py-28">
  <div class="container-x grid items-center gap-14 lg:grid-cols-2">
    <div class="img-zoom overflow-hidden rounded-[2rem] reveal">
      <img src="/assets/about.webp" alt="Sezai Eren, Siteler'deki atölyesinde ürettiği masalarla" width="1242" height="1378" loading="lazy" class="aspect-[4/5] w-full object-cover">
    </div>
    <div class="reveal reveal-delay-1">
      <h2 class="font-serif text-3xl md:text-4xl">Sezai Eren kimdir?</h2>
      <p class="mt-6 leading-relaxed text-walnut/75"><?= nl2br(e($s['about'])) ?></p>
      <p class="mt-4 leading-relaxed text-walnut/75">Siteler'deki atölyemizde her parça tek tek kesilir, kenar bantları ısıyla yapıştırılır, mekanizmalar test edilir. Montaja giden her dolap, bir kez daha ustanın elinden geçer. Çünkü bizim için iş, montaj bittiğinde değil; siz yıllar sonra hâlâ memnun olduğunuzda biter.</p>
      <div class="mt-10 grid grid-cols-2 gap-6 sm:grid-cols-3">
        <?php foreach ([[$s['years'], 'Yıl Tecrübe'], [$s['projects_done'], 'Teslim Proje'], [$s['happy_clients'], 'Mutlu Müşteri']] as [$n, $l]): ?>
          <div><div class="font-serif text-4xl text-copper"><span data-count="<?= (int) $n ?>" data-suffix="+">0</span></div><div class="mt-1 text-xs uppercase tracking-widest text-walnut/50"><?= $l ?></div></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<section class="bg-beige/50 py-20 md:py-28">
  <div class="container-x">
    <div class="mb-12 max-w-2xl reveal"><span class="eyebrow">Süreç</span><h2 class="h-display mt-4 text-4xl md:text-5xl">Dört adımda yeni dolabınız</h2></div>
    <div class="grid gap-6 md:grid-cols-4">
      <?php foreach ([['Keşif', 'Evinize gelir, ölçü alır, ihtiyaçlarınızı dinleriz.'], ['Tasarım', '3D çizim ve malzeme örnekleriyle sunum yaparız.'], ['Üretim', 'Atölyemizde CNC hassasiyetiyle üretim, elde kontrol.'], ['Montaj', 'Kendi ekibimizle temiz, hızlı ve garantili montaj.']] as $i => [$h, $t]): ?>
        <div class="card p-7 reveal reveal-delay-<?= $i ?>">
          <div class="font-serif text-5xl text-copper/40">0<?= $i + 1 ?></div>
          <h3 class="mt-4 font-serif text-2xl"><?= $h ?></h3>
          <p class="mt-2 text-sm text-walnut/65"><?= $t ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?= \App\View::partial('_cta') ?>
