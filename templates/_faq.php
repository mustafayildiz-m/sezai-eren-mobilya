<section class="py-20">
  <div class="container-x max-w-3xl">
    <span class="eyebrow">Sık Sorulanlar</span>
    <h2 class="h-display mt-4 text-4xl md:text-5xl"><?= e($faqTitle ?? 'Merak edilenler') ?></h2>
    <div class="mt-10 divide-y divide-walnut/10 border-y border-walnut/10">
      <?php foreach ($faq as [$q, $a]): ?>
        <details class="group py-5">
          <summary class="flex cursor-pointer list-none items-center justify-between gap-6 font-medium text-walnut marker:hidden">
            <span><?= e($q) ?></span>
            <span class="shrink-0 text-copper transition group-open:rotate-45">+</span>
          </summary>
          <p class="mt-3 pr-10 text-sm leading-relaxed text-walnut/70"><?= e($a) ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
