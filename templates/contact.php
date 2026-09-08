<?php
use App\Models\Setting;
$s = Setting::all();
$wa = 'https://wa.me/' . preg_replace('/\D/', '', $s['whatsapp']);
$tel = preg_replace('/\s/', '', $s['phone']);
?>
<section class="border-b border-line pb-14 pt-32 md:pb-20 md:pt-40">
  <div class="container-x">
    <nav class="text-xs text-ink-dim" aria-label="Sayfa yolu"><a href="/" class="transition hover:text-gold">Ana Sayfa</a> <span class="px-1">/</span> <span class="text-ink">İletişim</span></nav>
    <span class="eyebrow mt-6">İletişim</span>
    <h1 class="h1 mt-4 max-w-[18ch] text-balance">Bir kahve içelim, projenizi konuşalım</h1>
  </div>
</section>

<section class="py-14 md:py-20">
  <div class="container-x grid gap-8 lg:grid-cols-5 lg:gap-10">
    <div class="space-y-3 lg:col-span-2">
      <?php foreach ([
        ['Telefon', $s['phone'], 'tel:' . $tel],
        ['WhatsApp', 'Hemen yazın', $wa],
        ['E-posta', $s['email'], 'mailto:' . $s['email']],
        ['Adres', $s['address'], null],
        ['Çalışma Saatleri', $s['working_hours'], null],
        ['Instagram', '@' . basename(rtrim($s['instagram'], '/')), $s['instagram']],
      ] as $i => [$l, $v, $href]): ?>
        <div class="card flex min-h-[76px] items-center justify-between gap-4 p-5 reveal reveal-delay-<?= $i % 4 ?>">
          <div class="min-w-0">
            <div class="text-[11px] uppercase tracking-widest text-ink-dim"><?= e($l) ?></div>
            <div class="mt-1 truncate font-medium"><?= e($v) ?></div>
          </div>
          <?php if ($href): ?>
            <a href="<?= e($href) ?>" <?= str_starts_with($href, 'http') ? 'target="_blank" rel="noopener"' : '' ?>
               class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-gold/12 text-gold transition hover:bg-gold hover:text-bg"
               aria-label="<?= e($l) ?>">→</a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
      <a href="/teklif-al" class="btn-primary w-full reveal">Ücretsiz Teklif Formu</a>
    </div>

    <div class="overflow-hidden rounded-xl ring-1 ring-line lg:col-span-3 reveal reveal-delay-1">
      <iframe src="<?= e($s['map_embed']) ?>" title="Sezai Eren Mobilya konum"
              width="100%" height="100%" style="border:0;min-height:420px;filter:grayscale(.35) contrast(1.05) brightness(.85)"
              loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    </div>
  </div>
</section>

<section class="border-t border-line py-14 md:py-16">
  <div class="container-x">
    <span class="eyebrow">Hizmet Bölgemiz</span>
    <h2 class="h2 mt-4 text-balance">Ankara'nın tamamına keşfe geliyoruz</h2>
    <p class="lead mt-4 max-w-2xl">Atölyemiz Siteler'de (Altındağ). Ankara içinde keşif, ölçü ve teklif ücretsizdir; mesafe için ek nakliye ücreti almıyoruz. En yoğun çalıştığımız ilçeler:</p>
    <ul class="mt-6 flex flex-wrap gap-2">
      <?php foreach ($districts as $d): ?>
        <li><a href="<?= e($d['path']) ?>" class="pill"><?= e($d['name']) ?> Mobilya</a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
