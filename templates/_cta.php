<?php $cs = \App\Models\Setting::all(); ?>
<section class="relative overflow-hidden border-t border-line bg-gradient-to-br from-surface2 via-surface to-bg grain">
  <div class="absolute -right-40 -top-40 h-96 w-96 rounded-full bg-gold/10 blur-3xl" aria-hidden="true"></div>
  <div class="container-x relative flex flex-col items-start justify-between gap-8 py-16 md:flex-row md:items-center md:py-20">
    <div class="reveal">
      <span class="eyebrow">Ücretsiz Keşif</span>
      <h2 class="h2 mt-4 max-w-xl text-balance">Hayalinizdeki dolabı <em class="not-italic text-gold">birlikte</em> tasarlayalım.</h2>
      <p class="lead mt-4 max-w-xl">Ölçünüzü alıyor, 3 boyutlu çizimle gösteriyor, bütçenize uygun teklif hazırlıyoruz. Beğenmezseniz hiçbir ücret yok.</p>
    </div>
    <div class="flex flex-wrap gap-3 reveal reveal-delay-1">
      <a href="/teklif-al" class="btn-primary">Teklif Al</a>
      <a href="tel:<?= e(preg_replace('/\s/', '', $cs['phone'])) ?>" class="btn-ghost"><?= e($cs['phone']) ?></a>
    </div>
  </div>
</section>
