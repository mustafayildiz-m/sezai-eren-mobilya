<h1 class="font-serif text-4xl">Site Ayarları</h1>
<form method="post" action="/yonetim/ayarlar" class="mt-8 grid gap-8 lg:grid-cols-2">
  <?= csrf_field() ?>
  <div class="card space-y-4 p-6">
    <h2 class="font-serif text-2xl">İletişim</h2>
    <?php foreach ([['phone', 'Telefon (görünen)', '+90 5XX XXX XX XX'], ['whatsapp', 'WhatsApp numarası (sadece rakam, 90 ile)', '905XXXXXXXXX'], ['email', 'E-posta', ''], ['address', 'Adres', ''], ['working_hours', 'Çalışma saatleri', ''], ['instagram', 'Instagram linki', 'https://instagram.com/...']] as [$k, $l, $ph]): ?>
      <div><label class="label"><?= $l ?></label><input class="input" name="<?= $k ?>" value="<?= e($s[$k] ?? '') ?>" placeholder="<?= $ph ?>"></div>
    <?php endforeach; ?>
    <div><label class="label">Google Harita embed URL</label><input class="input" name="map_embed" value="<?= e($s['map_embed'] ?? '') ?>"><p class="mt-1 text-xs text-walnut/50">Google Maps → Paylaş → Harita yerleştir → src="..." içindeki adres.</p></div>
  </div>
  <div class="space-y-8">
    <div class="card space-y-4 p-6">
      <h2 class="font-serif text-2xl">SEO / Google</h2>
      <div>
        <label class="label">Google Search Console doğrulama kodu</label>
        <input class="input" name="gsc_verification" value="<?= e($s['gsc_verification'] ?? '') ?>" placeholder="abc123...">
        <p class="mt-1 text-xs text-walnut/50">Search Console → Mülk ekle → HTML etiketi. Yalnızca <code>content="..."</code> içindeki kodu yapıştırın, tüm etiketi değil.</p>
      </div>
      <div>
        <label class="label">Google İşletme Profili / Haritalar linki</label>
        <input class="input" name="map_url" value="<?= e($s['map_url'] ?? '') ?>" placeholder="https://maps.app.goo.gl/...">
        <p class="mt-1 text-xs text-walnut/50">İşletme profiliniz yayına girince buraya ekleyin; schema'da <code>hasMap</code> olarak bildirilir.</p>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div><label class="label">Enlem (latitude)</label><input class="input" name="latitude" value="<?= e($s['latitude'] ?? '') ?>" placeholder="39.9xxxxx"></div>
        <div><label class="label">Boylam (longitude)</label><input class="input" name="longitude" value="<?= e($s['longitude'] ?? '') ?>" placeholder="32.8xxxxx"></div>
      </div>
      <p class="text-xs text-walnut/50">Koordinatı Google Haritalar'da atölyeye sağ tıklayıp kopyalayabilirsiniz. <strong>Boş bırakın</strong> — yanlış koordinat, doğru olmayan bir koordinattan daha kötüdür; girilmezse schema'ya hiç yazılmaz.</p>
    </div>
    <div class="card space-y-4 p-6">
      <h2 class="font-serif text-2xl">Metinler</h2>
      <div><label class="label">Site adı</label><input class="input" name="site_name" value="<?= e($s['site_name'] ?? '') ?>"></div>
      <div><label class="label">Slogan (SEO açıklaması)</label><input class="input" name="tagline" value="<?= e($s['tagline'] ?? '') ?>"></div>
      <div><label class="label">Hakkımızda metni</label><textarea class="input" name="about" rows="5"><?= e($s['about'] ?? '') ?></textarea></div>
      <div class="grid grid-cols-3 gap-3">
        <div><label class="label">Yıl</label><input class="input" type="number" name="years" value="<?= e($s['years'] ?? '') ?>"></div>
        <div><label class="label">Proje</label><input class="input" type="number" name="projects_done" value="<?= e($s['projects_done'] ?? '') ?>"></div>
        <div><label class="label">Müşteri</label><input class="input" type="number" name="happy_clients" value="<?= e($s['happy_clients'] ?? '') ?>"></div>
      </div>
    </div>
    <div class="card space-y-4 p-6">
      <h2 class="font-serif text-2xl">Şifre Değiştir</h2>
      <div><label class="label">Yeni şifre (boş bırakılırsa değişmez)</label><input class="input" type="password" name="new_password" autocomplete="new-password" minlength="8"></div>
    </div>
    <button class="btn-primary w-full">Kaydet</button>
  </div>
</form>
