<section class="border-b border-line pb-14 pt-32 md:pb-20 md:pt-40">
  <div class="container-x">
    <nav class="text-xs text-ink-dim" aria-label="Sayfa yolu"><a href="/" class="transition hover:text-gold">Ana Sayfa</a> <span class="px-1">/</span> <span class="text-ink"><?= e($d['name']) ?> Mobilya</span></nav>
    <span class="eyebrow mt-6"><?= e($d['name']) ?> · Ankara</span>
    <h1 class="h1 mt-4 max-w-[20ch] text-balance"><?= e($d['name']) ?>'da Ölçüye Özel Mobilya</h1>
    <p class="lead mt-6 max-w-2xl text-base sm:text-lg"><?= e($d['lead']) ?></p>
    <div class="mt-8 flex flex-wrap gap-3">
      <a href="/teklif-al" class="btn-primary"><?= e($d['name']) ?> İçin Ücretsiz Keşif</a>
      <a href="/iletisim" class="btn-ghost">Bize Ulaşın</a>
    </div>
  </div>
</section>

<section class="py-14 md:py-20">
  <div class="container-x grid gap-10 lg:grid-cols-3 lg:gap-14">
    <div class="space-y-5 lg:col-span-2">
      <h2 class="h3"><?= e($d['name']) ?>'da nasıl çalışıyoruz?</h2>
      <?php foreach ($d['body'] as $para): ?>
        <p class="leading-relaxed text-ink-dim reveal"><?= e($para) ?></p>
      <?php endforeach; ?>
    </div>
    <aside class="panel h-fit p-6">
      <h2 class="h3">Keşfe gittiğimiz mahalleler</h2>
      <p class="mt-2 text-sm text-ink-dim"><?= e($d['name']) ?> genelinde çalışıyoruz. En sık iş yaptığımız mahalleler:</p>
      <ul class="mt-4 flex flex-wrap gap-2 text-xs">
        <?php foreach ($d['neighborhoods'] as $n): ?>
          <li class="rounded-full border border-line px-3 py-1.5 text-ink-dim"><?= e($n) ?></li>
        <?php endforeach; ?>
      </ul>
    </aside>
  </div>
</section>

<section class="border-y border-line bg-surface py-14 md:py-20">
  <div class="container-x">
    <span class="eyebrow">Hizmetler</span>
    <h2 class="h2 mt-4 text-balance"><?= e($d['name']) ?>'da hangi işleri yapıyoruz?</h2>
    <div class="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($services as $i => $sv): ?>
        <a href="<?= e($sv['path']) ?>" class="card group block p-6 reveal reveal-delay-<?= $i % 4 ?>">
          <h3 class="h3 transition group-hover:text-gold"><?= e($sv['name']) ?></h3>
          <p class="mt-2 text-sm leading-relaxed text-ink-dim"><?= e(mb_substr($sv['lead'], 0, 120)) ?>…</p>
          <span class="mt-4 inline-block text-[11px] uppercase tracking-widest2 text-gold">Detay →</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($local): ?>
<section class="py-14 md:py-20">
  <div class="container-x">
    <span class="eyebrow">Referans</span>
    <h2 class="h2 mt-4 text-balance"><?= e($d['name']) ?>'da tamamladığımız işler</h2>
    <div class="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($local as $i => $p) echo \App\View::partial('_project_card', ['p' => $p, 'delay' => $i]); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?= \App\View::partial('_faq', ['faq' => $faq, 'faqTitle' => $d['name'] . ' mobilya hakkında sık sorulanlar']) ?>

<section class="border-t border-line py-10">
  <div class="container-x">
    <h2 class="text-[11px] font-medium uppercase tracking-widest2 text-ink-dim">Ankara'da hizmet verdiğimiz diğer ilçeler</h2>
    <ul class="mt-5 flex flex-wrap gap-2">
      <?php foreach ($others as $o): ?><li><a href="<?= e($o['path']) ?>" class="pill"><?= e($o['name']) ?> Mobilya</a></li><?php endforeach; ?>
    </ul>
  </div>
</section>

<?= \App\View::partial('_cta') ?>
