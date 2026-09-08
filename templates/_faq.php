<section class="border-t border-line py-16 md:py-24">
  <div class="container-x max-w-3xl">
    <span class="eyebrow">Sık Sorulanlar</span>
    <h2 class="h2 mt-4 text-balance"><?= e($faqTitle ?? 'Merak edilenler') ?></h2>
    <div class="mt-9 divide-y divide-line border-y border-line">
      <?php foreach ($faq as [$q, $a]): ?>
        <details class="group">
          <summary class="flex min-h-[56px] cursor-pointer list-none items-center justify-between gap-6 py-4 font-medium marker:hidden [&::-webkit-details-marker]:hidden">
            <span class="text-[15px] leading-snug"><?= e($q) ?></span>
            <span class="shrink-0 text-xl leading-none text-gold transition-transform duration-300 group-open:rotate-45" aria-hidden="true">+</span>
          </summary>
          <p class="pb-5 pr-8 text-sm leading-relaxed text-ink-dim"><?= e($a) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
