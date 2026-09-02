<?php use App\Models\Setting; $s = Setting::all(); $wa = 'https://wa.me/' . preg_replace('/\D/', '', $s['whatsapp']); ?>
<section class="bg-ground pb-16 pt-36 text-cream md:pt-44">
  <div class="container-x">
    <span class="eyebrow">İletişim</span>
    <h1 class="h-display mt-4 text-5xl md:text-6xl">Bir kahve içelim, projenizi konuşalım</h1>
  </div>
</section>
<section class="py-20">
  <div class="container-x grid gap-10 lg:grid-cols-5">
    <div class="space-y-4 lg:col-span-2">
      <?php foreach ([
        ['Telefon', $s['phone'], 'tel:' . preg_replace('/\s/', '', $s['phone'])],
        ['WhatsApp', 'Hemen yazın', $wa],
        ['E-posta', $s['email'], 'mailto:' . $s['email']],
        ['Adres', $s['address'], null],
        ['Çalışma Saatleri', $s['working_hours'], null],
        ['Instagram', '@' . basename(rtrim($s['instagram'], '/')), $s['instagram']],
      ] as $i => [$l, $v, $href]): ?>
        <div class="card flex items-center justify-between p-6 reveal reveal-delay-<?= $i % 4 ?>">
          <div><div class="text-xs uppercase tracking-widest text-walnut/50"><?= $l ?></div><div class="mt-1 font-medium"><?= e($v) ?></div></div>
          <?php if ($href): ?><a href="<?= e($href) ?>" <?= str_starts_with($href, 'http') ? 'target="_blank" rel="noopener"' : '' ?> class="grid h-10 w-10 place-items-center rounded-full bg-copper/10 text-copper transition hover:bg-copper hover:text-cream">→</a><?php endif; ?>
        </div>
      <?php endforeach; ?>
      <a href="/teklif-al" class="btn-primary w-full reveal">Ücretsiz Teklif Formu</a>
    </div>
    <div class="overflow-hidden rounded-[2rem] ring-1 ring-walnut/10 lg:col-span-3 reveal reveal-delay-1 min-h-[420px]">
      <iframe src="<?= e($s['map_embed']) ?>" title="Sezai Eren Mobilya konum" width="100%" height="100%" style="border:0;min-height:420px;filter:sepia(.25) saturate(.8)" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    </div>
  </div>
</section>
