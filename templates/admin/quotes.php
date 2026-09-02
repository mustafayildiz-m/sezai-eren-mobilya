<h1 class="font-serif text-4xl">Teklif Talepleri</h1>
<p class="mt-1 text-sm text-walnut/60"><?= count($quotes) ?> talep</p>
<div class="mt-8 space-y-4">
  <?php foreach ($quotes as $q): $wa = 'https://wa.me/' . (str_starts_with($q['phone'], '0') ? '9' . $q['phone'] : $q['phone']); ?>
    <article class="card p-6 <?= $q['is_read'] ? 'opacity-80' : 'ring-2 ring-copper/40' ?>">
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
          <div class="flex items-center gap-3"><h2 class="text-lg font-medium"><?= e($q['name']) ?></h2><?php if (!$q['is_read']): ?><span class="rounded-full bg-copper px-2 py-0.5 text-[10px] uppercase tracking-wider text-cream">Yeni</span><?php endif; ?></div>
          <div class="mt-1 text-sm text-walnut/60"><?= e($q['job_type']) ?> · <?= date('d.m.Y H:i', strtotime($q['created_at'])) ?></div>
        </div>
        <div class="flex flex-wrap gap-2 text-sm">
          <a href="tel:<?= e($q['phone']) ?>" class="btn-outline !px-4 !py-2">☎ <?= e($q['phone']) ?></a>
          <a href="<?= $wa ?>" target="_blank" class="btn-outline !px-4 !py-2 !border-[#25D366] !text-[#128C7E] hover:!bg-[#25D366] hover:!text-white">WhatsApp</a>
          <?php if ($q['email']): ?><a href="mailto:<?= e($q['email']) ?>" class="btn-outline !px-4 !py-2">✉</a><?php endif; ?>
          <?php if (!$q['is_read']): ?><form method="post" action="/yonetim/teklifler/<?= $q['id'] ?>/okundu"><?= csrf_field() ?><button class="btn-outline !px-4 !py-2">Okundu</button></form><?php endif; ?>
          <form method="post" action="/yonetim/teklifler/<?= $q['id'] ?>/sil" data-confirm="Bu talep silinsin mi?"><?= csrf_field() ?><button class="rounded-full border border-red-200 px-4 py-2 text-red-600 hover:bg-red-50">Sil</button></form>
        </div>
      </div>
      <?php if ($q['message']): ?><p class="mt-4 whitespace-pre-line rounded-xl bg-beige/50 p-4 text-sm"><?= e($q['message']) ?></p><?php endif; ?>
      <?php if ($q['photo']): ?><a href="/uploads/<?= e($q['photo']) ?>" target="_blank" class="mt-3 inline-block"><img src="/uploads/<?= e($q['photo']) ?>" alt="" class="h-32 rounded-lg object-cover"></a><?php endif; ?>
    </article>
  <?php endforeach; if (!$quotes): ?><div class="card p-12 text-center text-walnut/50">Henüz teklif talebi yok.</div><?php endif; ?>
</div>
